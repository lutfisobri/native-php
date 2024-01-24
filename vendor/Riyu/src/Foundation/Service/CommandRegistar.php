<?php
namespace Riyu\Foundation\Service;

class CommandRegistar
{
    protected $app;

    protected $commands = [];

    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Register the given command with the console application.
     *
     * @param  string|\Riyu\Console\IO\Input $command
     * @return void
     */
    public function register($command)
    {
        $command = $this->app->registerCommand($command);

        $this->commands[$command->getName()] = $command;

        foreach ($command->getAliases() as $alias) {
            $this->commands[$alias] = $command;
        }
    }

    public function getCommands()
    {
        $commands = [];

        foreach ($this->commands as $command) {
            if (empty($commands)) {
                $commands[] = $command;
                continue;
            } else {
                foreach ($commands as $currentCommand) {
                    if ($currentCommand->getName() == $command->getName()) {
                        continue;
                    } else {
                        $commands[] = $command;
                    }
                }
            }
        }

        return $commands;
    }

    public function run($input)
    {
        $command = $this->match($input);

        $command->setInput($input);

        $command->handle();
    }

    public function match($input)
    {
        $commands = $this->getCommands();

        foreach ($commands as $command) {
            if ($command->getName() == $input->getCommand()) {
                return $command;
            }

            foreach ($command->getAliases() as $alias) {
                if ($alias == $input->getCommand()) {
                    return $command;
                }
            }
        }

        return false;
    }
}