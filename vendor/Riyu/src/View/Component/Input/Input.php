<?php
namespace Riyu\View\Component\Input;

use Riyu\View\Component\Component;

class Input extends Component
{
    protected $input;

    public function type(string $type)
    {
        $this->attributes['type'] = $type;

        return $this;
    }

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

    public function selected()
    {
        $this->attributes['selected'] = 'selected';

        return $this;
    }

    public function required()
    {
        $this->attributes['required'] = 'required';

        return $this;
    }

    public function autofocus()
    {
        $this->attributes['autofocus'] = 'autofocus';

        return $this;
    }

    public function disabled()
    {
        $this->attributes['disabled'] = 'disabled';

        return $this;
    }

    public function readonly()
    {
        $this->attributes['readonly'] = 'readonly';

        return $this;
    }

    public function maxlength($maxlength)
    {
        if (is_null($maxlength)) {
            return $this;
        }

        $this->attributes['maxlength'] = $maxlength;

        return $this;
    }

    public function minlength($minlength)
    {
        if (is_null($minlength)) {
            return $this;
        }

        $this->attributes['minlength'] = $minlength;

        return $this;
    }

    public function size($size)
    {
        if (is_null($size)) {
            return $this;
        }

        $this->attributes['size'] = $size;

        return $this;
    }

    public function checked()
    {
        $this->attributes['checked'] = 'checked';

        return $this;
    }

    public function render($callback = null)
    {
        $this->input = '<input';

        foreach ($this->attributes as $key => $value) {
            $this->input .= ' ' . $key . '="' . $value . '"';
        }

        $this->input .= $this->renderStyle();

        $this->input .= '>';

        return $this->input;
    }
}