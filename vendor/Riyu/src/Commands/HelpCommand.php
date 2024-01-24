<?php
namespace Riyu\Commands;

use Riyu\Console\Command\Command;

class HelpCommand extends Command
{
    protected $name = 'help';

    protected $description = 'Show help';

    protected $aliases = ['h', '-h', '--help', ''];

    public function execute()
    {
        $commands = $this->context->make('command')->getCommands();

        $cmds = [];

        foreach ($commands as $command) {
            if (isset($cmds[$command->getName()])) {
                continue;
            }

            $cmds[$command->getName()] = $command;
        }

        $this->writeln('Riyu Framework');
        $this->writeln('==============');
        $this->writeln('');
        $this->writeln('Usage:');
        $this->writeln('  command [options] [arguments]');
        $this->writeln('');
        $this->writeln('Available commands:');
        foreach ($cmds as $command) {
            $this->writeln('  ' . $command->getName() . ' - ' . $command->getDescription());
        }
        $this->writeln('');
        $this->writeln('For more information about the commands, use:');
        $this->writeln('  command --help');
    }

    protected function help()
    {
        $this->execute();
    }
}