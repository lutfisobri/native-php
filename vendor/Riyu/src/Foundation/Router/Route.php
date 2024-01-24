<?php
namespace Riyu\Foundation\Router;

use Riyu\Foundation\Router\Matching\MethodValidation;
use Riyu\Foundation\Router\Matching\UriValidation;

class Route
{
    protected $methods;

    protected $uri;

    protected $action;

    protected $name;

    protected $prefix;

    public function __construct($methods, $uri, $action)
    {
        $this->methods = $methods;
        $this->uri = $uri;
        $this->action = [
            'uses' => $action,
            'middleware' => []
        ];
    }

    public function match($route, $request)
    {
        $validations = [
            new MethodValidation(),
            new UriValidation()
        ];

        foreach ($validations as $validation) {
            if (! $validation->validate($route, $request)) {
                return false;
            }
        }

        return true;
    }

    public function getMethods()
    {
        return $this->methods;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    public function name($name)
    {
        $this->name = $name;

        return $this;
    }

    public function prefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function middleware($middleware = null)
    {
        if (is_null($middleware)) {
            return $this->action['middleware'] ?? [];
        }

        if (! is_array($middleware)) {
            $middleware = func_get_args();
        }

        foreach ($middleware as $index => $value) {
            $middleware[$index] = (string) $value;
        }

        $this->action['middleware'] = array_merge(
            $this->action['middleware'] ?? [],
            $middleware
        );

        return $this;
    }

    public function url($parameters = [])
    {
        $url = $this->uri;

        if (count($parameters) > 0) {
            foreach ($parameters as $key => $value) {
                $url = str_replace('{' . $key . '}', $value, $url);
            }
        }

        return $url;
    }
}