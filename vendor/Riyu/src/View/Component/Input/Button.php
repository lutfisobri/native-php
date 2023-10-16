<?php
namespace Riyu\View\Component\Input;

use Riyu\View\Component\Component;

class Button extends Component
{
    public function type($type)
    {
        if (is_null($type)) {
            return $this;
        }

        $this->attributes['type'] = $type;

        return $this;
    }

    public function render($callback = null)
    {
        $content = '<button';
        foreach ($this->attributes as $key => $value) {
            $content .= ' ' . $key . '="' . $value . '"';
        }

        $content .= $this->renderStyle();
        
        $content .= '>';
        $content .= parent::render($callback);
        $content .= '</button>';

        return $content;
    }
}