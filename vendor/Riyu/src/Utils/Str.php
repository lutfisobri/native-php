<?php
namespace Riyu\Utils;

class Str
{
    public static function slug($string, $separator = '-')
    {
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9]/', $separator, $string);
        $string = preg_replace('/' . $separator . '{2,}/', $separator, $string);
        $string = trim($string, $separator);

        return $string;
    }

    public static function camelCase($string)
    {
        $string = ucwords(str_replace(['-', '_'], ' ', $string));
        $string = str_replace(' ', '', $string);
        $string = lcfirst($string);

        return $string;
    }

    public static function snakeCase($string, $separator = '_')
    {
        $string = preg_replace('/\s+/', $separator, $string);
        $string = preg_replace('/([a-z])([A-Z])/', '$1' . $separator . '$2', $string);
        $string = strtolower($string);

        return $string;
    }

    public static function random($length = 16)
    {
        $string = '';
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return $string;
    }

    public static function startsWith($string, $needle)
    {
        return strpos($string, $needle) === 0;
    }

    public static function endsWith($string, $needle)
    {
        return substr($string, -strlen($needle)) === $needle;
    }

    public static function contains($string, $needle)
    {
        return strpos($string, $needle) !== false;
    }

    public static function containsAll($string, array $needles)
    {
        foreach ($needles as $needle) {
            if (! self::contains($string, $needle)) {
                return false;
            }
        }

        return true;
    }

    public static function equals($string, $other)
    {
        return strtolower($string) === strtolower($other);
    }

    public static function equalsIgnoreCase($string, $other)
    {
        return self::equals(self::lower($string), self::lower($other));
    }

    public static function lower($string)
    {
        return strtolower($string);
    }

    public static function upper($string)
    {
        return strtoupper($string);
    }

    public static function length($string)
    {
        return strlen($string);
    }

    public static function replace($string, $search, $replace)
    {
        return str_replace($search, $replace, $string);
    }

    public static function replaceFirst($string, $search, $replace)
    {
        return preg_replace('/' . preg_quote($search, '/') . '/', $replace, $string, 1);
    }

    public static function replaceLast($string, $search, $replace)
    {
        return preg_replace('/' . preg_quote($search, '/') . '/', $replace, $string, -1);
    }
}