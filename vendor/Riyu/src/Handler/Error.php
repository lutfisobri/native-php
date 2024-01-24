<?php
namespace Riyu\Handler;

use Riyu\Foundation\Application;

class Error
{
    protected $context;

    public function __construct(Application $context)
    {
        $this->context = $context;

        $this->register();
    }

    public function register()
    {
        set_error_handler([$this, 'errorHandler']);
        set_exception_handler([$this, 'exceptionHandler']);
        register_shutdown_function([$this, 'shutdownHandler']);
    }

    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
        $this->exceptionHandler(new \ErrorException($errstr, $errno, 0, $errfile, $errline));
    }

    public function exceptionHandler($exception)
    {
        $this->log($exception);
        $this->display($exception);
    }

    public function shutdownHandler()
    {
        $error = error_get_last();
        if ($error !== null) {
            $this->log(new \ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']));
            $this->display(new \ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']));
        }
    }

    public function log($exception)
    {
        $log = new \Riyu\Helpers\Log\Log($this->context);
        $log->error($exception);
    }

    public function display($exception)
    {
        $response = new \Riyu\Http\Response();
        $response->code(500);
        $response->content($this->render($exception));
        $response->send();
    }

    public function render($exception)
    {
        $html = '<html>';
        $html .= '<head>';
        $html .= '<title>500 Internal Server Error</title>';
        $html .= '</head>';
        $html .= '<body>';
        $html .= '<h1>500 Internal Server Error</h1>';
        $html .= '<p>' . $exception->getMessage() . '</p>';
        $html .= '<p>' . $exception->getFile() . ' on line ' . $exception->getLine() . '</p>';
        $html .= '<pre>' . $exception->getTraceAsString() . '</pre>';
        $html .= '</body>';
        $html .= '</html>';
        return $html;
    }
}