<?php
namespace Riyu\Foundation\Database\Builder\Schema;

class Numeric implements Schema
{
    protected $attributes = [];

    public function tinyInt($name, $length = 4)
    {
        $this->attributes[$name] = [
            'type' => 'TINYINT',
            'length' => $length
        ];

        return $this;
    }

    public function smallInt($name, $length = 6)
    {
        $this->attributes[$name] = [
            'type' => 'SMALLINT',
            'length' => $length
        ];

        return $this;
    }

    public function mediumInt($name, $length = 9)
    {
        $this->attributes[$name] = [
            'type' => 'MEDIUMINT',
            'length' => $length
        ];

        return $this;
    }

    public function int($name, $length = 11)
    {
        $this->attributes[$name] = [
            'type' => 'INT',
            'length' => $length
        ];

        return $this;
    }

    public function bigInt($name, $length = 20)
    {
        $this->attributes[$name] = [
            'type' => 'BIGINT',
            'length' => $length
        ];

        return $this;
    }

    public function decimal($name, $length = 10, $decimal = 0)
    {
        $this->attributes[$name] = [
            'type' => 'DECIMAL',
            'length' => $length,
            'decimal' => $decimal
        ];

        return $this;
    }

    public function float($name, $length = 10, $decimal = 0)
    {
        $this->attributes[$name] = [
            'type' => 'FLOAT',
            'length' => $length,
            'decimal' => $decimal
        ];

        return $this;
    }

    public function double($name, $length = 10, $decimal = 0)
    {
        $this->attributes[$name] = [
            'type' => 'DOUBLE',
            'length' => $length,
            'decimal' => $decimal
        ];

        return $this;
    }

    public function bit($name)
    {
        $this->attributes[$name] = [
            'type' => 'BIT'
        ];

        return $this;
    }

    public function boolean($name)
    {
        $this->attributes[$name] = [
            'type' => 'BOOLEAN'
        ];

        return $this;
    }

    public function serial($name)
    {
        $this->attributes[$name] = [
            'type' => 'SERIAL'
        ];

        return $this;
    }

    public function getAttributes() : array
    {
        return $this->attributes;
    }
}