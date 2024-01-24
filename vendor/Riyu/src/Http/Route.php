<?php
namespace Riyu\Http;

use Riyu\Helpers\Riyu;

/**
 * Route
 * @method static \Riyu\Foundation\Router\Router get($uri, $action)
 * @method static \Riyu\Foundation\Router\Router post($uri, $action)
 * @method static \Riyu\Foundation\Router\Router put($uri, $action)
 * @method static \Riyu\Foundation\Router\Router patch($uri, $action)
 * @method static \Riyu\Foundation\Router\Router delete($uri, $action)
 * @method static \Riyu\Foundation\Router\Router options($uri, $action)
 * @method static \Riyu\Foundation\Router\Router any($uri, $action)
 * @method static \Riyu\Foundation\Router\Router group($attributes, $callback)
 * @method static \Riyu\Foundation\Router\RouterRegistar match($methods, $uri, $action)
 * @method static \Riyu\Foundation\Router\RouterRegistar resource($name, $controller, $options = [])
 * @method static \Riyu\Foundation\Router\RouterRegistar prefix($prefix)
 * @method static \Riyu\Foundation\Router\RouterRegistar|\Riyu\Foundation\Router\Router name($name)
 */
class Route extends Riyu
{
    public function register()
    {
        return 'router';
    }
}