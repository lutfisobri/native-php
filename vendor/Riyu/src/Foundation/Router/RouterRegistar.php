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

    public function name($name)
    {
        $this->attributes['name'] = $name;

        return $this;
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->router, $name)) {
            return $this->router->$name(...$arguments);
        }

        if (method_exists($this, $name)) {
            return $this->$name(...$arguments);
        }

        throw new \Exception("Method '$name' does not exist.");
    }
}