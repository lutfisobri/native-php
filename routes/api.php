<?php

use App\Controller\HomeController;
use Riyu\Http\Request;
use Riyu\Http\Response;
use Riyu\Http\Route;

Route::get('/', function () {
    echo 'Hello World!';
});

Route::group(null, function () {
    Route::get('/{id}', function ($id) {
        $content = [
            'id' => $id,
            'name' => 'Riyu',
            'version' => '0.0.1'
        ];
        $response = new Response(json_encode($content));
        $response->contentType('application/json');
        return $response;
    });
});

Route::post('/login', [HomeController::class, 'login']);

Route::put('/update', function ($request) {
    $content = [
        'username' => $request->username,
        'password' => $request->password
    ];

    return (new Response())->json($content);
});