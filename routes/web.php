<?php

use App\Controller\HomeController;
use App\Middleware\Authenticable;
use Riyu\Http\Request;
use Riyu\Http\Route;
use views\Article\ArticleModel;
use views\Article\Create;
use views\Article\Detail;
use views\Article\ListArticle;
use views\Home;
use views\Register;

Route::get('/', function () {
    $data = [
        'title' => 'Hello World',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.',
    ];
    return view('welcome', compact('data'));
})->middleware('auth')->name('home');

// Route::get('/hot-reload', function () {
//     $data = (object) [
//         'title' => 'Hello World',
//         'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.',
//     ];
//     return view('hotReload', compact('data'));
// });

Route::group(['middleware' => 'auth'], function () {
    Route::get('/logout', [HomeController::class, 'logout'])->name('logout');

    Route::get('/article', fn () => widget(ListArticle::class))->name('article');
    Route::get('/article/create', fn () => widget(Create::class));
    Route::post('/article/create', function ($request) {
        ArticleModel::create([
            'title' => $request->title,
            'content' => $request->content,
        ]);
    });
    Route::get('/article/detail/{id}', fn ($id) => widget(Detail::class, ['id' => $id]));
    Route::get('/article/edit/{id}', fn ($id) => widget(Detail::class, ['id' => $id]));
    Route::post('/article/edit/{id}', function ($request, $id) {
        ArticleModel::update($id, [
            'title' => $request->title,
            'content' => $request->content,
        ]);
    });
    Route::get('/article/delete/{id}', function ($id) {
        ArticleModel::delete($id);
    });

    Route::get('/user/{id}', [HomeController::class, 'user'])->middleware('auth');
});

Route::get('/login', [HomeController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [HomeController::class, 'login']);

Route::post('/register', fn () => widget(Register::class));
Route::get('/test', function () {
    dd($_SERVER);
    $content = [
        'title' => 'Hello World',
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.',
    ];
    return redirect();
})->name('test');

Route::get('/generate', function () {
    echo '<pre>';
    var_dump($_SERVER);
    echo '</pre>';
    // $session = session();
    // session()->regenerate();
    // dd($session, session());
});

Route::get('destroy', function () {
    session()->destroy();
});

// Route::get('/test2', function () {
//     return redirect()->route('test');
// })->name('test2');