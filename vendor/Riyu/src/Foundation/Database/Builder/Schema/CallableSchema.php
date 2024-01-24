<?php
namespace Riyu\Foundation\Database\Builder\Schema;

abstract class CallableSchema
{
    protected $schemas = [
        Numeric::class,
        DateTime::class,
        Text::class,
        SchemaJson::class,
        Spatial::class,
    ];

    protected $attributes = [];

    public function __call($name, $arguments)
    {
        foreach ($this->schemas as $schema) {
            $schema = new $schema;

            if (method_exists($schema, $name)) {
                $result = call_user_func_array([$schema, $name], $arguments);
                $this->attributes = array_merge($this->attributes, $result->getAttributes());

                return $this;
            }
        }

        throw new \Exception("Method {$name} does not exist.");
    }

    public static function __callStatic($name, $arguments)
    {
        $schema = new self;

        return call_user_func_array([$schema, $name], $arguments);
    }
}