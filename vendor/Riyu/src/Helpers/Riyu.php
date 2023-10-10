<?php
namespace Riyu\Helpers;

abstract class Riyu
{
    /**
     * @var \Riyu\Foundation\Application
     */
    protected static $context;

    public function __construct($context)
    {
        static::$context = $context;
    }
    
    public static function __callStatic($name, $arguments)
    {
        $instance = new static(static::$context);
        $class = static::$context->make($instance->register());

        try {
            return call_user_func_array([$class, $name], $arguments);
        } catch (\Exception $e) {
            throw new \Exception('Method not found.');
        }
    }

    public function register() {
        throw new \Exception('Route not found.');
    }
}