<?php
namespace views\components;

use Riyu\View\Component\Div;
use Riyu\View\Component\Input\Input;

class Text
{
    public static function child($type = 'text', $name = null, $placeholder = null, $value = null, $id = null)
    {
        return (new Div())->class('form-outline mb-4')->render([
            (new Input())->class('form-control')->type($type)->name($name)->placeholder($placeholder)->value($value)->id($id),
        ]);
    }
}