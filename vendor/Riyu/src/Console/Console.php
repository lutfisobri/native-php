<?php
namespace Riyu\Console;

use Riyu\Console\Command\Command;
use Riyu\Console\IO\Input;
use Riyu\Console\IO\Output;

class Console
{
    /**
     * The console application version.
     * 
     * @var string
     */
    const VERSION = '0.0.1';

    /**
     * The console application name.
     * 
     * @var string
     */
    const NAME = 'Riyu Console';

    /**
     * The console application description.
     * 
     * @var string
     */
    const DESCRIPTION = 'Riyu Console Application';

    /**
     * The console application author.
     * 
     * @var string
     */
    const AUTHOR = 'Riyu';

    public const COLOR = [
        'black' => '0;30',
        'dark_gray' => '1;30',
        'blue' => '0;34',
        'light_blue' => '1;34',
        'green' => '0;32',
        'light_green' => '1;32',
        'cyan' => '0;36',
        'light_cyan' => '1;36',
        'red' => '0;31',
        'light_red' => '1;31',
        'purple' => '0;35',
        'light_purple' => '1;35',
        'brown' => '0;33',
        'yellow' => '1;33',
        'light_gray' => '0;37',
        'white' => '1;37',
    ];

    /**
     * Get the width of the terminal.
     * 
     * @return int
     */
    public static function getWidth()
    {
        return exec('tput cols') - 2;
    }

    /**
     * Get the height of the terminal.
     * 
     * @return int
     */
    public static function getHeight()
    {
        return exec('tput lines');
    }
}