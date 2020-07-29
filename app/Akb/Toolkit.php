<?php


namespace App\Akb;


use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Pusher\Laravel\Facades\Pusher;

class Toolkit
{
    /**
     * @param array|null $ids
     * @param Collection $users
     */
    public static function kickUsers(array $ids = null, Collection $users = null): void
    {
        $users = $users ?? User::find($ids);

        foreach ($users as $user) {
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

            Auth::setUser($user);
            Session::getHandler()->destroy($user->session_id);
            Auth::logout();
        }

        if (isset($user) && $user->role_id !== 5) {
            Pusher::trigger('admin-states-channel', '__admin_logged_out', 'Some admin has logged out');
        }
    }


    public static function afkWatchman()
    {
        $users         = User::where(['is_logged_in' => true])
            ->where('is_idle', '==', 0)
            ->where('role_id', '!=', 5)
            ->get();
        $executionTime = time();

        foreach ($users as $user) {
            if (($executionTime - $user->keep_alive_timestamp) >= 600) {
                $user->is_idle           = 1;
                $user->is_idle_timestamp = time();
                $user->save();
            }
        }

        Pusher::trigger('admin-states-channel', '__admin_is_idle', 'Some admin is inactive');

        self::kickQueue();
    }

    private static function kickQueue()
    {
        $users         = User::where(['is_idle' => 1])->get();
        $executionTime = time();

        foreach ($users as $key => $user) {
            if (($executionTime - $user->is_idle_timestamp) < 600) {
                unset($users[$key]);
            }
        }

        self::kickUsers(null, $users);
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public static function changeEnv($data = array()): ?bool
    {
        if (count($data) > 0) {

            // Read .env-file
            $env = file_get_contents(base_path() . '/.env');

            // Split string on every " " and write into array
            $env = preg_split('/\s+/', $env);

            // Loop through given data
            foreach ((array)$data as $key => $value) {

                // Loop through .env-data
                foreach ($env as $env_key => $env_value) {

                    // Turn the value into an array and stop after the first split
                    // So it's not possible to split e.g. the App-Key by accident
                    $entry = explode('=', $env_value, 2);

                    // Check, if new key fits the actual .env-key
                    if ($entry[0] === $key) {
                        // If yes, overwrite it with the new one
                        $env[$env_key] = $key . '=' . $value;
                    } else {
                        // If not, keep the old one
                        $env[$env_key] = $env_value;
                    }
                }
            }

            // Turn the array back to an String
            $env = implode("\n", $env);

            // And overwrite the .env with the new data
            file_put_contents(base_path() . '/.env', $env);

            return true;
        }

        return false;
    }
}
