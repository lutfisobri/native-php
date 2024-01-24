<?php
namespace Riyu\Foundation\Router\Matching;

class UriValidation implements ValidationInterfaces
{
    public function validate($routes, $request)
    {
        $path = "/";
        if ($request->getPathInfo()) {
            $path = rtrim($request->getPathInfo(), '/') ?: '/';

            if ($path !== '/' && strpos($path, '/') !== 0) {
                $path = '/' . $path;
            }
        }

        $uri = '';

        if ($routes instanceof \Riyu\Foundation\Router\Route) {
            $uri = $routes->getUri();
        } else {
            $uri = $routes['uri'];
        }

        $match = preg_match($this->compileRoute($uri), $path);
        
        if ($match) {
            $request->setParameters($this->getParameters($uri, $path));
        } else {
            $match = preg_match($this->compileRouteOptional($uri), $path);

            if ($match) {
                $request->setParameters($this->getParametersOptional($uri, $path));
            }
        }

        return $match;
    }

    protected function compileRoute($route)
    {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?<$1>[a-zA-Z0-9_ -]+)', $route);

        return '#^' . $pattern . '$#';
    }

    protected function getParameters($route, $path)
    {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?<$1>[a-zA-Z0-9_ -]+)', $route);

        preg_match('#^' . $pattern . '$#', $path, $matches);

        return $matches;
    }

    // pattern optional value {id?}
    protected function compileRouteOptional($route)
    {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\?\}/', '(?<$1>[a-zA-Z0-9_ -]+)?', $route);

        return '#^' . $pattern . '$#';
    }

    // pattern optional value {id?}
    protected function getParametersOptional($route, $path)
    {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\?\}/', '(?<$1>[a-zA-Z0-9_ -]+)?', $route);

        preg_match('#^' . $pattern . '$#', $path, $matches);

        return $matches;
    }
}