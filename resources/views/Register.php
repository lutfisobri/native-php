<?php
namespace views;

use Riyu\View\Component\Container;
use Riyu\View\Widget\Widget;
use views\components\Template;

class Register extends Widget
{
    public function build()
    {
        return (new Template($this->content(), 'Register'))->render();
    }

    public function content()
    {
        $data = [];
        foreach ($this->all() as $key => $value) {
            $data[] = (new Container())->render([
                '<b>' . $key . '</b>: ' . $this->forceXss($value) . '<br>'
            ]);
        }
        return (new Container())->render($data);
    }
}