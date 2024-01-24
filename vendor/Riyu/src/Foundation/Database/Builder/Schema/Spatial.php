<?php
namespace Riyu\Foundation\Database\Builder\Schema;

class Spatial implements Schema
{
    protected $attributes = [];

    public function geometry($name)
    {
        $this->attributes[$name] = [
            'type' => 'GEOMETRY'
        ];

        return $this;
    }

    public function point($name)
    {
        $this->attributes[$name] = [
            'type' => 'POINT'
        ];

        return $this;
    }

    public function linestring($name)
    {
        $this->attributes[$name] = [
            'type' => 'LINESTRING'
        ];

        return $this;
    }

    public function polygon($name)
    {
        $this->attributes[$name] = [
            'type' => 'POLYGON'
        ];

        return $this;
    }

    public function multipoint($name)
    {
        $this->attributes[$name] = [
            'type' => 'MULTIPOINT'
        ];

        return $this;
    }

    public function multilinestring($name)
    {
        $this->attributes[$name] = [
            'type' => 'MULTILINESTRING'
        ];

        return $this;
    }

    public function multipolygon($name)
    {
        $this->attributes[$name] = [
            'type' => 'MULTIPOLYGON'
        ];

        return $this;
    }

    public function geometrycollection($name)
    {
        $this->attributes[$name] = [
            'type' => 'GEOMETRYCOLLECTION'
        ];

        return $this;
    }

    public function getAttributes() : array
    {
        return $this->attributes;
    }
}