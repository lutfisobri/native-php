<?php

use Riyu\Http\Route;
use views\Article\ArticleModel;
use views\Article\Create;
use views\Article\Detail;
use views\Article\ListArticle;
use views\Home;
use views\Register;

Route::get('/', fn() => view('welcome'));

Route::get('/login', fn () => widget(Home::class));

Route::post('/register', fn () => widget(Register::class));

Route::get('/article', fn () => widget(ListArticle::class));
Route::get('/article/create', fn () => widget(Create::class));
Route::post('/article/create', function($request) {
    ArticleModel::create([
        'title' => $request->title,
        'content' => $request->content,
    ]);
});
Route::get('/article/detail/{id}', fn ($id) => widget(Detail::class, ['id' => $id]));
Route::get('/article/edit/{id}', fn ($id) => widget(Detail::class, ['id' => $id]));
Route::post('/article/edit/{id}', function($request, $id) {
    ArticleModel::update($id, [
        'title' => $request->title,
        'content' => $request->content,
    ]);
});
Route::get('/article/delete/{id}', function($id) {
    ArticleModel::delete($id);
});
Route::get('/test', function() {
    return redirect('/article');
});