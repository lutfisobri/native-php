<?php
namespace App\Services;

use App\Controller\HomeController;
use Riyu\Foundation\Service\ServiceProvider;
use Riyu\Http\Route;

class RouteProvider extends ServiceProvider
{
    public function register()
    {
        Route::get('/{id}', function ($id) {
            echo $id;
        });
        Route::get('/test/{id}', [HomeController::class, 'index']);
    }
}