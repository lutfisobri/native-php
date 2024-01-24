<?php
namespace Riyu\Helpers;

use Riyu\Helpers\Vardump\ViewDump;

class VarDumper
{
    public static function dump($var)
    {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }

    protected static $data = [];

    protected static $self;

    // public static function dump($var)
    // {
    //     if (is_null(self::$self)) {
    //         self::$self = new self;
    //     }

    //     self::$data[] = $var;

    //     return self::$self;
    // }

    public static function print()
    {
        if (is_null(self::$self)) {
            self::$self = new self;
        }

        $dump = widget(ViewDump::class, ['var' => self::$data]);
        echo $dump->build();
    }
}