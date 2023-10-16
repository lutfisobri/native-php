<?php
namespace views\Article;

use Riyu\View\Component\Container;
use Riyu\View\Component\Form;
use Riyu\View\Component\Input\Input;
use Riyu\View\Widget\Widget;
use views\components\Template;

class Create extends Widget
{
    public function build()
    {
        return (new Template($this->content(), 'Create Article'))->render();
    }

    public function content()
    {
        return (new Container())->render([
            (new Form())->action('/article/create')->method('POST')->render([
                (new Input())->type('text')->name('title')->placeholder('Title')->render(),
                (new Input())->type('text')->name('content')->placeholder('Content')->render(),
                (new Input())->type('submit')->value('Submit')->render(),
            ]),
        ]);
    }
}