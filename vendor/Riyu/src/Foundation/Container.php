<?php
namespace Riyu\Foundation;

use Riyu\Contract\Container as ContractContainer;

class Container implements ContractContainer
{
    protected $bindings = [];

    protected $instances = [];

    protected static $instance;

    public function __construct()
    {
        static::$instance = $this;
    }

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function bind($abstract, $concrete)
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function make($abstract)
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        foreach ($this->instances as $instance) {
            if ($instance instanceof $abstract) {
                return $instance;
            }
        }

        if (isset($this->bindings[$abstract])) {
            $concrete = $this->bindings[$abstract];
        } else {
            $concrete = $abstract;
        }

        $object = $this->resolve($concrete);

        if (isset($this->bindings[$abstract])) {
            $this->instances[$abstract] = $object;
        }

        return $object;
    }

    public function singleton($abstract, $concrete)
    {
        $this->bind($abstract, $concrete);
    }

    public function instance($abstract, $instance)
    {
        $this->instances[$abstract] = $instance;
    }

    public function get($abstract)
    {
        return $this->make($abstract);
    }

    public function has($abstract)
    {
        return isset($this->bindings[$abstract]) || isset($this->instances[$abstract]);
    }

    public function resolve($abstract, $parameters = [])
    {
        if ($abstract instanceof \Closure) {
            return $abstract($this, $parameters);
        }

        $reflector = new \ReflectionClass($abstract);

        if (!$reflector->isInstantiable()) {
            throw new \Exception("Class {$abstract} is not instantiable");
        }

        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            return new $abstract;
        }

        $dependencies = $constructor->getParameters();

        $parameters = $this->keyParametersByArgument(
            $dependencies,
            $parameters
        );

        $instances = $this->getDependencies(
            $dependencies,
            $parameters
        );

        return $reflector->newInstanceArgs($instances);
    }

    public function solve($abstract, $parameters = [])
    {
        try {
            return $this->get($abstract);
        } catch (\Exception $e) {
            return $this->resolve($abstract, $parameters);
        }
    }

    protected function keyParametersByArgument(array $dependencies, array $parameters)
    {
        foreach ($parameters as $key => $value) {
            if (is_numeric($key)) {
                unset($parameters[$key]);

                $parameters[$dependencies[$key]->name] = $value;
            }

            if (is_string($key) && !is_numeric($key)) {
                unset($parameters[$key]);

                $parameters[$key] = $value;
            }
        }

        return $parameters;
    }

    public function getDependencies($dependencies, $parameters = [])
    {
        $results = [];

        foreach ($dependencies as $dependency) {
            $results[] = $this->resolveDependency($dependency, $parameters);
        }

        return $results;
    }

    public function resolveDependency(\ReflectionParameter $dependency, $parameters = [])
    {
        if (isset($parameters[$dependency->name])) {
            return $parameters[$dependency->name];
        }

        if ($dependency->getType()) {
            return $this->make($dependency->getType()->getName());
        }

        if ($dependency->isDefaultValueAvailable()) {
            return $dependency->getDefaultValue();
        }

        throw new \Exception("Can not resolve dependency {$dependency->name}");
    }
}