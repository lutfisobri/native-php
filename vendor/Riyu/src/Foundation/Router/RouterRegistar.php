<?php
namespace Riyu\Foundation\Router;

class RouterRegistar
{
    protected $router;

    protected $attributes = [];

    public function __construct($router)
    {
        $this->router = $router;
    }

    public function group($callback)
    {
        $this->router->group($this->attributes, $callback);
    }

    public function prefix($prefix)
    {
        $this->attributes['prefix'] = $prefix;

        return $this;
    }
}