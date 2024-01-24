<?php
namespace Riyu\Http;

use Riyu\Foundation\Http\Request as HttpRequest;

class Request extends HttpRequest
{
    protected $server;

    protected static $context;

    protected $parameters = [];

    protected $route;

    protected $session;

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
        if ($this->hasParameter('_method')) {
            return $this->getParameter('_method');
        }

        return $this->server->get('REQUEST_METHOD');
    }

    public function server($key = null)
    {
        if (is_null($key)) {
            return $this->server;
        }

        return $this->server->get($key);
    }

    public function getPathInfo()
    {
        if (null === ($requestUri = $this->getUri())) {
            return '/';
        }

        // Remove the query string from REQUEST_URI
        if (false !== $pos = strpos($requestUri, '?')) {
            $requestUri = substr($requestUri, 0, $pos);
        }
        if ('' !== $requestUri && '/' !== $requestUri[0]) {
            $requestUri = '/'.$requestUri;
        }

        if (null === ($baseUrl = $this->getBaseUrlReal())) {
            return $requestUri;
        }

        $pathInfo = substr($requestUri, \strlen($baseUrl));
        if (false === $pathInfo || '' === $pathInfo) {
            // If substr() returns false then PATH_INFO is set to an empty string
            return '/';
        }

        return $pathInfo;
    }

    public function getBaseUrlReal()
    {
        $filename = basename($this->server->get('SCRIPT_FILENAME'));

        if (basename($this->server->get('SCRIPT_NAME')) === $filename) {
            $baseUrl = $this->server->get('SCRIPT_NAME');
        } elseif (basename($this->server->get('PHP_SELF')) === $filename) {
            $baseUrl = $this->server->get('PHP_SELF');
        } elseif ($this->server->get('ORIG_SCRIPT_NAME')) {
            if (basename($this->server->get('ORIG_SCRIPT_NAME')) === $filename) {
                $baseUrl = $this->server->get('ORIG_SCRIPT_NAME');
            }
        } else {
            // Backtrack up the SCRIPT_FILENAME to find the portion
            // matching PHP_SELF.
            $path = $this->server->get('PHP_SELF', '');
            $file = $this->server->get('SCRIPT_FILENAME', '');
            $segs = explode('/', trim($file, '/'));
            $segs = array_reverse($segs);
            $index = 0;
            $last = count($segs);
            $baseUrl = '';
            do {
                $seg = $segs[$index];
                $baseUrl = '/'.$seg.$baseUrl;
                ++$index;
            } while ($last > $index && (false !== $pos = strpos($path, $baseUrl)) && 0 != $pos);
        }

        // Does the baseUrl have anything in common with the request_uri?
        $requestUri = $this->getUri();

        if (0 === strpos($requestUri, $baseUrl)) {
            // full $baseUrl matches
            return $baseUrl;
        }

        if (0 === strpos($requestUri, \dirname($baseUrl))) {
            // directory portion of $baseUrl matches
            return \dirname($baseUrl);
        }

        $truncatedRequestUri = $requestUri;
        if (false !== ($pos = strpos($requestUri, '?'))) {
            $truncatedRequestUri = substr($requestUri, 0, $pos);
        }

        $basename = basename($baseUrl);
        if (empty($basename) || !strpos($truncatedRequestUri, $basename)) {
            // no match whatsoever; set it blank
            return '';
        }

        // If using mod_rewrite or ISAPI_Rewrite strip the script filename
        // out of baseUrl. $pos !== 0 makes sure it is not matching a value
        // from PATH_INFO or QUERY_STRING
        if (\strlen($requestUri) >= \strlen($baseUrl) && (false !== ($pos = strpos($requestUri, $baseUrl)) && $pos !== 0)) {
            $baseUrl = substr($requestUri, 0, $pos + \strlen($baseUrl));
        }

        return rtrim($baseUrl, '/');
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

    /**
     * Set the route object.
     * 
     * @param \Riyu\Foundation\Router\Route $route
     * @return void
     */
    public function route(\Riyu\Foundation\Router\Route $route = null)
    {
        if (!is_null($route)) {
            $this->route = $route;
        }

        return $this->route;
    }
}