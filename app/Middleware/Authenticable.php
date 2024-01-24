<?php
namespace App\Middleware;

use Riyu\Http\Request;

class Authenticable
{
    public function handle(Request $request, \Closure $next)
    {
        if (!auth()->user()) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}