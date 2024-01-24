<?php
namespace Riyu\Foundation\Router;

use Riyu\Foundation\Router\Matching\MethodValidation;
use Riyu\Foundation\Router\Matching\UriValidation;

class RouteCollection
{
    /**
     * @var array
     */
    protected $routes = [];

    /**
     * @var \Riyu\Foundation\Router\Route[]
     */
    protected $routers = [];

    /**
     * @param array $methods
     * @param string $uri
     * @param mixed $action
     */
    public function addRoute($methods, $uri, $action)
    {
        $this->routes[] = [
            'methods' => $methods,
            'uri' => $uri,
            'action' => $action
        ];

        return $this->routes[count($this->routes) - 1];
    }

    public function addRouter($router)
    {
        $this->routers[] = $router;

        return $router;
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    public function matches($request)
    {
        foreach ($this->routers as $router) {
            if ($router->match($router, $request)) {
                return $router;
            }
        }
        
        return false;
    }

    protected function match($route, $request)
    {
        if (!(new MethodValidation())->validate($route, $request)) {
            return false;
        }

        return (new UriValidation())->validate($route, $request);
    }

    public function getRouteByName($name)
    {
        $routes = $this->routers;

        foreach ($routes as $route) {
            if ($route->getName() == $name) {
                return $route;
            }
        }

        throw new \Exception("Route '$name' not found.");
    }
}