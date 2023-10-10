<?php
namespace Riyu\Foundation\Exception;

use Exception;

class RouteNotFound extends Http
{
    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        if (is_null($message)) {
            $message = 'Route not found';
        }

        parent::__construct($message, $code, $previous);
    }
}