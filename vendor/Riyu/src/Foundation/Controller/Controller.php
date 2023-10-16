<?php
namespace Riyu\Foundation\Controller;

use Riyu\Contract\Controller as ControllerContract;
use Riyu\Http\Request;
use Riyu\Http\Response;
use Riyu\Validation\Validator;

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

    /**
     * @param Request $request
     * @param array $rules
     * @return array|false
     */
    public function validate(Request $request, $rules, $customMessages = null)
    {
        $validator = new Validator($request, $rules, $customMessages);
        $validator->validate();

        if ($validator->fails()) {
            return $validator->errors();
        }

        return false;
    }
}