<?php
namespace App\Services;

use Riyu\Foundation\Service\ServiceProvider;
use Riyu\Http\Route;

class RouteProvider extends ServiceProvider
{
    public function register()
    {
        Route::prefix('')->group(app()->getBasePath() . '/routes/web.php');
        Route::prefix('/api')->group(app()->getBasePath() . '/routes/api.php');
    }

    public function boot()
    {
        //
    }
}