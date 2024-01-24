<?php

namespace Riyu\Console\Pipes;

abstract class Pipes implements PipesInterface
{
    public array $pipes = [];

    private $input;

    public function __construct($input)
    {
        $this->input = $input;
    }

    public function write()
    {
        if (!isset($this->pipes[0])) {
            return null;
        }

        $input = $this->input;

        
    }
}
