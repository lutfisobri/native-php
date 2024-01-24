<?php
namespace Riyu\View\Component;

class Container extends Component
{
    public function render($callback = null)
    {
        $content = '';
        foreach ($this->attributes as $key => $value) {
            if ($key == 'class') {
                $content = $value;
            }
        }

        $class = 'container' . ($content ? ' ' . $content : '');
        
        $content = '<div class="' . $class . '"';

        foreach ($this->attributes as $key => $value) {
            if ($key == 'class') {
                continue;
            }
            $content .= ' ' . $key . '="' . $value . '"';
        }

        $content .= $this->renderStyle();

        $content .= '>';

        $content .= parent::render($callback);
        $content .= '</div>';

        return $content;
    }
}