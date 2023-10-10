<?php
namespace Riyu\Contract;

interface Container
{
    public function bind($abstract, $concrete);

    public function make($abstract);

    public function singleton($abstract, $concrete);

    public function instance($abstract, $instance);

    public function get($abstract);

    public function has($abstract);

    public function resolve($abstract, $parameters = []);
}