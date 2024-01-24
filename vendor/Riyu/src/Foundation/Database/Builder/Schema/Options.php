<?php
namespace Riyu\Foundation\Database\Builder\Schema;

class Options extends CallableSchema
{
    protected $attributes = [];

    public function id()
    {
        $id = $this->bigInt('id')->getAttributes();
        $this->unsigned();
        $this->autoIncrement();
        $this->primaryKey();

        foreach ($id as $key => $value) {
            $this->attributes[$key] = $value;
        }

        return $this;
    }

    public function autoIncrement()
    {
        $this->attributes['auto_increment'] = [
            'auto_increment' => true
        ];

        return $this;
    }

    public function primaryKey()
    {
        $this->attributes['primary_key'] = [
            'primary_key' => true
        ];

        return $this;
    }

    public function unsigned()
    {
        $this->attributes['unsigned'] = [
            'unsigned' => true
        ];

        return $this;
    }

    public function nullable($value = true)
    {
        $this->attributes['nullable'] = [
            'nullable' => $value
        ];

        return $this;
    }

    public function default($value)
    {
        $this->attributes['default'] = [
            'default' => $value
        ];

        return $this;
    }

    public function onUpdate($value = 'RESTRICT')
    {
        $this->attributes['on_update'] = [
            'on_update' => $value
        ];

        return $this;
    }

    public function onDelete($value = 'RESTRICT')
    {
        $this->attributes['on_delete'] = [
            'on_delete' => $value
        ];

        return $this;
    }

    public function cascade()
    {
        $this->attributes['cascade'] = [
            'on_update' => 'CASCADE',
            'on_delete' => 'CASCADE'
        ];

        return $this;
    }

    public function restrict()
    {
        $this->attributes['restrict'] = [
            'on_update' => 'RESTRICT',
            'on_delete' => 'RESTRICT'
        ];

        return $this;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}