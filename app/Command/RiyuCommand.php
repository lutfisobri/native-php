<?php

namespace App\Command;

use Riyu\Console\Command\Command;

class RiyuCommand extends Command
{
    protected $name = 'riyu';

    protected $description = 'Riyu command';

    // public function execute()
    // {
    //     $line = 'Kateru Riyu';
    //     $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=[]{};:,./<>?\'"\\';
    //     // add space to alphabet
    //     $alphabet .= ' ';
    //     $alphabet .= '\t';
    //     $alphabet .= '\n';
    //     $alphabet .= '\r';
    //     $alphabet .= PHP_EOL;

    //     $match = '';
    //     while (true) {
    //         for ($i = 0; $i < strlen($alphabet); $i++) {
    //             $this->write("\r" . $match . $alphabet[$i]);
    //             if ($line[strlen($match)] == $alphabet[$i]) {
    //                 $match .= $alphabet[$i];
    //                 $this->write($alphabet[$i]);
    //                 usleep(100000);
    //                 break;
    //             }

    //             usleep(100000);
    //         }
    //     }
    // }

    public function execute()
    {
        $line = 'Kateru Riyu';
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=[]{};:,./<>?\'"';

        // Add special characters to the alphabet
        $alphabet .= ' ';
        $alphabet .= "\t"; // Tab
        $alphabet .= "\n"; // Newline
        $alphabet .= "\r"; // Carriage return
        $alphabet .= PHP_EOL; // Platform-specific end of line

        $match = '';

        while (strlen($match) < strlen($line)) {
            for ($i = 0; $i < strlen($alphabet); $i++) {
                if ($line == $match) {
                    break;
                }
                $this->write("\r" . $match . $alphabet[$i]);

                if ($line[strlen($match)] == $alphabet[$i]) {
                    $match .= $alphabet[$i];
                    $this->write($alphabet[$i]);
                    usleep(10000);
                    break;
                }

                usleep(10000);
            }
        }
    }


    public function options(): bool
    {
        if ($this->input->getOption('-h')) {
            $this->help();
            return false;
        }

        return true;
    }

    public function help()
    {
        $this->writeln('Riyu command help');
    }
}
