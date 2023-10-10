<?php
namespace Riyu\Contract;

interface ArrayInterface
{
    public function get($key);

    public function set($key, $value);

    public function has($key);

    public function remove($key);

    public function count();

    public function clear();

    public function isEmpty();

    public function toArray();

    public function keys();

    public function values();

    public function replace($array);

    public function merge($array);

    public function __toString();

    public function __get($key);

    public function __set($key, $value);
}