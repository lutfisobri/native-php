<?php
namespace Riyu\View\Component;

use Riyu\View\Style\Style;

abstract class Component
{
    protected $style;

    protected $attributes = [];

    private $verbs = [
        'Date',
        'Time',
    ];

    protected $specialChars = false;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function class($class)
    {
        $this->attributes['class'] = $class;

        return $this;
    }

    public function specialChars(bool $specialChars)
    {
        $this->specialChars = $specialChars;

        return $this;
    }

    public function render($callback = null)
    {
        $content = '';
        if (is_callable($callback)) {
            $return = $callback();

            if ($return instanceof Component) {
                $content .= $return->render();
            } else {
                $content .= $return;
            }
        }

        if (is_array($callback)) {
            $content = '';
            foreach ($callback as $key => $value) {
                if ($value instanceof Component) {
                    $content .= $value->render();
                } else if (is_callable($value)) {
                    if (in_array($value, $this->verbs)) {
                        $content .= $value;
                    } else {
                        $content .= $value();
                    }
                } else {
                    $content .= $value;
                }
            }
        }

        if (is_string($callback)) {
            $content .= $callback;
        }

        return $this->setSpecialChars($content);
    }

    private function setSpecialChars($content)
    {
        if ($this->specialChars) {
            return htmlspecialchars($content);
        }

        return $content;
    }

    public function style(Style $style)
    {
        $this->style = $style;

        return $this;
    }

    public function renderStyle()
    {
        if ($this->style instanceof Style) {
            return $this->style->render();
        }

        return '';
    }
}