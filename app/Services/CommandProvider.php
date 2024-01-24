<?php
namespace App\Services;

use Riyu\Foundation\Service\CommandProvider as ServiceCommandProvider;

class CommandProvider extends ServiceCommandProvider
{
    /**
     * Load the commands.
     *
     * @return array
     */
    protected function load()
    {
        return [
            'riyu' => \App\Command\RiyuCommand::class,
        ];
    }
}