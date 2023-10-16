<?php
namespace Riyu\Http;

use Riyu\Foundation\Http\Request as HttpRequest;

class Request extends HttpRequest
{
    protected $server;

    protected static $context;

    protected $parameters = [];

    public function __construct($context = null)
    {
        $this->server = new Server();
        
        if (!is_null($context)) {
            self::$context = $context;
        }

        parent::__construct();
    }

    public function getMethod()
    {
        return $this->server->get('REQUEST_METHOD');
    }

    public function getPathInfo()
    {
        return $this->server->get('PATH_INFO');
    }

    public function getQueryString()
    {
        return $this->server->get('QUERY_STRING');
    }

    public function getUri()
    {
        return $this->server->get('REQUEST_URI');
    }

    public function getHost()
    {
        return $this->server->get('HTTP_HOST');
    }

    public function getPort()
    {
        return $this->server->get('SERVER_PORT');
    }

    public function getProtocol()
    {
        return $this->server->get('SERVER_PROTOCOL');
    }

    public function getParameters()
    {
        return $this->parameters;
    }
    
    public function getParameter($key)
    {
        if (isset($this->parameters[$key])) {
            return $this->parameters[$key];
        }

        if ($this->has($key)) {
            return $this->get($key);
        }
        
        return null;
    }

    public function setParameters($parameters)
    {
        foreach ($parameters as $key => $value) {
            if (is_numeric($key)) {
                continue;
            }
            $this->set($key, $value);
            $this->parameters[$key] = $value;
            $this->$key = $value;
        }
    }

    public function setParameter($key, $value)
    {
        $this->parameters[$key] = $value;

        $this->set($key, $value);
    }

    public function hasParameter($key)
    {
        if (isset($this->parameters[$key])) {
            return true;
        }

        return $this->has($key);
    }
}