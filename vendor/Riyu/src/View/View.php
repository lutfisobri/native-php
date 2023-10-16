<?php

namespace Riyu\View;

class View
{
    protected $context;

    public $attributes = [];

    public function __construct($context)
    {
        $this->context = $context;
    }

    public function build()
    {
        //
    }

    public function make($path, $data = [])
    {
        $basePath = $this->context->getBasePath() . '/resources/views/';
        $path = $basePath . $path;
        $this->path = $path;
        $this->setData($data);

        return $this;
    }

    public function render()
    {
        $content = null;
        $this->path = str_replace('.', '/', $this->path);
        $this->path = str_replace('/', DIRECTORY_SEPARATOR, $this->path);
        $path = $this->path;
        $riyuPath = $path . '.riyu.php';
        $viewPath = $path . '.php';

        if (file_exists($riyuPath)) {
            include_once $riyuPath;
            // $content = file_get_contents($riyuPath);
        } else if (file_exists($viewPath)) {
            include_once $viewPath;
            // $content = file_get_contents($viewPath);
        } else {
            throw new \Exception('View not found.');
        }

        // if ($content) {
        //     $content = (new Parser($this, $content))->parse();
        // } else {
        //     throw new \Exception('View not found.');
        // }

    }

    protected function form($uri, $method = 'POST', $attributes = [])
    {
        $attributes = implode(' ', $attributes);
        $attributes = trim($attributes);

        $form = '<form action="' . $uri . '" method="' . $method . '" ' . $attributes . '>';

        return $form;
    }

    protected function input($input = [])
    {
        $content = '<input ';

        foreach ($input as $key => $value) {
            $content .= $key . '="' . $value . '" ';
        }

        $content .= '>';

        return $content;
    }

    protected function endform()
    {
        return '</form>';
    }

    protected function setData($data = [])
    {
        $data = json_decode(json_encode($data));

        foreach ($data as $key => $value) {
            $this->attributes[$key] = $value;
        }
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    public function __get($name)
    {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }

        throw new \Exception('View attribute ' . $name . ' not found.');
    }
}
