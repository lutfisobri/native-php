<?php
namespace Riyu\View\Widget;

use Riyu\Support\Collection;

class Widget
{
    protected $context;

    protected $collection;

    public function __construct($context)
    {
        $this->context = $context;
        $this->collection = new Collection();
    }

    /**
     * Render the widget.
     * 
     * @return string
     */
    public function build()
    {
        throw new \Exception('Widget not found.');
    }

    public function make($class, $data = [])
    {
        $class = new $class($this->context);

        if (!is_null($data) && is_array($data) && count($data) > 0) {
            $class->setData($data);
        } else {
            $class->setData(request()->all());
        }

        return $class;
    }

    public function setData($data)
    {
        $this->collection->addAll($data);
    }

    public function forceXss($data)
    {
        return htmlspecialchars($data);
    }

    // public function get($key, $default = null)
    // {
    //     return $this->collection->get($key, $default);
    // }

    // /**
    //  * Check if the data has the key.
    //  * 
    //  * @param string $key
    //  * @return bool
    //  */
    // public function has($key)
    // {
    //     return isset($this->data[$key]);
    // }

    // public function set($key, $value)
    // {
    //     $this->data[$key] = $value;
    // }

    // public function __get($key)
    // {
    //     return $this->get($key);
    // }

    // public function __set($key, $value)
    // {
    //     return $this->set($key, $value);
    // }

    public function __call($name, $arguments)
    {
        if (method_exists($this->collection, $name)) {
            return $this->collection->$name(...$arguments);
        }

        if (method_exists($this, $name)) {
            return $this->$name(...$arguments);
        }

        throw new \Exception('Method '. $name .' not found.');
    }
}