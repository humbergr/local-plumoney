<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Role
{
    public $roles = [
        'administrator'  => 1,
        'admin'          => 2,
        'finances'       => 3,
        'trader'         => 4,
        'merchant'       => 5,
        'trader_master'  => 6,
        'compliance'     => 7,
        'support'        => 8,
        'wallets_trader' => 9
    ];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @param                          $roles
     *
     * @return mixed
     */
    public function handle($request, Closure $next, ... $roles)
    {
        if (Auth::user()) {
            foreach ($roles as $role) {
                if (Auth::user()->role_id === $this->roles[$role]) {
                    return $next($request);
                }
            }
        }

        if (Auth::user() && Auth::user()->role_id !== 5) {
            return redirect('/app');
        }

        return redirect('/');
    }
}
