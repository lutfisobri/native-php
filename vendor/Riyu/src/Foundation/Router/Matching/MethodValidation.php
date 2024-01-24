<?php
namespace Riyu\Foundation\Router\Matching;

class MethodValidation implements ValidationInterfaces
{
    public function validate($routes, $request)
    {
        $method = $routes instanceof \Riyu\Foundation\Router\Route ? $routes->getMethods() : $routes['methods'];

        return in_array($request->getMethod(), $method);
    }
}