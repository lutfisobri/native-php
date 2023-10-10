<?php
namespace Riyu\Helpers\Log;

use Riyu\Foundation\Application;

class Log
{
    protected static $context;

    public function __construct(Application $context)
    {
        self::$context = $context;
    }

    public function writeLog($message, $type)
    {
        $path = static::$context->getBasePath();

        $this->createFolder($path . '/storage/logs');

        $content = '[' . date('Y-m-d H:i:s') . '] ' . strtoupper($type) . ': ' . $message . "\n";

        $this->createFile($path . '/storage/logs/' . date('Y-m-d') . '.log', $content);
    }

    public function error($exception)
    {
        $this->writeLog($exception->getMessage(), 'error');
        $this->writeLog($exception->getFile() . ' on line ' . $exception->getLine(), 'error');
        $this->writeLog($exception->getTraceAsString(), 'error');
    }

    public function endsWithSlash($string)
    {
        return substr($string, -1) === '/';
    }

    public function createFolder($path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
    }

    public function createFile($path, $content)
    {
        $file = fopen($path, 'w');
        fwrite($file, $content);
        fclose($file);
    }
}