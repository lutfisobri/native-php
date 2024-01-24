<?php
namespace Riyu\Foundation\Service;

class RouteProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerRoutes();
    }

    public function registerRoutes()
    {
        // throw new \Exception('You must override the registerRoutes() method in the route provider.');
    }
}