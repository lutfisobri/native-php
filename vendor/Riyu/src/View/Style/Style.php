<?php
namespace Riyu\View\Style;

class Style
{
    protected $attributes = [];

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function color($color)
    {
        $this->attributes['color'] = $color;

        return $this;
    }

    public function backgroundColor($backgroundColor)
    {
        $this->attributes['background-color'] = $backgroundColor;

        return $this;
    }

    public function backgroundImage($backgroundImage)
    {
        $this->attributes['background-image'] = $backgroundImage;

        return $this;
    }

    public function border($border)
    {
        $this->attributes['border'] = $border;

        return $this;
    }

    public function borderColor($borderColor)
    {
        $this->attributes['border-color'] = $borderColor;

        return $this;
    }

    public function borderRadius($borderRadius)
    {
        $this->attributes['border-radius'] = $borderRadius;

        return $this;
    }

    public function boxShadow($boxShadow)
    {
        $this->attributes['box-shadow'] = $boxShadow;

        return $this;
    }

    public function padding($padding)
    {
        $this->attributes['padding'] = $padding;

        return $this;
    }

    public function margin($margin)
    {
        $this->attributes['margin'] = $margin;

        return $this;
    }

    public function width($width)
    {
        $this->attributes['width'] = $width;

        return $this;
    }

    public function height($height)
    {
        $this->attributes['height'] = $height;

        return $this;
    }

    public function display($display)
    {
        $this->attributes['display'] = $display;

        return $this;
    }

    public function float($float)
    {
        $this->attributes['float'] = $float;

        return $this;
    }

    public function position($position)
    {
        $this->attributes['position'] = $position;

        return $this;
    }

    public function top($top)
    {
        $this->attributes['top'] = $top;

        return $this;
    }

    public function right($right)
    {
        $this->attributes['right'] = $right;

        return $this;
    }

    public function bottom($bottom)
    {
        $this->attributes['bottom'] = $bottom;

        return $this;
    }

    public function left($left)
    {
        $this->attributes['left'] = $left;

        return $this;
    }

    public function textAlign($textAlign)
    {
        $this->attributes['text-align'] = $textAlign;

        return $this;
    }

    public function textDecoration($textDecoration)
    {
        $this->attributes['text-decoration'] = $textDecoration;

        return $this;
    }

    public function textTransform($textTransform)
    {
        $this->attributes['text-transform'] = $textTransform;

        return $this;
    }

    public function fontSize($fontSize)
    {
        $this->attributes['font-size'] = $fontSize;

        return $this;
    }

    public function fontWeight($fontWeight)
    {
        $this->attributes['font-weight'] = $fontWeight;

        return $this;
    }

    public function fontStyle($fontStyle)
    {
        $this->attributes['font-style'] = $fontStyle;

        return $this;
    }

    public function fontFamily($fontFamily)
    {
        $this->attributes['font-family'] = $fontFamily;

        return $this;
    }

    public function lineHeight($lineHeight)
    {
        $this->attributes['line-height'] = $lineHeight;

        return $this;
    }

    public function letterSpacing($letterSpacing)
    {
        $this->attributes['letter-spacing'] = $letterSpacing;

        return $this;
    }

    public function verticalAlign($verticalAlign)
    {
        $this->attributes['vertical-align'] = $verticalAlign;

        return $this;
    }

    public function listStyle($listStyle)
    {
        $this->attributes['list-style'] = $listStyle;

        return $this;
    }

    public function listStyleType($listStyleType)
    {
        $this->attributes['list-style-type'] = $listStyleType;

        return $this;
    }

    public function listStylePosition($listStylePosition)
    {
        $this->attributes['list-style-position'] = $listStylePosition;

        return $this;
    }

    public function listStyleImage($listStyleImage)
    {
        $this->attributes['list-style-image'] = $listStyleImage;

        return $this;
    }

    public function opacity($opacity)
    {
        $this->attributes['opacity'] = $opacity;

        return $this;
    }

    public function overflow($overflow)
    {
        $this->attributes['overflow'] = $overflow;

        return $this;
    }

    public function zIndex($zIndex)
    {
        $this->attributes['z-index'] = $zIndex;

        return $this;
    }

    public function cursor($cursor)
    {
        $this->attributes['cursor'] = $cursor;

        return $this;
    }

    public function resize($resize)
    {
        $this->attributes['resize'] = $resize;

        return $this;
    }

    public function userSelect($userSelect)
    {
        $this->attributes['user-select'] = $userSelect;

        return $this;
    }

    public function content($content)
    {
        $this->attributes['content'] = $content;

        return $this;
    }

    public function attr($attr)
    {
        $this->attributes['attr'] = $attr;

        return $this;
    }

    public function counterIncrement($counterIncrement)
    {
        $this->attributes['counter-increment'] = $counterIncrement;

        return $this;
    }

    public function counterReset($counterReset)
    {
        $this->attributes['counter-reset'] = $counterReset;

        return $this;
    }

    public function quotes($quotes)
    {
        $this->attributes['quotes'] = $quotes;

        return $this;
    }

    public function filter($filter)
    {
        $this->attributes['filter'] = $filter;

        return $this;
    }

    public function render()
    {
        $content = 'style="';
        foreach ($this->attributes as $key => $value) {
            $content .= $key . ':' . $value . ';';
        }
        $content .= '"';

        return $content;
    }
}