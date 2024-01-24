<?php
namespace Riyu\Foundation;

use App\Middleware\Authenticable;
use Riyu\Contract\Application as ApplicationContract;
use Riyu\Foundation\Database\Connection\Connection;
use Riyu\Foundation\Router\Redirect;
use Riyu\Foundation\Router\Router;
use Riyu\Foundation\Service\DefaultServiceProvider;
use Riyu\Http\Request;
use Riyu\Http\Response;
use Riyu\Http\Route;
use Riyu\Session\Store;
use Riyu\View\View;
use Riyu\View\Widget\Widget;

/**
 * Context class.
 */
class Application implements ApplicationContract
{
    protected static $instance;

    protected $basePath;

    /**
     * @var \Riyu\Foundation\Container
     */
    protected $container;

    protected $serviceProviders = [];

    protected $env;

    public function __construct($basePath = null)
    {
        if (!is_null($basePath)) {
            $this->setBasePath($basePath);
        }

        $this->container = Container::getInstance();
        
        static::$instance = $this;
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

    public function boot()
    {
        $this->registerBaseBindings();
        $this->registerBaseServiceProviders();
        $this->registerServiceProviders();
    }

    public function run()
    {
        $this->boot();
        $this->container->get('router')->dispatch();
    }

    public function console($input)
    {
        $this->boot();
        $this->container->get('command')->run($input);
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
        if ($basePath != null) {
            $this->basePath = rtrim($basePath, '\/');
        }
    }

    public function getBasePath()
    {
        return $this->basePath;
    }

    // public function basePath($path = '')
    // {
    //     return $this->basePath . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    // }

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
        foreach ($this->defaultBindings() as $key => $value) {
            $this->container->instance($key, $value);
        }
    }

    protected function registerConfigApp()
    {
        $path = $this->basePath;
        $configFile = $path . '/config/app.php';

        if (!file_exists($configFile)) {
            throw new \Exception('Config file not found.');
        }

        $config = require $configFile;
        
        foreach ($config['providers'] as $provider) {
            $this->register(new $provider($this));
        }

        foreach ($config['aliases'] as $alias => $class) {
            $this->container->instance($alias, new $class);
        }

        $envFile = $path . '/env.php';

        if (file_exists($envFile)) {
            $this->env = require $envFile;
        }
    }

    protected function registerBaseServiceProviders()
    {
        $this->registerConfigApp();        
        $this->register(new DefaultServiceProvider($this));
        // $this->register(new ViewServiceProvider($this));
    }

    protected function registerServiceProviders()
    {
        foreach ($this->serviceProviders as $provider) {
            $provider->register();
            $provider->boot();
        }
    }

    public function registerCommand($command)
    {
        return new $command($this);
    }

    public function defaultBindings()
    {
        return [
            'app' => $this,
            'router' => new Router($this),
            'route' => new Route($this),
            'request' => new Request($this),
            'response' => new Response(),
            'view' => new View($this),
            'widget' => new Widget($this),
            'redirect' => new Redirect($this),
            'session' => new Store($this),
            'db.connection' => new Connection($this),
        ];
    }

    public function getEnv($key)
    {
        return $this->env[$key];
    }

    public function setEnv($key, $value)
    {
        $this->env[$key] = $value;
    }

    public function env()
    {
        return $this->env;
    }
}