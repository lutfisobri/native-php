<?php

namespace Riyu\Console\Command;

use Riyu\Console\IO\Input;
use Riyu\Console\IO\IO;
use Riyu\Console\IO\Output;
use Riyu\Foundation\Service\ServiceProvider;

/**
 * Command class.
 * 
 * Automatically called by the console.
 * Automatically called help if the command has --help
 * 
 * @package Riyu\Console\Command
 */
class Command extends ServiceProvider
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name;

    /**
     * The console command aliases.
     *
     * @var array
     */
    protected $aliases = [];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description;

    protected $arguments = [];

    protected $options = [];

    protected $input = null;

    public function handle()
    {
        if ($this->input->getOption('help')) {
            $this->help();
            return;
        }

        $status = $this->options();

        if ($status) {
            $this->execute();
        }
    }

    /**
     * Configure the command.
     * 
     * ex: option (--help, -h or -v)
     * 
     * @return bool true if the command can be executed, false otherwise
     */
    public function options(): bool
    {
        return true;
    }

    public function execute()
    {
        throw new \Exception('Command not implemented.');
    }

    protected function help()
    {
        throw new \Exception('Help not implemented.');
    }

    /**
     * Get the command name.
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /** 
     * Get the command aliases.
     * 
     * @return array
     */
    public function getAliases()
    {
        return $this->aliases;
    }

    /**
     * Get the command description.
     * 
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get the command arguments.
     * 
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Get the command options.
     * 
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Run the command.
     * 
     * @return void
     */
    public function run()
    {
        $this->execute();
    }

    public function setInput(Input $input)
    {
        $this->input = $input;
    }

    public function __call($name, $arguments)
    {
        if (method_exists(new Output, $name)) {
            return call_user_func_array([new Output, $name], $arguments);
        }

        throw new \Exception('Method [' . $name . '] does not exist.');
    }
    
    public static function __callStatic($name, $arguments)
    {
        if (method_exists(new Output, $name)) {
            return call_user_func_array([new Output, $name], $arguments);
        }

        throw new \Exception('Method [' . $name . '] does not exist.');
    }
}