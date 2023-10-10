<?php
namespace Riyu\Helpers;

class VarDumper
{
    public static function dump($var)
    {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }
}