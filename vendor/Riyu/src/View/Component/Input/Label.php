<?php
namespace Riyu\View\Component\Input;

use Riyu\View\Component\Component;

class Label extends Component
{
    protected $label;

    public function __construct(string $label = null, array $attributes = [])
    {
        if (!is_null($label)) {
            $this->label = $label;
        }

        parent::__construct($attributes);
    }

    public function for($for)
    {
        $this->attributes['for'] = $for;

        return $this;
    }

    public function label(string $label)
    {
        if (is_null($label)) {
            return $this;
        }

        return $this;
    }

    public function render($callback = null)
    {
        $content = '<label';
        foreach ($this->attributes as $key => $value) {
            $content .= ' ' . $key . '="' . $value . '"';
        }

        $content .= $this->renderStyle();
        
        $content .= '>';
        $content .= $this->label;
        $content .= '</label>';

        return $content;
    }
}