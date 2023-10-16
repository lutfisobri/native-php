<?php
namespace views\Article;

use Riyu\View\Component\A;
use Riyu\View\Component\Div;
use Riyu\View\Component\H;
use Riyu\View\Widget\Widget;
use views\components\Template;

class ListArticle extends Widget
{
    public function build()
    {
        return (new Template($this->content(), 'List Article'))->render();
    }

    public function content()
    {
        return (new Div())->class('container')->render([
            (new Div())->class('row')->render([
                (new Div())->class('col-md-12')->render([
                    (new Div())->class('card')->render([
                        (new Div())->class('card-header')->render([
                            (new Div())->class('row')->render([
                                (new Div())->class('col-md-6')->render([
                                    (new Div())->class('card-title')->render([
                                        (new H)->number(4)->render('List Article'),
                                    ])
                                ]),
                                (new Div())->class('col-md-6')->render([
                                    (new Div())->class('float-right')->render([
                                        (new A)->href('/article/create')->class('btn btn-primary')->render('Create Article'),
                                    ])
                                ])
                            ])
                        ]),
                        (new Div())->class('card-body')->render([
                            (new Div())->class('table-responsive')->render([
                                (new Div())->class('table table-hover')->render([
                                    // (new Div())->class('thead')->render([
                                    //     (new Div())->class('tr')->render([
                                    //         (new Div())->class('th')->render([
                                    //             'Title'
                                    //         ]),
                                    //         (new Div())->class('th')->render([
                                    //             'Content'
                                    //         ]),
                                    //         (new Div())->class('th')->render([
                                    //             'Action'
                                    //         ])
                                    //     ])
                                    // ]),
                                    (new Div())->class('tbody')->render(array_map(function ($article) {
                                        return (new Div())->class('tr')->render([
                                            (new Div())->class('td')->render([
                                                (new H)->number(5)->render([
                                                    (new A)->href('/article/detail/' . $article['id'])->render($article['title'])
                                                ])
                                            ]),
                                            (new Div())->class('td')->render([
                                                $article['content']
                                            ]),
                                            (new Div())->class('td')->render([
                                                (new Div())->class('btn-group')->render([
                                                    // (new A)->href('/article/detail/' . $article['id'])->class('btn btn-primary')->render('Detail'),
                                                    (new A)->href('/article/edit/' . $article['id'])->class('btn btn-warning')->render('Edit'),
                                                    (new A)->href('/article/delete/' . $article['id'])->class('btn btn-danger')->render('Delete'),
                                                ])
                                            ])
                                        ]);
                                    }, ArticleModel::all()))
                                ])
                            ])
                        ])
                    ])
                ])
            ])
        ]);
    }
}