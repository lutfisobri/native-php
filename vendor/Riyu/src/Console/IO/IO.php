<?php
namespace Riyu\Console\IO;

class IO
{
    public function __call($name, $arguments)
    {
        if (method_exists(new Input, $name)) {
            return call_user_func_array([new Input, $name], $arguments);
        } elseif (method_exists(new Output, $name)) {
            return call_user_func_array([new Output, $name], $arguments);
        }

        throw new \Exception('Method [' . $name . '] does not exist.');
    }
    
    public static function __callStatic($name, $arguments)
    {
        if (method_exists(new Input, $name)) {
            return call_user_func_array([new Input, $name], $arguments);
        } elseif (method_exists(new Output, $name)) {
            return call_user_func_array([new Output, $name], $arguments);
        }

        throw new \Exception('Method [' . $name . '] does not exist.');
    }
}