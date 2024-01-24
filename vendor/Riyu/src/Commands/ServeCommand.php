<?php

namespace Riyu\Commands;

use Riyu\Console\Command\Command;
use Riyu\Console\Console;
use Riyu\Foundation\Process\PhpExecutableFinder;
use Riyu\Foundation\Process\Process;

class ServeCommand extends Command
{
    protected $name = 'serve';

    protected $description = 'Serve the application on the PHP development server';

    protected $port = 8000;

    protected $process;

    protected $isincrement = true;

    protected $watchedFiles = [];

    public function execute()
    {
        $this->serveCommand();
    }

    public function options(): bool
    {
        if ($this->input->getOption('port') | $this->input->getOption('p')) {
            $this->port = $this->input->getOption('port') ?? $this->input->getOption('p');
            $this->isincrement = false;
        }
        
        return true;
    }

    protected function help()
    {
        $this->writeln('Usage:');
        $this->writeln('  serve [options]');
        $this->writeln('');
        $this->writeln('Options:');
        $this->writeln('  -h, --help  Show help');
        $this->writeln('  -p, --port  Specify the port number');
    }

    protected function serveCommand()
    {
        $vendorDir = dirname(__FILE__);
        $baseDir = $vendorDir . '/../resources/server.php';

        $php = (new PhpExecutableFinder)->find(false);
        $command = [
            $php,
            '-S',
            '127.0.0.1:' . $this->port,
            // '-f',
            $baseDir,
        ];

        $process = new Process($command);
        $this->process = $process;

        $process->run($this->handleOutput());

        $isRunning = $process->isRunning();

        sleep(1);

        if (!$isRunning && $this->isincrement) {
            $this->writeln('Port ' . $this->port . ' is already in use.');
            $this->port += 1;

            $process->close();

            return $this->handle();
        }

        // $this->watchFiles();
        while (true) {
            // $this->checkFiles();
            sleep(1);
        }
    }

    private function getAllFiles($dir)
    {
        $files = [];

        foreach (scandir($dir) as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }

            if (strpos($file, '.git') !== false) {
                continue;
            }

            $path = $dir . '/' . $file;

            if (is_dir($path)) {
                $files = array_merge($files, $this->getAllFiles($path));
            } else {
                $files[] = $path;
            }
        }

        return $files;
    }

    private function watchFiles()
    {
        $path = getcwd();

        $files = $this->getAllFiles($path);

        $this->writeln('Watching files...');

        foreach ($files as $file) {
            $this->watchedFiles[$file] = filemtime($file);
        }

        $this->writeln('Press Ctrl+C to quit.');

        while (true) {
            // $this->checkFiles();
            foreach ($this->watchedFiles as $file => $lastMtime) {
                if (!file_exists($file)) {
                    unset($this->watchedFiles[$file]);
                    continue;
                }

                clearstatcache(true, $file);
                $mtime = filemtime($file);
    
                if ($mtime > $lastMtime) {
                    $this->writeln('File changed: ' . $file);
                    $this->writeln('Restarting server...');
    
                    $this->process->close();
    
                    $this->watchedFiles[$file] = $mtime;
    
                    $this->writeln('restarting...');
                    $this->writeln('');
    
                    usleep(100000);
    
                    return $this->serveCommand();
                }
            }
            sleep(1);
        }

        return $this->serveCommand();
    }

    private function checkFiles()
    {
        foreach ($this->watchedFiles as $file => $lastMtime) {
            if (!file_exists($file)) {
                unset($this->watchedFiles[$file]);
                continue;
            }

            clearstatcache(true, $file);
            $mtime = filemtime($file);

            if ($mtime > $lastMtime) {
                $this->writeln('File changed: ' . $file);
                $this->writeln('Restarting server...');

                $this->process->terminate();

                $this->watchedFiles[$file] = $mtime;

                $this->writeln('restarting...');
                header('Refresh:0');

                usleep(100000);

                $this->handle(true);
            }
        }
    }

    private function handleOutput()
    {
        return function ($type, $buffer) {
            // $this->writeln($buffer);
            if ($buffer != '') {
                $position = strpos($buffer, '] ');

                $buffer = substr($buffer, $position + 2);
                $buffer = str_replace("\n", ' ', $buffer);

                $date = $this->getCurrentTime();
                $maxWidth = Console::getWidth();

                if (strpos($buffer, 'Development Server') !== false) {
                    $this->info('Starting server on <bold;underline>http://localhost:' . $this->port . '</>');
                    $this->writeln('');
                    $this->writeln('<fg=yellow;bold>Press Ctrl+C to quit.</>');
                    $this->writeln('');
                } else if (strpos($buffer, ' Clossing') !== false || strpos($buffer, ' Accepted') !== false || strpos($buffer, ' Closing') !== false) {
                } else if (strpos($buffer, 'Closed without sending a request')) {
                    // ...
                } else if (strpos($buffer, 'Address already in use')) {
                    if ($this->isincrement) {
                        $this->warn('Port ' . $this->port . ' is already in use.');
                    } else {
                        $this->warn('Port ' . $this->port . ' is already in use.');

                        $this->writeln('Please specify the port number with the ' . $this->style('-p', 'yellow') . ' or ' . $this->style('--port', 'yellow') . ' option.');
                        $this->writeln('Example:');
                        $this->writeln('  serve --port=8001');
                        $this->writeln('  serve -p 8001');
                        $this->writeln('');
                    }
                } else if (strpos($buffer, 'PHP Warning:')) {
                    $this->warn($buffer);
                } else if (strpos($buffer, 'PHP Fatal error: ' !== false)) {
                    $this->error($buffer);
                    // $line = explode(':', $buffer);
                    // $line = explode(' in ', $line[2]);

                    // while (substr($line[0], 0, 1) == ' ') {
                    //     $line[0] = substr($line[0], 1);
                    // }

                    // $this->error($line[0]);
                    // $this->writeln('');
                } else if (strpos($buffer, '[200]: ')) {
                    $index = strpos($buffer, '[');
                    $buffer = substr($buffer, $index + 1);
                    $index = strpos($buffer, ']');
                    $statusCode = substr($buffer, 0, $index);
                    $index = strpos($buffer, ':');
                    $requestMethod = substr($buffer, $index + 2);
                    $index = strpos($requestMethod, ' ');
                    $requestUri = substr($requestMethod, $index + 1);

                    $dots = $maxWidth - strlen($requestUri) - strlen($date) - 3;
                    $dots = str_repeat('.', $dots);

                    $this->writeln('<fg=cyan;bold>' . $date . '</> <fg=white>' . $requestUri . '</>' . $dots);
                } else {
                    $msg = '<fg=cyan;bold>' . $date . '</> <fg=yellow>' . $buffer . '</>';

                    $this->writeln($msg);
                }
            }
        };
    }

    private function getCurrentTime()
    {
        return date('Y-m-d H:i:s');
    }
}
