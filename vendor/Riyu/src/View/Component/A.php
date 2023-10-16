<?php
namespace Riyu\View\Component;

class A extends Component
{
    public function href($href)
    {
        $this->attributes['href'] = $href;

        return $this;
    }

    public function render($callback = null)
    {
        $content = '<a';
        foreach ($this->attributes as $key => $value) {
            $content .= ' ' . $key . '="' . $value . '"';
        }

        $content .= $this->renderStyle();
        
        $content .= '>';
        $content .= parent::render($callback);
        $content .= '</a>';

        return $content;
    }
}