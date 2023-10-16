<?php
namespace Riyu\View\Component\Input;

use Riyu\View\Component\Component;

class Option extends Component
{
    protected $option;

    public function value($value)
    {
        if (is_null($value)) {
            return $this;
        }

        $this->attributes['value'] = $value;

        return $this;
    }

    public function render($callback = null)
    {
        $content = '<option';
        foreach ($this->attributes as $key => $value) {
            $content .= ' ' . $key . '="' . $value . '"';
        }

        $content .= '>';
        $content .= parent::render($callback);
        $content .= '</option>';

        return $content;
    }
}