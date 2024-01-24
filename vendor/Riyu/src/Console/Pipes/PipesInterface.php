<?php
namespace Riyu\Console\Pipes;

interface PipesInterface
{
    public function readAndWrite(): array;
}