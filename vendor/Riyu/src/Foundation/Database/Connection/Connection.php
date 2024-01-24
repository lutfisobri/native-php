<?php
namespace Riyu\Foundation\Database\Connection;

use PDO;
use PDOException;
use Riyu\Foundation\Application;
use Riyu\Foundation\Database\Model;

class Connection
{
    public const F_ASSOC = PDO::FETCH_ASSOC;
    public const F_OBJECT = PDO::FETCH_OBJ;
    public const F_LAZY = PDO::FETCH_LAZY;


    /**
     * @var \PDO
     */
    protected $pdo;

    protected $context;

    protected $config;

    protected $query;

    protected $fetchMode = self::F_ASSOC;

    public function __construct(Application $context)
    {
        $this->context = $context;
    }

    public function connect()
    {
        if (is_null($this->pdo)) {
            try {
                $config = $this->config = $this->context->getEnv('DATABASE') ?: $this->context->getEnv('database');
                $options = $config['options'] ?? null;
                $this->pdo = new PDO(
                    $config['DRIVER'] . ':host=' . $config['HOST'] . ';dbname=' . $config['DBNAME'],
                    $config['USERNAME'],
                    $config['PASSWORD'],
                    $options
                );
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage(), $e->getCode());
            }
        }

        return $this->pdo;
    }

    public function disconnect()
    {
        $this->pdo = null;
    }

    public function reconnect()
    {
        $this->disconnect();

        return $this->connect();
    }

    public function getPDO()
    {
        return $this->pdo;
    }

    public function query($query = null)
    {
        if (is_null($query)) {
            return $this;
        }

        $this->query = $query;

        return $this;
    }

    public function select($table, $columns = ['*'])
    {
        $this->query = 'SELECT ' . implode(', ', $columns) . ' FROM ' . $table;

        return $this;
    }

    public function insert($table, $data)
    {
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_values($data));
        // tambahkan ' pada $values
        $values = implode(', ', array_map(function ($value) {
            return "'" . $value . "'";
        }, array_values($data)));

        $this->query = "INSERT INTO " . $table . " (" . $columns . ") VALUES (" . $values . ")";

        $this->pdo->prepare($this->query)->execute();
    }

    public function update($table, $data)
    {
        $set = [];

        foreach ($data as $column => $value) {
            $set[] = $column . ' = ' . $value;
        }

        $this->query = 'UPDATE ' . $table . ' SET ' . implode(', ', $set);

        return $this;
    }

    public function delete($table)
    {
        $this->query = 'DELETE FROM ' . $table;

        return $this;
    }

    public function where($column, $operator, $value = null)
    {
        if (is_null($value)) {
            $value = $operator;
            $operator = '=';
        }

        $this->query .= ' WHERE ' . $column . ' ' . $operator . ' ' . $value;

        return $this;
    }

    public function andWhere($column, $operator, $value)
    {
        $this->query .= ' AND ' . $column . ' ' . $operator . ' ' . $value;

        return $this;
    }

    public function orWhere($column, $operator, $value)
    {
        $this->query .= ' OR ' . $column . ' ' . $operator . ' ' . $value;

        return $this;
    }

    public function orderBy($column, $order = 'ASC')
    {
        $this->query .= ' ORDER BY ' . $column . ' ' . $order;

        return $this;
    }

    public function limit($limit)
    {
        $this->query .= ' LIMIT ' . $limit;

        return $this;
    }

    public function offset($offset)
    {
        $this->query .= ' OFFSET ' . $offset;

        return $this;
    }

    public function get()
    {
        $statement = $this->pdo->prepare($this->query);
        $statement->execute();

        return $statement;
    }

    public function first()
    {
        $statement = $this->pdo->prepare($this->query);
        $statement->execute();

        return $statement->fetch();
    }

    public function last()
    {
        $statement = $this->pdo->prepare($this->query);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_NAMED);
    }

    public function count()
    {
        $statement = $this->pdo->prepare($this->query);
        $statement->execute();

        return $statement->rowCount();
    }

    public function exists()
    {
        $statement = $this->pdo->prepare($this->query);
        $statement->execute();

        return $statement->rowCount() > 0;
    }

    public function getQuery()
    {
        return $this->query;
    }
    
    // lastInsertId
    public function lastInsertId()
    {
        return 1;
    }
}