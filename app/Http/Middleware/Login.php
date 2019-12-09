<?php

namespace App\Http\Middleware;

use Closure;

class Login
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->has('login')) {
            $request->session()->put('url.intended', url()->previous());

            return redirect(action('Auth\LoginController@showLoginForm'));
        }

        return $next($request);
    }
}
