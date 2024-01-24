<?php
namespace Riyu\Foundation\Database\Builder\Schema;

class SchemaJson implements Schema
{
    protected $attributes = [];

    public function json($name)
    {
        $this->attributes[$name] = [
            'type' => 'JSON'
        ];

        return $this;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}