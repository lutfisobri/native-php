<?php
namespace Riyu\Console\Command;

use ReflectionClass;
use Riyu\Console\Command\Attribute\AsCmd;

class kernel
{
    /**
     * The input instance.
     * 
     * @var \Riyu\Console\IO\Input
     */
    protected $input;

    /**
     * The console instance.
     * 
     * @var \Riyu\Console\Console
     */
    protected $console;

    public function handle($input, $console)
    {
        $this->input = $input;
        $this->console = $console;

        $this->run();
    }

    public function terminate()
    {
        $this->input = null;
        $this->console = null;
    }

    private function run()
    {
    }
}