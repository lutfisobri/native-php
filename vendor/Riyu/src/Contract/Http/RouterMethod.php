<?php
namespace Riyu\Contract\Http;

interface RouterMethod
{
    public function get($uri, $action);

    public function post($uri, $action);

    public function put($uri, $action);

    public function delete($uri, $action);

    public function patch($uri, $action);

    public function options($uri, $action);

    public function any($uri, $action);

    public function match($methods, $uri, $action);

    public function group($attributes, $callback);
}