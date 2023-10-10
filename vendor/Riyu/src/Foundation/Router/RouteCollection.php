<?php
namespace Riyu\Foundation\Router;

use Riyu\Foundation\Router\Matching\UriValidation;

class RouteCollection
{
    /**
     * @var array
     */
    protected $routes = [];

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
        $routes = $this->getRoutes();

        $currentRoute = false;

        foreach ($routes as $route) {
            if ($currentRoute != false && $currentRoute['uri'] === $route['uri']) {
                $th = $currentRoute['uri'];
                throw new \Exception("Route '$th' already registered.");
            }

            if ($this->match($route, $request)) {
                $currentRoute = $route;
            }
        }

        return $currentRoute;
    }

    protected function match($route, $request)
    {
        $methods = $route['methods'];

        if (! in_array($request->getMethod(), $methods)) {
            return false;
        }

        return (new UriValidation())->validate($route, $request);
    }
}