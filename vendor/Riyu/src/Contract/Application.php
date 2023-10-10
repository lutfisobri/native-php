<?php
namespace Riyu\Contract;

interface Application
{
    public function run();

    public function bind($abstract, $concrete);

    public function make($abstract);

    public function singleton($abstract, $concrete);

    public function instance($abstract, $instance);

    public function get($abstract);

    public function has($abstract);

    public function setBasePath($basePath);

    public function getBasePath();

    public function resolve($abstract, $parameters = []);

    public function register($provider);
}