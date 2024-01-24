<?php
namespace App\Middleware;

use Riyu\Http\Request;

class UnAuthenticate
{
    public function handle(Request $request, \Closure $next)
    {
        if (auth()->user()) {
            return redirect('/');
        }

        return $next($request);
    }
}