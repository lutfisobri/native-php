<?php

class RiyuJson
{
    protected $json;

    protected $attributes = [];

    public function addAll($path, $json)
    {
        $this->json[][$path] = $json;
    }

    public function get($path)
    {
        return $this->json[$path];
    }

    public function set($path, $json)
    {
        $this->json[$path] = $json;
    }

    public function has($path)
    {
        return isset($this->json[$path]);
    }

    public function all()
    {
        return $this->json;
    }
}