<?php
namespace Riyu\View\Component;

use Riyu\View\Component\Component;

class H extends Component
{
    public function __construct($number = 1, array $attributes = [])
    {
        $this->attributes['number'] = $number;

        parent::__construct($attributes);
    }

    public function number(int $number)
    {
        $this->attributes['number'] = $number;

        return $this;
    }

    public function render($callback = null)
    {
        $content = '<h' . $this->attributes['number'];
        foreach ($this->attributes as $key => $value) {
            if ($key == 'number') {
                continue;
            }

            $content .= ' ' . $key . '="' . $value . '"';
        }

        $content .= $this->renderStyle();
        
        $content .= '>';
        $content .= parent::render($callback);
        $content .= '</h' . $this->attributes['number'] . '>';

        return $content;
    }
}