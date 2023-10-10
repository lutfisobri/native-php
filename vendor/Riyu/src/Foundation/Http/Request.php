<?php
namespace Riyu\Foundation\Http;

abstract class Request
{
    protected $attributes = [];

    public function __construct()
    {
        $this->attributes = $_REQUEST;
    }

    public function all()
    {
        return $this->attributes;
    }

    public function get($key, $default = null)
    {
        return isset($this->attributes[$key]) ? $this->attributes[$key] : $default;
    }

    public function set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function has($key)
    {
        return isset($this->attributes[$key]);
    }

    public function remove($key)
    {
        unset($this->attributes[$key]);
    }

    public function only($keys)
    {
        $result = [];
        foreach ($keys as $key) {
            if (isset($this->attributes[$key])) {
                $result[$key] = $this->attributes[$key];
            }
        }
        return $result;
    }

    public function except($keys)
    {
        $result = [];
        foreach ($this->attributes as $key => $value) {
            if (!in_array($key, $keys)) {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    public function merge($attributes)
    {
        $this->attributes = array_merge($this->attributes, $attributes);
    }

    public function replace($attributes)
    {
        $this->attributes = $attributes;
    }

    public function reset()
    {
        $this->attributes = [];
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function __isset($key)
    {
        return $this->has($key);
    }

    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    public function __unset($key)
    {
        $this->remove($key);
    }

    public function __toString()
    {
        return json_encode($this->attributes);
    }
}