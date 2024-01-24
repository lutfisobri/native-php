<?php
namespace Riyu\Foundation\Database;

use Riyu\Foundation\Database\Builder\Builder;

abstract class Model
{
    protected $table;

    protected $primaryKey = 'id';

    protected $fillable = [];

    protected $guarded = [];

    protected $attributes = [];

    protected $hidden = [
        'password'
    ];

    protected $connection;

    protected $query;

    protected $builder;

    public function __construct()
    {
        $this->connection = $this->getConnection();
        $this->connection->connect();
        $this->query = $this->connection->query();
        $this->builder = new Builder($this);
    }

    public function getConnection()
    {
        if (isset($this->connection)) {
            return $this->connection;
        }

        return app('db.connection');

    }

    public function getTable()
    {
        if (isset($this->table)) {
            return $this->table;
        }

        $class = get_class($this);
        $class = explode('\\', $class);
        $class = end($class);
        $class = strtolower($class);
        $class = str_replace('model', '', $class);

        return $class;
    }

    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    public function getFillable()
    {
        return $this->fillable;
    }

    public function getGuarded()
    {
        return $this->guarded;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setAttributes($attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function getAttribute($key)
    {
        if (isset($this->attributes[$key])) {
            return $this->attributes[$key];
        }

        return null;
    }

    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function save()
    {
        $fillable = $this->getFillable();
        $guarded = $this->getGuarded();
        $attributes = $this->getAttributes();

        if (empty($fillable)) {
            $fillable = array_keys($attributes);
        }

        if (!empty($guarded)) {
            $fillable = array_diff($fillable, $guarded);
        }

        $attributes = array_intersect_key($attributes, array_flip($fillable));

        $primaryKey = $this->getPrimaryKey();
        $primaryKeyValue = $this->getAttribute($primaryKey);

        if (is_null($primaryKeyValue)) {
            $this->query->insert($this->getTable(), $attributes);
        } else {
            $this->query->update($this->getTable(), $attributes, [
                $primaryKey => $primaryKeyValue
            ]);

            $this->setAttributes($attributes);

            return $this;
        }

        $primaryKeyValue = $this->query->lastInsertId();

        $this->setAttribute($primaryKey, $primaryKeyValue);

        return $this;
    }

    public function delete()
    {
        $primaryKey = $this->getPrimaryKey();
        $primaryKeyValue = $this->getAttribute($primaryKey);

        if (!is_null($primaryKeyValue)) {
            $this->query->delete($this->getTable(), [
                $primaryKey => $primaryKeyValue
            ]);

            $this->setAttributes([]);

            return $this;
        }

        return false;
    }

    public function __call($method, $parameters)
    {
        if (method_exists($this->builder, $method)) {
            return call_user_func_array([$this->builder, $method], $parameters);
        }

        throw new \Exception("Method {$method} does not exist.");
    }

    public static function __callStatic($method, $parameters)
    {
        $model = new static;
        
        if (method_exists($model, $method)) {
            return call_user_func_array([$model, $method], $parameters);
        }
        
        if (method_exists($model->builder, $method)) {
            return call_user_func_array([$model->builder, $method], $parameters);
        }

        throw new \Exception("Method {$method} does not exist.");
    }

    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }

    public function __unset($key)
    {
        unset($this->attributes[$key]);
    }

    public function __toString()
    {
        return json_encode($this->attributes);
    }

    public function __debugInfo()
    {
        return $this->attributes;
    }

    public function toArray()
    {
        return $this->attributes;
    }

    /**
     * Store the model to session.
     * 
     * @return array
     */
    public function storeToSession()
    {
        $primaryKey = $this->getPrimaryKey();

        $data = $this->toArray();
        $data = array_merge($data, [
            'namespace' => get_class($this),
            'primaryKey' => $primaryKey
        ]);

        $hidden = $this->hidden;
        $data = array_diff_key($data, array_flip($hidden));

        return $data;
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? $this->getPrimaryKey(), $value)->first() ?? $this;
    }
}