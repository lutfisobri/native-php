<?php
namespace Riyu\Foundation\Database\Builder\Schema;

class Text implements Schema
{
    protected $attributes = [];

    public function char($name, $length = 255)
    {
        $this->attributes[$name] = [
            'type' => 'CHAR',
            'length' => $length
        ];

        return $this;
    }

    public function varchar($name, $length = 255)
    {
        $this->attributes[$name] = [
            'type' => 'VARCHAR',
            'length' => $length
        ];

        return $this;
    }

    public function tinyText($name)
    {
        $this->attributes[$name] = [
            'type' => 'TINYTEXT'
        ];

        return $this;
    }

    public function text($name)
    {
        $this->attributes[$name] = [
            'type' => 'TEXT'
        ];

        return $this;
    }

    public function mediumText($name)
    {
        $this->attributes[$name] = [
            'type' => 'MEDIUMTEXT'
        ];

        return $this;
    }

    public function longText($name)
    {
        $this->attributes[$name] = [
            'type' => 'LONGTEXT'
        ];

        return $this;
    }

    public function binary($name, $length = 255)
    {
        $this->attributes[$name] = [
            'type' => 'BINARY',
            'length' => $length
        ];

        return $this;
    }

    public function varbinary($name, $length = 255)
    {
        $this->attributes[$name] = [
            'type' => 'VARBINARY',
            'length' => $length
        ];

        return $this;
    }

    public function tinyBlob($name)
    {
        $this->attributes[$name] = [
            'type' => 'TINYBLOB'
        ];

        return $this;
    }

    public function blob($name)
    {
        $this->attributes[$name] = [
            'type' => 'BLOB'
        ];

        return $this;
    }

    public function mediumBlob($name)
    {
        $this->attributes[$name] = [
            'type' => 'MEDIUMBLOB'
        ];

        return $this;
    }

    public function longBlob($name)
    {
        $this->attributes[$name] = [
            'type' => 'LONGBLOB'
        ];

        return $this;
    }

    public function enum($name, array $values)
    {
        $this->attributes[$name] = [
            'type' => 'ENUM',
            'values' => $values
        ];

        return $this;
    }

    public function set($name, array $values)
    {
        $this->attributes[$name] = [
            'type' => 'SET',
            'values' => $values
        ];

        return $this;
    }

    public function getAttributes() : array
    {
        return $this->attributes;
    }
}