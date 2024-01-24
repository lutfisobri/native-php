<?php

namespace Riyu\Foundation\Process;

class Process
{
    public const IN = 'in';
    public const OUT = 'out';
    public const ERR = 'err';

    protected $commands;

    protected $process;

    protected $pipes;

    protected $callback;

    public $exitCode;

    protected $path;

    protected $watchedFiles = [];

    protected $fileMtimes = [];

    public function __construct(string|array $commands)
    {
        $this->commands = $commands;
    }

    private function escapeArgument(?string $argument): string
    {
        if ('' === $argument || null === $argument) {
            return '""';
        }
        if ('\\' !== \DIRECTORY_SEPARATOR) {
            return "'" . str_replace("'", "'\\''", $argument) . "'";
        }
        if (str_contains($argument, "\0")) {
            $argument = str_replace("\0", '?', $argument);
        }
        if (!preg_match('/[\/()%!^"<>&|\s]/', $argument)) {
            return $argument;
        }
        $argument = preg_replace('/(\\\\+)$/', '$1$1', $argument);

        return '"' . str_replace(['"', '^', '%', '!', "\n"], ['""', '"^^"', '"^%"', '"^!"', '!LF!'], $argument) . '"';
    }

    /**
     * Run the process.
     * 
     * @param callable $callback callback($type, $buffer) {}
     * 
     */
    public function run(callable $callback = null)
    {
        if (is_array($command = $this->commands)) {
            $command = implode(' ', array_map($this->escapeArgument(...), $this->commands));

            if ('\\' !== \DIRECTORY_SEPARATOR) {
                $command = 'exec ' . $command;
            }
        } else {
            $command = $this->commands;
        }

        dd($command);
        $process = @proc_open($command, [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w']
        ], $this->pipes);


        if (!is_resource($process)) {
            print_r('Unable to execute the command.');
            die();
        }


        $this->process = $process;
    }

    private function readAndWriteWindows()
    {
    }

    private function readAndWrite()
    {
        // if ($this->getOS() == 'windows') {
        //     $this->readAndWriteWindows();
        //     return;
        // }

        do {
            $read = array($this->pipes[1], $this->pipes[2]);
            $write = null;
            $except = null;

            if (stream_select($read, $write, $except, 5)) {
                foreach ($read as $c) {
                    if (feof($c)) {
                        continue;
                    }
                    $read = fread($c, 1024);

                    if ($read === false) {
                        continue;
                    }

                    $callback = $this->callback;

                    $type = $c == $this->pipes[1] ? 'out' : 'err';

                    if (is_callable($callback)) {
                        $callback($type, $read);
                    }
                }
            }
        } while (!feof($this->pipes[1]) | !feof($this->pipes[2]));

        fclose($this->pipes[0]);
        fclose($this->pipes[1]);
        fclose($this->pipes[2]);
    }

    private function getOS()
    {
        $os = strtolower(PHP_OS);

        if (strpos($os, 'darwin') !== false) {
            return 'mac';
        } elseif (strpos($os, 'win') !== false) {
            return 'windows';
        } elseif (strpos($os, 'linux') !== false) {
            return 'linux';
        } else {
            return 'unknown';
        }
    }

    public function isRunning()
    {
        $status = proc_get_status($this->process);

        return $status['running'];
    }

    public function getStatus()
    {
        if (!is_resource($this->process)) {
            return false;
        }

        return proc_get_status($this->process);
    }

    /**
     * Get the process output.
     * 
     * @return string|bool
     */
    public function getOutput()
    {
        if (is_resource($this->process)) {
            do {
                $read = array($this->pipes[1], $this->pipes[2]);
                $write = null;
                $except = null;

                if (stream_select($read, $write, $except, 5)) {
                    foreach ($read as $c) {
                        if (feof($c)) {
                            continue;
                        }
                        $read = fread($c, 1024);

                        if ($read === false) {
                            continue;
                        }

                        echo $read;
                    }
                }
            } while (!feof($this->pipes[1]) | !feof($this->pipes[2]));

            fclose($this->pipes[0]);
            fclose($this->pipes[1]);
            fclose($this->pipes[2]);

            // It is important to close any pipes before calling
            // proc_close to avoid a deadlock
            $returnValue = proc_close($this->process);

            // if ($returnValue != 0) {
            //     // if ($exitOnError) {
            //     //     exit(1);
            //     // }
            // }

        }
    }


    /**
     * Terminate the running process.
     * 
     * @return bool
     */
    public function terminate()
    {
        if ($this->isRunning()) {
            $result = proc_terminate($this->process);

            while ($this->isRunning()) {
                usleep(100000); // Tunggu 100.000 mikrodetik (0.1 detik)
            }

            proc_close($this->process);


            return true;
        }

        return false;
    }

    public function getExitCode()
    {
        $status = proc_get_status($this->process);

        return $status['exitcode'];
    }

    public function close()
    {
        $this->closePipes();
        $this->closeProcess();
    }

    private function closePipes()
    {
        foreach ($this->pipes as $pipe) {
            if (is_resource($pipe)) {
                fclose($pipe);
            }
        }

        $this->pipes = [];
    }

    private function closeProcess()
    {
        if (is_resource($this->process)) {
            proc_terminate($this->process);
            proc_close($this->process);

            $this->process = null;
        }

    }
}
