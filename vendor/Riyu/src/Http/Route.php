<?php
namespace Riyu\Http;

use Riyu\Helpers\Riyu;

/**
 * Route
 * @method static void get($uri, $action)
 * @method static void post($uri, $action)
 * @method static void put($uri, $action)
 * @method static void patch($uri, $action)
 * @method static void delete($uri, $action)
 * @method static void options($uri, $action)
 * @method static void any($uri, $action)
 * @method static \Riyu\Foundation\Router\Router group($attributes, $callback)
 * @method static \Riyu\Foundation\Router\RouterRegistar match($methods, $uri, $action)
 * @method static \Riyu\Foundation\Router\RouterRegistar resource($name, $controller, $options = [])
 * @method static \Riyu\Foundation\Router\RouterRegistar prefix($prefix)
 */
class Route extends Riyu
{
    public function register()
    {
        return 'router';
    }
}