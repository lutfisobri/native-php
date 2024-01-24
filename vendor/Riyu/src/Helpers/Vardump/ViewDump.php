<?php
namespace Riyu\Helpers\Vardump;

use Riyu\View\Component\Container;
use Riyu\View\Component\Div;
use Riyu\View\Widget\Screen;
use Riyu\View\Widget\Widget;

class ViewDump extends Widget
{
    public function build()
    {
        return (new Screen())
            ->css('https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0')
            ->body($this->content())
            ->style($this->style())
            ->render();
    }

    public function content()
    {
        $data = $this->get('var');
        if (is_array($data)) {
            $data = $this->renderArray($data);
        } else if (is_object($data)) {
            $data = $this->renderObject($data);
        } else if ($data instanceof \Closure) {
            $data = $this->renderClosure($data);
        } else {
            $data = $this->renderValue($data);
        }

        return (new Container())->render([
            (new Div())->class('vardump')->render([
                (new Div())->class('vardump-content')->render($data),
            ]),
        ]);
    }

    public function renderArray($data)
    {
        $result = [];
        foreach ($data as $key => $value) {
            $result[] = (new Container())->render([
                (new Div())->class('card')->render([
                    (new Div())->class('card-body')->render([
                        (new Div())->class('card-title')->render($key),
                        (new Div())->class('card-text')->render(
                            $this->renderValue($value)
                        ),
                    ]),
                ]),
            ]);
        }
        return $result;
    }

    public function renderValue($value)
    {
        if (is_array($value)) {
            return $this->renderArray($value);
        } else if (is_object($value)) {
            return $this->renderObject($value);
        } else if ($value instanceof \Closure) {
            return $this->renderClosure($value);
        } else {
            return $value;
        }
    }

    public function renderObject($value)
    {
        $result = [];
        foreach ($value as $key => $value) {
            $result[] = (new Container())->render([
                (new Div())->class('card')->render([
                    (new Div())->class('card-body')->render([
                        (new Div())->class('card-title')->render($key),
                        (new Div())->class('card-text')->render($this->renderValue($value)),
                    ]),
                ]),
            ]);
        }
        return $result;
    }

    public function renderClosure($value)
    {
        return (new Container())->render([
            (new Div())->class('card')->render([
                (new Div())->class('card-body')->render([
                    (new Div())->class('card-title')->render('Closure'),
                    (new Div())->class('card-text')->render($value),
                ]),
            ]),
        ]);
    }

    public function style()
    {
        return "
            .vardump {
                font-family: 'Material Symbols Outlined', sans-serif;
                font-size: 1.5rem;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                width: 100vw;
                background-color: #f5f5f5;
            }
            .vardump-content {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                width: 100%;
            }
            .card {
                width: 100%;
                margin-bottom: 1rem;
            }
            .card-body {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
            .card-title {
                font-size: 1.5rem;
                font-weight: bold;
                margin-bottom: 1rem;
            }
            .card-text {
                font-size: 1.5rem;
            }
        ";
    }
}