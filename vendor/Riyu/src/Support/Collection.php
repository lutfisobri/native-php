<?php
namespace Riyu\Support;

use Riyu\Contract\Support\ArrayAccess;

class Collection implements ArrayAccess
{
    protected $data = [];

    public function __construct($data = [])
    {
        if (!is_null($data) && is_array($data) && count($data) > 0) {
            $this->data = $data;
        }
    }

    public function addAll($data)
    {
        if (!is_null($data) && is_array($data) && count($data) > 0) {
            $this->data = array_merge($this->data, $data);
        }
    }

    public function get($key, $default = null)
    {
        if ($this->has($key)) {
            return $this->data[$key];
        }

        return $default;
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function has($key)
    {
        return isset($this->data[$key]);
    }

    public function remove($key)
    {
        unset($this->data[$key]);
    }

    public function all()
    {
        return $this->data;
    }

    public function clear()
    {
        $this->data = [];
    }

    public function count()
    {
        return count($this->data);
    }

    public function isEmpty()
    {
        return empty($this->data);
    }

    public function isNotEmpty()
    {
        return !$this->isEmpty();
    }

    public function keys()
    {
        return array_keys($this->data);
    }

    public function values()
    {
        return array_values($this->data);
    }
}