<?php
namespace Riyu\Foundation\Router;

class Redirect
{
    /**
     * @var \Riyu\Foundation\Application
     */
    protected $context;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @param \Riyu\Foundation\Application $context
     */
    public function __construct($context)
    {
        $this->context = $context;
    }

    /**
     * @param string $url
     * @param int $statusCode
     * @return \Riyu\Foundation\Router\Redirector
     */
    public function to($url, $statusCode = 302)
    {
        $this->url = $url;
        $this->statusCode = $statusCode;

        return new Redirector($this, $this->context);
    }

    /**
     * @param string $name
     * @param array $parameters
     * @return \Riyu\Foundation\Router\Redirector
     */
    public function route($name = null, $parameters = [])
    {
        $route = $this->context->get('routerCollection')->getRouteByName($name);
        $this->url = $route->url($parameters);
        $this->statusCode = 302;

        return new Redirector($this, $this->context);
    }

    /**
     * @return \Riyu\Foundation\Router\Redirector
     */
    public function back()
    {
        $request = $this->context->get('request');
        $session = $this->context->get('session');

        $this->url = $request->server('HTTP_REFERER') ? $request->server('HTTP_REFERER') : $session->previousUrl() ?? '/';
        $this->statusCode = 302;

        return new Redirector($this, $this->context);
    }

    /**
     * @return void
     */
    public function execute()
    {
        $response = $this->context->get('response');
        $response->code($this->statusCode ?? 302);
        $response->header('Location', $this->url);
        $response->send();
    }
}