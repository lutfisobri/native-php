<?php
namespace Riyu\Foundation\Router\Matching;

class MethodValidation implements ValidationInterfaces
{
    public function validate($routes, $request)
    {
        return in_array($request->getMethod(), $routes['method']);
    }
}