<?php
namespace Riyu\Contract\Session;

interface Session
{
    public function set($key, $value);
    public function get($key, $default = null);
    public function has($key);
    public function remove($key);
    public function clear();
}