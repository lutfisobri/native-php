<?php
namespace Riyu\Http;

use Riyu\Contract\ArrayInterface;

class Server implements ArrayInterface
{
    protected $server;

    public function __construct()
    {
        $this->server = $_SERVER;
    }

    public function get($key)
    {
        return $this->server[$key];
    }

    public function all()
    {
        return $this->server;
    }

    public function has($key)
    {
        return isset($this->server[$key]);
    }

    public function set($key, $value)
    {
        $this->server[$key] = $value;
    }

    public function remove($key)
    {
        unset($this->server[$key]);
    }

    public function clear()
    {
        $this->server = [];
    }

    public function count()
    {
        return count($this->server);
    }

    public function keys()
    {
        return array_keys($this->server);
    }

    public function values()
    {
        return array_values($this->server);
    }

    public function replace($server)
    {
        $this->server = $server;
    }

    public function toArray()
    {
        return $this->server;
    }

    public function __toString()
    {
        return json_encode($this->server);
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function __set($key, $value)
    {
        return $this->set($key, $value);
    }

    public function isEmpty()
    {
        return empty($this->server);
    }

    public function merge($server)
    {
        $this->server = array_merge($this->server, $server);
    }
}