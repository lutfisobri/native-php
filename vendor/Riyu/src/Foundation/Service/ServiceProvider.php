<?php
namespace Riyu\Foundation\Service;

use Riyu\Contract\Service\Service;

class ServiceProvider implements Service
{
    protected $context;

    public function __construct($context)
    {
        $this->context = $context;
        $this->register();
    }

    public function register()
    {
        throw new \Exception('You must override the register() method in the service provider.');
    }

    public function boot()
    {
        throw new \Exception('You must override the boot() method in the service provider.');
    }
}