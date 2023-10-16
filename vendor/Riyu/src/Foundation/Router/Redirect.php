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
     * @return void
     */
    public function to($url, $statusCode = 302)
    {
        $this->url = $url;
        $this->statusCode = $statusCode;

        $this->context->instance('redirect', $this);

        return $this;
    }

    public function route($name = null, $parameters = [])
    {
        $this->url = $this->context->get('routerRegistar')->route($name, $parameters);
        $this->statusCode = 302;

        $this->context->instance('redirect', $this);

        return $this;
    }

    /**
     * @return void
     */
    public function execute()
    {
        $response = $this->context->get('response');
        $response->code($this->statusCode);
        $response->header('Location', $this->url);
        $response->send();
    }
}