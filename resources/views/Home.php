<?php

namespace views;

use Riyu\View\Component\{
    A,
    Container,
    Div,
    Form,
    Row,
    Input\Input,
    Input\Button,
    Input\Label,
};
use Riyu\View\Widget\Widget;
use views\components\Template;
use views\components\Text;

class Home extends Widget
{
    public function build()
    {
        return (new Template($this->content(), 'Home'))->render();
    }

    public function content()
    {
        return (new Container())->render([
            (new Row())->render([
                (new Div([
                    'class' => 'col-md-6 offset-md-3'
                ]))->render(fn () => $this->form())
            ])
        ]);
    }

    public function form()
    {
        return (new Form())->action('/register')->method('POST')->render([
            Text::child('email', 'email', 'Email', $this->get('email') ?? '', 'email'),
            Text::child('password', 'password', 'Password', $this->get('password') ?? '', 'password'),
            Text::child('password', 'password_confirmation', 'Password Confirmation', $this->get('password_confirmation') ?? '', 'password_confirmation'),

            (new Div)->class('row mb-4')->render([
                (new Div)->class('col d-flex justify-content-center')->render([
                    (new Div)->class('form-check')->render([
                        (new Input)->type('checkbox')->class('form-check-input')->id('form2Example31')->checked(),
                        (new Label('Remember me'))->class('form-check-label')->for('form2Example31'),
                    ])
                ]),
                (new Div)->class('col')->render([
                    (new A)->href('#!')->render('Forgot password?')
                ]),
                (new Button)->type('submit')->class('btn btn-primary btn-block mb-4')->render('Sign in'),
            ]),

        ]);
    }
}
