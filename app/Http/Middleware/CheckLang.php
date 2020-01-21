<?php

namespace App\Http\Middleware;

use App\Base;
use Closure;

class CheckLang
{
    public function __construct(?Base $base)
    {
        $this->base = $base;
    }
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
        if (!is_null($this->base)) {
            $lang = $request->query('lang') ?: $request->session()->get('lang');
            if (!in_array($lang, $this->base->languages, true)) {
                $lang = $this->base->default_language;
            }
            \App::setLocale($lang);
            $request->session()->put('lang', $lang);
        }

        return $next($request);
    }
}
