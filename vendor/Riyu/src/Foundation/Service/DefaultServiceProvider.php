<?php

namespace Riyu\Foundation\Service;

class DefaultServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerCommands();
    }

    protected function registerCommands()
    {
        $this->context->singleton('command', function ($app) {
            return new CommandRegistar($app);
        });
    }

    public function boot()
    {
        $this->loadCommands();
    }

    protected function loadCommands()
    {
        $commands = [
            \Riyu\Commands\HelpCommand::class,
            \Riyu\Commands\ServeCommand::class,
        ];

        foreach ($commands as $command) {
            $this->context->make('command')->register($command);
        }
    }
}
