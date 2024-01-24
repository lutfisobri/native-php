<?php
namespace Riyu\Foundation\Router;

class Redirector
{
    /**
     * @var \Riyu\Foundation\Router\Redirect
     */
    protected $redirect;

    /**
     * @var \Riyu\Foundation\Application
     */
    protected $context;

    // method with, withInput, withErrors, and onlyInput are not implemented yet
    public function __construct(Redirect $redirect, \Riyu\Foundation\Application $context)
    {
        $this->redirect = $redirect;
        $this->context = $context;
    }

    public function with($key, $value)
    {
        $session = $this->context->get('session');
        
        $session->set($key, $value);

        return $this;
    }

    public function execute()
    {
        $this->redirect->execute();
    }
}