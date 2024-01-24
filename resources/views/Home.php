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
use Riyu\View\Style\Style;
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
            '<script>
            function password_show_hide() {
                var x = document.getElementById("password");
                var show_eye = document.getElementById("show_eye");
                var hide_eye = document.getElementById("hide_eye");
                hide_eye.classList.remove("d-none");
                if (x.type === "password") {
                  x.type = "text";
                  show_eye.style.display = "none";
                  hide_eye.style.display = "block";
                } else {
                  x.type = "password";
                  show_eye.style.display = "block";
                  hide_eye.style.display = "none";
                }
              },
            </script>',
            (new Row)->render(
                (new Div)->class('col-md-6 offset-md-3')->render($this->form())
            )
        ]);
    }

    public function form()
    {
        return (new Container)->class('mt-4')->render([
            (new Form())->action('/login')->method('POST')->render([
                '<img src="https://mdbootstrap.com/img/Photos/new-templates/bootstrap-login-form/draw2.png" class="img-fluid" alt="smaple image">',
                // username and password
                Text::child('text', 'username', 'Username', $this->get('username') ?? '', 'username'),
                Text::child('password', 'password', 'Password', $this->get('password') ?? '', 'password'),
                '<button id="toggle-password" type="button" class="d-none"
                aria-label="Show password as plain text. Warning: this will display your password on the screen.">
              </button>',
    
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
                ]),
                (new Button)->type('submit')->class('btn btn-primary btn-block mb-4')->render('Sign in'),
            ]),

        ]);
    }
}
