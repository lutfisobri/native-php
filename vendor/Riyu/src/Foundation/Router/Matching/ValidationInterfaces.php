<?php
namespace Riyu\Foundation\Router\Matching;

use Riyu\Http\Request;

interface ValidationInterfaces
{
    /**
     * Validate the routes.
     * 
     * @param array $routes
     * @param \Riyu\Http\Request $request
     * @return bool
     */
    public function validate($routes, Request $request);
}