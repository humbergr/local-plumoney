<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App;
use Config;
class LanguageSwitcher
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
        if (!Session::has('locale'))
         {
           Session::put('locale', 'sp');
        }
        App::setLocale(session('locale'));
        return $next($request);
    }
}
