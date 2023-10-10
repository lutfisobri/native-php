<?php
namespace Riyu\Foundation\Router;

use Riyu\Contract\Http\RouterInterface;
use Riyu\Contract\Http\RouterMethod;

class Router implements RouterInterface, RouterMethod
{
    /**
     * @var \Riyu\Foundation\Application
     */
    protected $context;

    /**
     * @var array
     */
    protected $groupStack = [];

    /**
     * @param \Riyu\Foundation\Application $context
     */
    public function __construct($context)
    {
        $this->context = $context;
        if (!$this->context->has('routerCollection')) {
            $this->registerRouterCollection();
        }

        if (!$this->context->has('routerRegistar')) {
            $this->registerRouterRegistar();
        }
    }

    public function registerRouterRegistar()
    {
        $this->context->instance('routerRegistar', new RouterRegistar($this));
    }

    public function registerRouterCollection()
    {
        $this->context->instance('routerCollection', new RouteCollection());
    }

    public function get($uri, $action)
    {
        $this->addRoute(['GET', 'HEAD'], $uri, $action);

        return $this;
    }

    public function post($uri, $action)
    {
        $this->addRoute(['POST'], $uri, $action);

        return $this;
    }

    public function put($uri, $action)
    {
        $this->addRoute(['PUT'], $uri, $action);

        return $this;
    }

    public function delete($uri, $action)
    {
        $this->addRoute(['DELETE'], $uri, $action);

        return $this;
    }

    public function patch($uri, $action)
    {
        $this->addRoute(['PATCH'], $uri, $action);

        return $this;
    }

    public function options($uri, $action)
    {
        $this->addRoute(['OPTIONS'], $uri, $action);

        return $this;
    }

    public function any($uri, $action)
    {
        $this->addRoute(['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS'], $uri, $action);

        return $this;
    }

    public function match($methods, $uri, $action)
    {
        $this->addRoute($methods, $uri, $action);

        return $this;
    }

    public function group($attributes, $callback)
    {
        $this->updateGroupStack($attributes);

        call_user_func($callback, $this);

        array_pop($this->groupStack);
    }

    public function addRoute($methods, $uri, $action)
    {
        $this->context->make('routerCollection')->addRoute($methods, $uri, $action);
    }

    public function dispatch()
    {
        $request = $this->context->make('request');
        $route = $this->context->make('routerCollection')->matches($request);

        if ($route) {
            $action = $route['action'];
            return $this->runAction($action);
        }

        throw new \Exception('Route ' . $request->getUri() . ' not found.');
    }

    public function runAction($action)
    {
        $return = $this->resolveAction($action);

        if (is_string($return)) {
            echo $return;
            return null;
        }

        return $return;
    }

    public function resolveAction($action)
    {
        $request = $this->context->make('request');
        $return = null;

        $parameters = $this->bindParameters($action);

        if (is_callable($action)) {
            if (count($parameters) > 0) {
                $parameters = $this->context->make('request')->getParameters();
                $return = call_user_func_array($action, $parameters);
            } else {
                $return = call_user_func($action);
            }
        }

        if (is_string($action)) {
            $action = explode('@', $action);
            $controller = $action[0];
            $method = $action[1];
            if ($this->context->has($controller)) {
                $controller = $this->context->make($controller);
                $return = call_user_func([$controller, $method]);
            }

            if (count($parameters) > 0) {
                $parameters = $this->context->make('request')->getParameters();
                $return = call_user_func_array([$controller, $method], $parameters);
            } else {
                $return = call_user_func([$controller, $method]);
            }
        }

        if (is_array($action)) {
            $controller = $action[0];
            $method = $action[1];

            if (count($parameters) > 0) {
                $return = (new $controller())->$method(...$parameters);
            } else {
                $return = call_user_func([$controller, $method]);
            }
        }

        return $return;
    }

    public function bindParameters($action)
    {
        $request = $this->context->make('request');
        $parameters = [];

        if (is_callable($action)) {
            $reflection = new \ReflectionFunction($action);
            $parameters = $reflection->getParameters();
        }

        if (is_string($action)) {
            $action = explode('@', $action);
            $controller = $action[0];
            $method = $action[1];
            if ($this->context->has($controller)) {
                $controller = $this->context->make($controller);
                $parameters = (new \ReflectionClass($controller))->getMethod($method)->getParameters();
            }
        }

        if (is_array($action)) {
            $controller = $action[0];
            $method = $action[1];
            $reflection = new \ReflectionClass($controller);
            $parameters = $reflection->getMethod($method)->getParameters();
            foreach ($parameters as $parameter => $value) {
                $name = $value->name;
                if ($this->context->has($name)) {
                    $parameters[$parameter] = $this->context->make($name);
                } else if ($request->hasParameter($name)) {
                    $parameters[$parameter] = $request->getParameter($name);
                } else {
                    $parameters[$parameter] = null;
                }
            }
        }

        return $parameters;
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->context->make('routerRegistar'), $name)) {
            return call_user_func_array([$this->context->make('routerRegistar'), $name], $arguments);
        }

        if (method_exists($this->context->make('routerCollection'), $name)) {
            return call_user_func_array([$this->context->make('routerCollection'), $name], $arguments);
        }

        if (method_exists($this, $name)) {
            return call_user_func_array([$this, $name], $arguments);
        }

        throw new \Exception('Method not found.');
    }
}