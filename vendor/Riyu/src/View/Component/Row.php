<?php
namespace Riyu\View\Component;

class Row extends Component
{
    public function render($callback = null)
    {
        $content = '<div class="row"';
        foreach ($this->attributes as $key => $value) {
            $content .= ' ' . $key . '="' . $value . '"';
        }

        $content .= $this->renderStyle();
        
        $content .= '>';
        $content .= parent::render($callback);
        $content .= '</div>';

        return $content;
    }
}