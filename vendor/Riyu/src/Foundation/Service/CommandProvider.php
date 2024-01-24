<?php
namespace Riyu\Foundation\Service;

class CommandProvider extends ServiceProvider
{
    private $commands = [];

    final public function register()
    {
        $this->registerCommands();
    }

    final protected function registerCommands()
    {
        $app = $this->context;
        $this->context->singleton('command', function () use ($app) {
            return new CommandRegistar($app);
        });
    }

    final public function boot()
    {
        $this->commands = $this->load();
        
        $this->loadCommands();
    }

    final protected function loadCommands()
    {
        if (empty($this->commands)) {
            return;
        }
        
        foreach ($this->commands as $command) {
            $this->context->make('command')->register($command);
        }
    }

    /**
     * Load the commands.
     *
     * @return array
     */
    protected function load()
    {
        // throw new \Exception('You must override the load() method in the command provider.');
    }
}