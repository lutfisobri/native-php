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