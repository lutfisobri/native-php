<?php
namespace Riyu\Support;

use Riyu\Contract\Support\ArrayAccess;

class Collection implements \ArrayAccess, ArrayAccess, \Countable
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

    public function count(): int
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

    public function first()
    {
        return reset($this->data);
    }

    public function last()
    {
        return end($this->data);
    }

    public function each(callable $callback)
    {
        foreach ($this->data as $key => $value) {
            $callback($key, $value);
        }
    }

    public function map(callable $callback)
    {
        $result = [];

        foreach ($this->data as $key => $value) {
            $result[$key] = $callback($key, $value);
        }

        return $result;
    }

    public function filter(callable $callback)
    {
        $result = [];

        foreach ($this->data as $key => $value) {
            if ($callback($key, $value)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    public function sort(callable $callback)
    {
        uasort($this->data, $callback);
    }

    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset, null);
    }

    public function offsetSet($offset, $value): void
    {
        $this->set($offset, $value);
    }

    public function offsetUnset($offset): void
    {
        $this->remove($offset);
    }
}