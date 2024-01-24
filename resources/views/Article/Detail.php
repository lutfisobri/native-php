<?php
namespace views\Article;

use Riyu\View\Component\Container;
use Riyu\View\Component\Div;
use Riyu\View\Widget\Widget;
use views\components\Template;

class Detail extends Widget
{
    public function build()
    {
        return (new Template($this->content(), 'Article Detail'))->render();
    }

    public function content()
    {
        $article = ArticleModel::find($this->get('id'));

        return (new Container())->render([
            (new Div())->class('card')->render([
                (new Div())->class('card-body')->render([
                    (new Div())->class('card-title')->render([
                        $article['title'],
                    ]),
                    (new Div())->class('card-text')->render([
                        $article['content'],
                    ]),
                ]),
            ]),
        ]);
    }
}