<?php

return [
    'providers' => [
        \App\Services\CommandProvider::class,
        \App\Services\RouteProvider::class,
    ],
    'aliases' => [
        'auth' => \App\Middleware\Authenticable::class,
        'guest' => \App\Middleware\UnAuthenticate::class,
    ],
    'env' => 'env.php',
];