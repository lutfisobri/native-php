<?php
namespace Riyu\Foundation\Controller;

use Riyu\Contract\Controller as ControllerContract;

class Controller implements ControllerContract
{
    public function callAction($action, $params)
    {
        if (method_exists($this, $action)) {
            return call_user_func_array([$this, $action], $params);
        } else {
            throw new \Exception("Method {$action} does not exist.");
        }
    }
}