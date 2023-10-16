<?php
namespace Riyu\Contract\Support;

interface ArrayAccess
{
    public function get($key, $default = null);

    public function set($key, $value);

    public function has($key);

    public function remove($key);

    public function all();

    public function clear();

    public function keys();

    public function values();

    public function count();

    public function isEmpty();

    public function isNotEmpty();
}