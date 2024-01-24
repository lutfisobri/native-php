<?php
namespace Riyu\Foundation\Router;

use Riyu\Contract\Http\RouterInterface;
use Riyu\Contract\Http\RouterMethod;
use Riyu\Foundation\Database\Model;
use Riyu\Http\Request;
use Riyu\Http\Response;
use Riyu\View\View;
use Riyu\View\Widget\Widget;
use Riyu\Utils\Str;

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
        return $this->addRoute(['GET', 'HEAD'], $uri, $action);
    }

    public function post($uri, $action)
    {
        $this->addRoute(['POST'], $uri, $action);

        return $this;
    }

    public function put($uri, $action)
    {
        $this->addRoute(['PUT', 'PATCH'], $uri, $action);

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

    public function middleware($middleware)
    {
        $this->updateGroupStack(['middleware' => $middleware]);

        return $this;
    }

    public function group($attributes, $callback)
    {
        $this->updateGroupStack($attributes);

        if ($callback instanceof \Closure) {
            $callback = $callback->bindTo(null);
            $callback($this);
        }

        if (is_string($callback)) {
            if (file_exists($callback)) {
                require $callback;
            }
        }

        $this->removeGroupStack();
    }

    public function name($name)
    {
        $this->updateGroupStack(['name' => $name]);

        return $this;
    }

    public function setAttributes($attributes)
    {
        $this->updateGroupStack($attributes);
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
        if ($action instanceof \Closure) {
            $action = $action->bindTo(null);
        }

        $route = $this->context->make('routerCollection')->addRouter(new Route($methods, $this->parseUri($uri), $action));

        if (count($this->groupStack) > 0) {
            $this->updateRoute($route);
        }

        return $route;
    }

    public function updateRoute($route)
    {
        $lastGroup = end($this->groupStack);

        if (isset($lastGroup['name'])) {
            $route->name($lastGroup['name']);
        }

        if (isset($lastGroup['middleware'])) {
            $route->middleware($lastGroup['middleware']);
        }

        if (isset($lastGroup['prefix'])) {
            $route->prefix($lastGroup['prefix']);
        }
    }

    public function parseUri($uri) : String
    {
        if (!Str::startsWith($uri, '/')) {
            $uri = '/' . $uri;
        }

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
        $this->storeUrl($request->getUri(), $request);

        if ($route) {
            $request->route($route);
            return $this->runAction($route->getAction());
        }

        throw new \Exception('Route "' . $request->getUri() . '" not found.');
    }

    public function storeUrl()
    {
        $session = $this->context->make('session');
        $request = $this->context->make('request');
        $session->setPreviousUrl($request->server('HTTP_REFERER'));
    }

    public function runMiddleware($middleware)
    {
        $request = $this->context->make('request');

        return $this->context->make($middleware)->handle($request, function ($req) {
            if (is_null($req)) {
                return $this->context->make('response');
            }
            return $req;
        });
    }

    public function runAction($action)
    {
        if (is_array($action)) {
            if (isset($action['middleware']) && !is_null($action['middleware'])) {
                $middleware = $action['middleware'];
                if (is_array($middleware)) {
                    foreach ($middleware as $value) {
                        $result = $this->runMiddleware($value);
                        if (!$result instanceof Request) {
                            return $this->runAction($result);
                        }
                    }
                } else {
                    $result = $this->runMiddleware($middleware);
                    if (!$result instanceof Request) {
                        return $this->runAction($result);
                    }
                }
            }
    
            if (isset($action['uses'])) {
                $action = $action['uses'];
            }
        }

        $return = $this->resolveAction($action);

        $this->storeUrl();

        if (is_string($return) || is_numeric($return) || is_bool($return)) {
            echo $return;
            return;
        }

        if (is_array($return)) {
            echo json_encode($return);
            return;
        }

        if ($return instanceof Response) {
            $return->send();
            return;
        }

        if ($return instanceOf View) {
            $return->render();
            return;
        }

        if ($return instanceOf Widget) {
            echo $return->build();
            return;
        }

        if ($return instanceof Redirect || $return instanceof Redirector) {
            $return->execute();
            return;
        }

        if (is_object($return)) {
            print_r($return);
        }
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
        } else if (is_string($action)) {
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
        } else if (is_array($action)) {
            $controller = $action[0];
            $method = $action[1];

            if (count($parameters) > 0) {
                $return = (new $controller())->$method(...$parameters);
            } else {
                $return = (new $controller())->$method();
            }
        } else {
            return $action;
        }

        return $return;
    }

    public function bindParameters($action)
    {
        $request = request();
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
            $defaultValue = null;

            if ($value->isDefaultValueAvailable()) {
                $defaultValue = $value->getDefaultValue();
            }

            if ($this->context->has($name)) {
                $parameters[$parameter] = $this->context->make($name);
            } else if ($request->hasParameter($name)) {
                $parameters[$parameter] = $request->getParameter($name);
            } elseif ($defaultValue) {
                $parameters[$parameter] = $defaultValue;
            } else if (($class = $this->getTypeName($value))) {
                $parameters[$parameter] = $this->context->make($class);
                // $parameters[$parameter] = new $class();
            } else {
                $parameters[$parameter] = null;
            }

            if (!is_null($parameters[$parameter]) && is_object($parameters[$parameter]) && method_exists($parameters[$parameter], 'resolveRouteBinding')) {
                $parameters[$parameter] = $this->bindModelParamater($parameters[$parameter], $value);
            }
        }

        return $parameters;
    }

    public function bindModelParamater($model, $value)
    {
        $request = $this->context->make('request');
        $model = $model->resolveRouteBinding(array_values($request->all()), array_keys($request->all()));

        return $model;
    }

    /**
     * Get the class name of the given parameter's type, if possible.
     *
     * @param  \ReflectionParameter  $parameter
     * @return string|null
     */
    public function getTypeName($type)
    {
        $name = $type->getType();

        if (!is_null($class = $type->getDeclaringClass())) {
            $namespace = $class->getNamespaceName();

            if (!empty($namespace) && !is_null($name)) {
                $name = str_replace($namespace . '\\', '', $name);
            }
        }

        return $name;
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