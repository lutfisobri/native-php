<?php
namespace Riyu\Session;

use Riyu\Contract\Session\Session;

class Store implements Session
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var bool
     */
    protected $started = false;

    public function __construct(string $name = null)
    {
        if (!is_null($name)) {
            $this->name = $name;
        }
    }

    /**
     * Start the session.
     * 
     * @return void
     */
    public function start()
    {
        if (!$this->started) {
            $this->started = true;
        }
    }

    /**
     * Get the session name.
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the session name.
     * 
     * @param string $name
     * @return void
     */
    public function setName(string $name)
    {
        if (!is_null($name)) {
            $this->name = $name;
        }
    }

    /**
     * Get the session data.
     * 
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the session data.
     * 
     * @param array $data
     * @return void
     */
    public function setData(array $data)
    {
        if (!is_null($data) && is_array($data) && count($data) > 0) {
            $this->data = $data;
        }
    }

    /**
     * Get the session data by key.
     * 
     * @param string $key
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        return $default;
    }

    /**
     * Set the session data by key.
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value = null)
    {
        if (!is_null($key)) {
            $this->data[$key] = $value;
        }
    }

    /**
     * Check if the session data exists.
     * 
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        if (isset($this->data[$key])) {
            return true;
        }

        return false;
    }

    /**
     * Remove the session data by key.
     * 
     * @param string $key
     * @return void
     */
    public function remove($key)
    {
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
        }
    }

    /**
     * Clear the session data.
     * 
     * @return void
     */
    public function clear()
    {
        $this->data = [];
    }
}