<?php

namespace App\Http\Middleware;

use Closure;
use URL;

class Login
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->has('login'))
        {
            $request->session()->put('url.intended', URL::previous() );
            return redirect(action('Auth\AuthController@getLogin'));
        }

        return $next($request);
    }
}
