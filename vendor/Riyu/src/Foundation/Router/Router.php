<?php
namespace Riyu\Foundation\Router;

use Riyu\Contract\Http\RouterInterface;
use Riyu\Contract\Http\RouterMethod;
use Riyu\Http\Response;
use Riyu\View\View;
use Riyu\View\Widget\Widget;

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

        if ($callback instanceof \Closure) {
            $callback($this);
        }

        if (is_string($callback)) {
            if (file_exists($callback)) {
                require $callback;
            }
        }

        $this->removeGroupStack();
    }

    public function updateGroupStack($attributes)
    {
        if (count($this->groupStack) > 0) {
            $attributes = $this->mergeGroup($attributes);
        }

        $this->groupStack[] = $attributes;
    }

    public function mergeGroup($attributes)
    {
        $lastGroup = end($this->groupStack);

        if (isset($lastGroup['prefix'])) {
            if (isset($attributes['prefix'])) {
                $attributes['prefix'] = $lastGroup['prefix'] . $attributes['prefix'];
            } else {
                $attributes['prefix'] = $lastGroup['prefix'];
            }
        }

        return $attributes;
    }

    public function removeGroupStack()
    {
        array_pop($this->groupStack);
    }

    public function addRoute($methods, $uri, $action)
    {
        $this->context->make('routerCollection')->addRoute($methods, $this->parseUri($uri), $action);
    }

    public function parseUri($uri) : String
    {
        if (count($this->groupStack) > 0) {
            $uri = $this->mergeUri($uri);
        }

        return $uri;
    }

    public function mergeUri($uri)
    {
        $lastGroup = end($this->groupStack);

        if (isset($lastGroup['prefix'])) {
            $uri = $lastGroup['prefix'] . $uri;
        }

        return $uri;
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

        if ($return instanceof Response) {
            $return->send();
            return null;
        }

        if ($return instanceOf View) {
            echo $return->render();
            return null;
        }

        if ($return instanceOf Widget) {
            echo $return->build();
            return null;
        }

        if ($return instanceof Redirect) {
            $return->execute();
            return null;
        }

        return $return;
    }

    public function resolveAction($action)
    {
        $return = null;

        $parameters = $this->bindParameters($action);

        if (is_callable($action)) {
            if (count($parameters) > 0) {
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
        }

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

        return $parameters;
    }

    public function __call($name, $arguments)
    {
        $routeRegistar = $this->context->make('routerRegistar');
        if (method_exists($routeRegistar, $name)) {
            return $routeRegistar->$name(...$arguments);
        }

        $routeCollection = $this->context->make('routerCollection');
        if (method_exists($routeCollection, $name)) {
            return $routeCollection->$name(...$arguments);
        }

        if (method_exists($this, $name)) {
            return $this->$name(...$arguments);
        }

        throw new \Exception('Method '. $name .' not found.');
    }
}