<?php

use Riyu\Helpers\VarDumper;

if (! function_exists('dd')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed  $args
     * @return void
     */
    function dd(...$args)
    {
        foreach ($args as $x) {
            VarDumper::dump($x);
        }

        die(1);
    }
}

if (! function_exists('request')) {
    /**
     * Get the request instance.
     *
     * @return \Riyu\Http\Request
     */
    function request()
    {
        return app('request');
    }
}

if (! function_exists('response')) {
    /**
     * Get the response instance.
     *
     * @return \Riyu\Http\Response
     */
    function response()
    {
        return app('response');
    }
}

if (! function_exists('app')) {
    /**
     * Get the available container instance.
     *
     * @param  string|null  $abstract
     * @param  array  $parameters
     * @return mixed|\Riyu\Foundation\Application
     */
    function app($abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return \Riyu\Foundation\Application::getInstance();
        }

        return \Riyu\Foundation\Application::getInstance()->solve($abstract, $parameters);
    }
}

if (! function_exists('view')) {
    /**
     * Get the view instance.
     *
     * @param  string|null  $view
     * @param  array  $data
     * @return \Riyu\View\View
     */
    function view($view = null, $data = [])
    {
        if (is_null($view)) {
            return app('view');
        }

        return app('view')->make($view, $data);
    }
}

if (! function_exists('widget')) {
    /**
     * Get the widget instance.
     *
     * @param  string|null  $widget
     * @param  array  $data
     * @return \Riyu\View\Widget\Widget
     */
    function widget($path = null, $data = [])
    {
        if (is_null($path)) {
            return app('widget');
        }

        return app('widget')->make($path, $data);
    }
}

if (! function_exists('redirect')) {
    /**
     * Get the redirect instance.
     *
     * @param  string|null  $path
     * @return \Riyu\Http\Redirect
     */
    function redirect($path = null)
    {
        if (is_null($path)) {
            return app('redirect');
        }

        return app('redirect')->to($path);
    }
}

if (! function_exists('tap')) {
    /**
     * Call the given Closure with the given value then return the value.
     *
     * @param  mixed  $value
     * @param  callable|null  $callback
     * @return mixed
     */
    function tap($value, $callback = null)
    {
        if (is_null($callback)) {
            return $value;
        }

        $callback($value);

        return $value;
    }
}

if (! function_exists('asset')) {
    /**
     * Get the asset path.
     *
     * @param  string  $path
     * @return string
     */
    function asset($path)
    {
        if (strpos($path, '/') !== 0) {
            $path = '/' . $path;
        }

        return '/public' . $path;
    }
}

if (! function_exists('session')) {
    /**
     * Get the session instance.
     *
     * @return \Riyu\Session\Store|mixed
     */
    function session($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('session');
        }

        if (is_array($key)) {
            return app('session')->addAll($key);
        }

        if (!is_null($default)) {
            return app('session')->get($key, $default);
        }

        return app('session')->get($key);
    }
}

if (! function_exists('auth')) {
    /**
     * Get the auth instance.
     *
     * @return \Riyu\Foundation\Auth\Auth|null
     */
    function auth()
    {
        return app()->make(\Riyu\Foundation\Auth\Auth::class);
    }
}