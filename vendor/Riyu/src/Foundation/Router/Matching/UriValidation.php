<?php
namespace Riyu\Foundation\Router\Matching;

class UriValidation implements ValidationInterfaces
{
    public function validate($routes, $request)
    {
        $path = rtrim($request->getPathInfo(), '/') ?: '/';

        $match = preg_match($this->compileRoute($routes['uri']), $path);
        
        if ($match) {
            $request->setParameters($this->getParameters($routes['uri'], $path));
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
}