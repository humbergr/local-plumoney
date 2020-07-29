<?php

namespace App\Http\Middleware;

use Closure;

class AuditorsOnly
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
        if (\Auth::user() && \Auth::user()->role_id != 3){

          return redirect()->back()->with('error', 'You are unauthorized for this action.');

        }

        return $next($request);
    }
}
