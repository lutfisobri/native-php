<?php

namespace Riyu\View;

use stdClass;

class View
{
    /**
     * The path to the view.
     *
     * @var \Riyu\Foundation\Application
     */
    protected $context;

    public $attributes = [];

    public $data = null;

    public function __construct(\Riyu\Foundation\Application $context)
    {
        $this->context = $context;
    }

    public function build()
    {
        //
    }

    public function make($path, object|array $data = null)
    {
        $basePath = $this->context->getBasePath();
        if (substr($basePath, -1) != '/') {
            $basePath .= '/';
        }
        $basePath = $basePath . 'resources/views/';
        $path = $basePath . $path;
        $this->path = $path;
        $this->setData($data);

        return $this;
    }

    public function render()
    {
        ob_start();
        $content = $this->getContent();
        $content = $this->replaceContent($content);
        $content = $this->replacePHP($content);

        echo $content;
    }

    protected function replacePHP($content)
    {
        $__datas = $this->getDatas();
        $content = preg_replace_callback('/<\?php(.*?)\?>/s', function ($matches) use ($__datas) {
            return $this->callFunction($matches, $__datas);
        }, $content);

        return $content;
    }

    /**
     * Call function or method.
     *
     * @param string $expression
     * @return mixed
     */
    protected function callFunction(array $matches, $__datas)
    {
        foreach ($__datas as $key => $value) {
            $$key = $value;
        }

        $expression = trim($matches[1]);

        if (substr($expression, -1) != ';') {
            $expression .= ';';
        }

        try {
            ob_start();
            eval('$result = ' . $expression);
        } catch (\Exception $e) {
            $result = $expression;
        }

        return $result;
    }

    protected function replaceContent($content)
    {
        $__datas = $this->getDatas();
        $content = preg_replace_callback('/\{\{(.*)\}\}/', function ($matches) use ($__datas) {
            foreach ($__datas as $key => $value) {
                $$key = $value;
            }

            // cetak semua variabel yang tersedia
            $definedVariables = get_defined_vars();

            $expression = trim($matches[1]);

            // Evaluasi ekspresi jika itu adalah pemanggilan fungsi atau metode
            if (strpos($expression, '(') !== false && strpos($expression, ')') !== false) {
                $return = $this->callFunction($matches, $__datas);

                return $return;
            } elseif (is_callable($expression) || function_exists($expression)) {
                $result = call_user_func($expression, $data);
            } else if (is_object($expression)) {
                var_dump(is_object($expression));
                // convert object to string
                $result = json_encode($expression);
            } else if ($expression instanceof stdClass) {
                $result = json_encode($expression);
            } else if (strpos($expression, '$') !== false) {
                $result = str_replace('$', '', $expression);

                $ra = explode('->', $result);

                if (isset($definedVariables[$result])) {
                    $result = $definedVariables[$result];
                    if (is_array($result) || is_object($result)) {
                        $result = json_encode($result);
                    }
                } else {
                    if (count($ra) > 1) {
                        // dinamis total ra, bisa 3 atau lebih
                        $result = $definedVariables[$ra[0]];
                        for ($i = 1; $i < count($ra); $i++) {
                            $result = $result->{$ra[$i]};
                        }
                    }
                }
            } else {
                // Jika bukan pemanggilan fungsi atau nama fungsi/metode, evaluasi sebagai kode PHP
                // ob_start();
                eval('echo ' . $expression . ';');
                $result = ob_get_clean();
            }

            return $result;
        }, $content);

        return $content;
    }

    public function getContent()
    {
        $content = null;
        $this->path = str_replace('.', '/', $this->path);
        $this->path = str_replace('/', DIRECTORY_SEPARATOR, $this->path);
        $path = $this->path;
        $riyuPath = $path . '.riyu.php';
        $viewPath = $path . '.php';

        foreach ($this->getDatas() as $key => $value) {
            $$key = $value;
        }

        if (file_exists($riyuPath)) {
            $content = file_get_contents($filePath);
        } else if (file_exists($viewPath)) {
            $content = file_get_contents($viewPath);
        } else {
            throw new \Exception('View [' . $viewPath . '] not found.');
        }

        $content = $this->removeComment($content);

        return $content;
    }

    /**
     * Remove comment from content.
     *
     * @param string $content
     * @return string
     */
    private function removeComment($content)
    {
        // remove comment php //
        $content = preg_replace('/\/\/.*?\n/', '', $content);

        // remove comment php #
        $content = preg_replace('/#.*?\n/', '', $content);

        // remove comment html
        $content = preg_replace('/<!--.*?-->/', '', $content);

        return $content;
    }

    protected function setData($data = [])
    {
        $data = json_decode(json_encode($data));

        foreach ($data as $key => $value) {
            $this->attributes[$key] = $value;
        }
    }

    public function getDatas()
    {
        return $this->attributes;
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

    public function __toString()
    {
        return $this->attributes;
    }

    public function __debugInfo()
    {
        return $this->attributes;
    }
}
