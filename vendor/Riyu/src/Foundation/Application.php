<?php
namespace Riyu\Foundation;

use App\Services\RouteProvider;
use Riyu\Contract\Application as ApplicationContract;
use Riyu\Foundation\Router\Router;
use Riyu\Http\Request;
use Riyu\Http\Route;

/**0
 * Context class.
 */
class Application implements ApplicationContract
{
    protected static $instance;

    protected $basePath;

    protected $container;

    protected $serviceProviders = [];

    public function __construct($basePath = null)
    {
        $this->setBasePath($basePath);
        $this->container = Container::getInstance();
        $this->registerBaseBindings();
        $this->registerBaseServiceProviders();
    }

    /**
     * Get the application instance.
     * 
     * @param string|null $basePath
     * @return \Riyu\Foundation\Application
     */
    public static function getInstance($basePath = null)
    {
        if (is_null(static::$instance)) {
            static::$instance = new static($basePath);
        }

        return static::$instance;
    }

    public function run()
    {
        $this->container->get('router')->dispatch();
    }

    public function bind($abstract, $concrete)
    {
        $this->container->bind($abstract, $concrete);
    }

    public function make($abstract)
    {
        return $this->container->make($abstract);
    }

    public function singleton($abstract, $concrete)
    {
        $this->container->singleton($abstract, $concrete);
    }

    public function instance($abstract, $instance)
    {
        $this->container->instance($abstract, $instance);
    }

    public function get($abstract)
    {
        return $this->container->get($abstract);
    }

    public function has($abstract)
    {
        return $this->container->has($abstract);
    }

    public function setBasePath($basePath)
    {
        $this->basePath = rtrim($basePath, '\/');
    }

    public function getBasePath()
    {
        return $this->basePath;
    }

    public function resolve($abstract, $parameters = [])
    {
        return $this->container->resolve($abstract, $parameters);
    }

    public function solve($abstract, $parameters = [])
    {
        return $this->container->solve($abstract, $parameters);
    }

    public function register($provider)
    {
        $this->serviceProviders[] = $provider;
    }

    protected function registerBaseBindings()
    {
        $this->container->instance('app', $this);
        $this->container->instance('router', new Router($this));
        $this->container->instance('route', new Route($this));
        $this->container->instance('request', new Request($this));
        // $this->container->instance('response', new Response());
        // $this->container->instance('view', new View($this));
    }

    protected function registerBaseServiceProviders()
    {
        $this->register(new RouteProvider($this));
        // $this->register(new ViewServiceProvider($this));
    }
}