<?php
namespace Riyu\View\Component\Input;

use Riyu\View\Component\Component;

class Select extends Component
{
    protected $select;

    public function id($id)
    {
        if (is_null($id)) {
            return $this;
        }

        $this->attributes['id'] = $id;

        return $this;
    }

    public function name($name)
    {
        if (is_null($name)) {
            return $this;
        }

        $this->attributes['name'] = $name;

        return $this;
    }

    public function value($value)
    {
        if (is_null($value)) {
            return $this;
        }

        $this->attributes['value'] = $value;

        return $this;
    }

    public function placeholder($placeholder)
    {
        if (is_null($placeholder)) {
            return $this;
        }

        $this->attributes['placeholder'] = $placeholder;

        return $this;
    }

    public function render($callback = null)
    {
        $content = '<select';
        foreach ($this->attributes as $key => $value) {
            $content .= ' ' . $key . '="' . $value . '"';
        }

        $content .= $this->renderStyle();
        
        $content .= '>';
        $content .= parent::render($callback);
        $content .= '</select>';

        return $content;
    }
}