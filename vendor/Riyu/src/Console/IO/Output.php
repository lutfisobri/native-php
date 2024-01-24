<?php

namespace Riyu\Console\IO;

class Output implements OutputInterface
{
    protected $styles = [
        'bold' => '1',
        'dim' => '2',
        'underlined' => '4',
        'blink' => '5',
        'reverse' => '7',
        'hidden' => '8',
        'black' => '30',
        'red' => '31',
        'green' => '32',
        'yellow' => '33',
        'blue' => '34',
        'magenta' => '35',
        'cyan' => '36',
        'white' => '37',
        'bg-black' => '40',
        'bg-red' => '41',
        'bg-green' => '42',
        'bg-yellow' => '43',
        'bg-blue' => '44',
        'bg-magenta' => '45',
        'bg-cyan' => '46',
        'bg-white' => '47',
    ];

    /**
     * Write a message to the output.
     * 
     * @param string $message
     * @param bool $newLine
     * @return void
     */
    public function write(string $message, bool $newLine = false)
    {
        $message = $this->applyStyledText($message);

        if ($newLine) {
            echo '  ' . $message . PHP_EOL;
        } else {
            echo '  ' . $message;
        }
    }

    /**
     * Write a message to the output with a new line.
     * 
     * @param string $message
     * @return void
     */
    public function writeLine(string $message)
    {
        $this->write($message, true);
    }

    /**
     * Write a message to the output with a new line.
     * 
     * @param string $message
     * @return void
     */
    public function writeln(string $message)
    {
        $this->write($message, true);
    }

    public function applyStyledText($input)
    {
        preg_match_all('/<(.*?)>(.*?)<\/>/', $input, $matches, PREG_SET_ORDER);

        $result = $input;

        foreach ($matches as $match) {
            $tagContent = $match[1];
            $options = explode(';', $tagContent);
            $text = $match[2];

            $result = str_replace($match[0], $this->applyStyle($text, $options), $result);
        }

        return $result;
    }

    public function applyStyle($text, $options = [])
    {
        $styleCodes = [];

        foreach ($options as $option) {
            $optionParts = explode('=', $option);
            $styleType = $optionParts[0];
            $color = $optionParts[1] ?? '';

            if ($styleType === 'fg') {
                if (array_key_exists($color, $this->styles)) {
                    $styleCodes[] = $this->styles[$color];
                }
            } elseif ($styleType === 'bg') {
                if (array_key_exists('bg-' . $color, $this->styles)) {
                    $styleCodes[] = $this->styles['bg-' . $color];
                }
            } elseif (array_key_exists($styleType, $this->styles)) {
                $styleCodes[] = $this->styles[$styleType];
            }
        }

        $styleString = implode(';', $styleCodes);

        return "\033[{$styleString}m{$text}\033[0m";
    }

    public function style(string $message, string $style)
    {
        $styles = [
            'bold' => '1',
            'dim' => '2',
            'underlined' => '4',
            'blink' => '5',
            'reverse' => '7',
            'hidden' => '8',
            'black' => '30',
            'red' => '31',
            'green' => '32',
            'yellow' => '33',
            'blue' => '34',
            'magenta' => '35',
            'cyan' => '36',
            'white' => '37',
            'bg-black' => '40',
            'bg-red' => '41',
            'bg-green' => '42',
            'bg-yellow' => '43',
            'bg-blue' => '44',
            'bg-magenta' => '45',
            'bg-cyan' => '46',
            'bg-white' => '47',
        ];

        if (array_key_exists($style, $styles)) {
            return "\033[" . $styles[$style] . "m" . $message . "\033[0m";
        }

        throw new \Exception('Style [' . $style . '] does not exist.');
    }

    public function warn(string $message)
    {
        $this->writeln('');
        $this->write('<bg=yellow> WARN </>');
        $this->writeln($message);
    }

    public function error(string $message)
    {
        $this->writeln('');
        $this->write('<bg=red> ERROR </>');
        $this->writeln($message);
    }

    public function info(string $message)
    {
        $this->writeln('');
        $this->write('<bg=blue> INFO </>');
        $this->writeln($message);
    }
}
