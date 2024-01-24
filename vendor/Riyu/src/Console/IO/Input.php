<?php
namespace Riyu\Console\IO;

/**
 * Input class for console.
 */
class Input
{
    /**
     * @var array
     */
    protected $argv = [];

    /**
     * @var array
     */
    protected $options = [];
    
    public function __construct()
    {
        $this->argv = $_SERVER['argv'];

        $this->options = $this->getOptions();
    }

    /**
     * Get the arguments.
     *
     * @return array
     */
    public function getArguments()
    {
        $arguments = [];
        foreach ($this->argv as $argument) {
            if (substr($argument, 0, 2) !== '--' && substr($argument, 0, 1) !== '-') {
                $arguments[] = $argument;
            }
        }

        return $arguments;
    }

    /**
     * Get the options.
     *
     * @return array
     */
    public function getOptions()
    {
        $options = [];
        foreach ($this->argv as $argument) {
            if (substr($argument, 0, 2) === '--') {
                $option = explode('=', $argument);
                $options[substr($option[0], 2)] = $option[1] ?? true;
            }

            if (substr($argument, 0, 1) === '-') {
                $option = explode('=', $argument);
                $options[substr($option[0], 1)] = $option[1] ?? true;
            }
        }

        return $options;
    }

    /**
     * Get the command.
     *
     * @return string
     */
    public function getCommand()
    {
        return $this->argv[1] ?? null;
    }

    /**
     * Get the option.
     *
     * @param string $option
     * @return string|null
     */
    public function getOption($key)
    {
        $options = $this->getOptions();
        if (isset($options[$key])) {
            return $options[$key];
        }

        return null;
    }

    public function getCommands()
    {
        $commands = [];

        foreach ($this->argv as $argument) {
            if (substr($argument, 0, 2) !== '--' && substr($argument, 0, 1) !== '-') {
                $commands[] = $argument;
            }
        }

        return $commands;
    }

    public function setCommand($command)
    {
        $this->argv[1] = $command;
    }

    public function setArgument($key, $value)
    {
        $this->argv[$key] = $value;
    }

    public function read()
    {
        return trim(fgets(STDIN));
    }

    public function clear()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            system('cls');
        } else {
            system('clear');
        }
    }
}