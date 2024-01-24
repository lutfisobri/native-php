<?php
namespace App\Services;

use Riyu\Foundation\Service\RouteProvider as ServiceProvider;
use Riyu\Http\Route;

class RouteProvider extends ServiceProvider
{
    public function register()
    {
        Route::prefix('')->group(app()->getBasePath() . '/routes/web.php');
        Route::prefix('/api')->group(app()->getBasePath() . '/routes/api.php');
    }
}