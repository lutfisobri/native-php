<?php
namespace Riyu\View\Component;

class Form extends Component
{
    public function action($action)
    {
        $this->attributes['action'] = $action;

        return $this;
    }

    public function method($method)
    {
        $this->attributes['method'] = $method;

        return $this;
    }

    public function render($callback = null)
    {
        $content = '<form';
        foreach ($this->attributes as $key => $value) {
            $content .= ' ' . $key . '="' . $value . '"';
        }

        $content .= $this->renderStyle();
        
        $content .= '>';
        $content .= parent::render($callback);
        $content .= '</form>';

        return $content;
    }
}