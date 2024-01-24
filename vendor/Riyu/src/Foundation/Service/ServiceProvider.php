<?php
namespace Riyu\Foundation\Service;

use Riyu\Contract\Service\Service;

abstract class ServiceProvider implements Service
{
    /**
     * @var \Riyu\Foundation\Application
     */
    protected $context;

    public function __construct(\Riyu\Foundation\Application $context)
    {
        $this->context = $context;
    }

    public function register()
    {
        throw new \Exception('You must override the register() method in the service provider.');
    }

    public function boot()
    {
        //
    }
}