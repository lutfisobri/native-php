<?php
namespace Riyu\Console\IO;

interface OutputInterface
{
    /**
     * Write a message to the output.
     * 
     * @param string $message
     * @param bool $newLine
     * @return void
     */
    public function write(string $message, bool $newLine = false);

    /**
     * Write a message to the output with a new line.
     * 
     * @param string $message
     * @return void
     */
    public function writeLine(string $message);

    /**
     * Write a message to the output with a new line.
     * 
     * @param string $message
     * @return void
     */
    public function writeln(string $message);
}