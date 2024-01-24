<?php
namespace Riyu\Foundation\Database\Builder\Schema;

class DateTime implements Schema
{
    protected $attributes = [];

    public function date($name)
    {
        $this->attributes[$name] = [
            'type' => 'DATE'
        ];

        return $this;
    }

    public function dateTime($name)
    {
        $this->attributes[$name] = [
            'type' => 'DATETIME'
        ];

        return $this;
    }

    public function time($name)
    {
        $this->attributes[$name] = [
            'type' => 'TIME'
        ];

        return $this;
    }

    public function timestamp($name)
    {
        $this->attributes[$name] = [
            'type' => 'TIMESTAMP'
        ];

        return $this;
    }

    public function year($name)
    {
        $this->attributes[$name] = [
            'type' => 'YEAR'
        ];

        return $this;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}