<?php

namespace App\Http\Controllers\Auth;

use App\Akb\Banker;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\TransactionOrder;
use Carbon\Carbon;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Pusher\Laravel\Facades\Pusher;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected function authenticated($request, $user)
    {
        $user->last_login   = Carbon::now()->toDateTimeString();
        $user->is_logged_in = 1;
        $user->session_id   = session()->getId();
        $user->save();

        if ($user->role_id === 5) {
            return Redirect('/home');
        }

        if ($user->role_id === 4) {
            Banker::assignExchangeTransactions($user);
        }

        if ($user->role_id === 9) {
            Banker::assignWalletsTransactions($user);
        }

        if ($user->role_id !== 5) {
            Pusher::trigger('admin-states-channel', '__admin_logged_in', 'New admin has logged in');
        }

        return Redirect('/app');
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/app';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     *
     * @return Response | RedirectResponse
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user !== null) {
            $user->last_logout       = Carbon::now()->toDateTimeString();
            $user->is_logged_in      = 0;
            $user->is_idle           = 0;
            $user->is_idle_timestamp = null;
            $user->session_id        = null;
            $user->save();

            if ($user->role_id === 4) {
                Banker::watchForcedTransfer($user->id);
            }

            if ($user->role_id === 9) {
                Banker::watchForcedWalletsTransfer($user->id);
            }
        }

        $this->guard()->logout();
        $request->session()->invalidate();

        if ($user !== null && $user->role_id !== 5) {
            Pusher::trigger('admin-states-channel', '__admin_logged_out', 'Some admin has logged out');

            unset($user);
        }

        return $this->loggedOut($request) ? : redirect('/');
    }
}
