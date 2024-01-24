<?php
namespace Riyu\Foundation\Database\Builder;

use Riyu\Foundation\Database\Connection\Connection;

class Builder
{
    /**
     * @var \Riyu\Foundation\Database\Model
     */
    protected $model;

    protected $wheres = [];

    protected $selects = [];

    public function __construct(\Riyu\Foundation\Database\Model $model)
    {
        $this->model = $model;
    }

    public function select($columns = ['*'])
    {
        $select = is_array($columns) ? implode(', ', $columns) : func_get_args();

        $this->selects[] = $select;

        return $this;
    }

    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        if (is_array($column)) {
            foreach ($column as $key => $value) {
                $this->where($key, '=', $value, $boolean);
            }

            return $this;
        }

        if (is_null($value)) {
            $value = $operator;
            $operator = '=';
        }

        $this->wheres[] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
            'boolean' => $boolean
        ];

        return $this;
    }

    public function compileSelect()
    {
        $selects = '*';

        if (count($this->selects) > 0) {
            $selects = implode(', ', $this->selects);
        }

        $table = $this->model->getTable();

        $wheres = $this->compileWheres();

        $sql = "SELECT {$selects} FROM {$table} {$wheres}";

        return $sql;
    }

    public function compileWheres()
    {
        $wheres = '';

        if (count($this->wheres) > 0) {
            $wheres .= 'WHERE ';

            foreach ($this->wheres as $where) {
                $wheres .= $where['column'] . ' ' . $where['operator'] . ' ' . "'" . $where['value'] . "'" . ' ' . $where['boolean'] . ' ';
            }

            $wheres = rtrim($wheres, ' ');

            $wheres = substr($wheres, 0, -3);
        }

        return $wheres;
    }

    public function get()
    {
        $sql = $this->compileSelect();

        $connection = $this->model->getConnection();

        $connection->query($sql);

        $result = $connection->get();

        if ($result->rowCount() > 0) {
            $models = [];

            foreach ($result->fetchAll(Connection::F_ASSOC) as $row) {
                $model = clone $this->model;
                $model->setAttributes($row);
                $models[] = $model;
            }

            return $models;
        }

        return null;
    }

    public function first()
    {
        $sql = $this->compileSelect();

        $connection = $this->model->getConnection();
        
        $connection->query($sql);
        
        $result = $connection->get();
        
        if ($result->rowCount() > 0) {
            $this->model->setAttributes($result->fetch(Connection::F_ASSOC));

            return $this->model;
        }

        return null;
    }

    public function find($id)
    {
        $primaryKey = $this->model->getPrimaryKey();

        $this->where($primaryKey, $id);

        return $this->first();
    }
}