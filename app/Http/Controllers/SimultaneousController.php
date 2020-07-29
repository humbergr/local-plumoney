<?php


namespace App\Http\Controllers;


use App\Akb\Banker;
use App\Akb\Toolkit;
use App\User;
use App\UserExchangeTransactions;
use App\UserWalletsTransactions;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Pusher\Laravel\Facades\Pusher;

class SimultaneousController extends Controller
{
    /**
     * @param null $admins
     *
     * @return JsonResponse
     */
    public function getActiveUsers($admins = null): JsonResponse
    {
        return response()->json([
            'activeUsers' => self::searchActiveUsers($admins),
            'clientUser'  => Auth::user() ? Auth::user()->toArray() : []
        ]);
    }

    /**
     * @param null $admins
     *
     * @return JsonResponse
     */
    public function setIdleUser($admins = null): JsonResponse
    {
        $user = Auth::user();

        if ($user !== null) {
            $user->is_idle           = 1;
            $user->is_idle_timestamp = time();
            $user->save();
        }

        Pusher::trigger('admin-states-channel', '__admin_is_idle', 'Some admin is inactive');

        return response()->json([
            'activeUsers' => self::searchActiveUsers($admins),
            'clientUser'  => $user ? $user->toArray() : []
        ]);
    }

    /**
     * @param null $admins
     *
     * @return JsonResponse
     */
    public function rmIdleUser($admins = null): JsonResponse
    {
        $user = Auth::user();

        if ($user !== null) {
            $user->is_idle           = 0;
            $user->is_idle_timestamp = null;
            $user->save();
        }

        if ($admins) {
            Pusher::trigger('admin-states-channel', '__admin_is_not_idle', 'Some admin is not longer inactive');

            if ($user->role_id === 4) {
                Banker::assignForcedExchangeTransactions($user);
            }

            if ($user->role_id === 9) {
                Banker::assignForcedWalletsTransactions($user);
            }
        }

        return response()->json([
            'activeUsers' => self::searchActiveUsers($admins),
            'clientUser'  => $user ? $user->toArray() : []
        ]);
    }

    /**
     * @return int
     */
    public function imAliveUser(): int
    {
        $user = Auth::user();

        if ($user !== null) {
            $user->keep_alive_timestamp = time();
            $user->save();

            return $user->keep_alive_timestamp;
        }
    }

    /**
     * Run process to put inactive users on idle, and kick out complete AFK users.
     */
    public function afkWatcher(): void
    {
        Toolkit::afkWatchman();
    }

    /**
     * @param int $operation_id
     * @param int $id
     *
     * @return string
     */
    public function transferOperationChat(int $operation_id, int $id): string
    {
        $user      = User::find($id);
        $operation = UserExchangeTransactions::find($operation_id);

        Banker::directAssignExchangeOperator($operation, $user);

        return 'true';
    }

    /**
     * @param int $operation_id
     * @param int $id
     *
     * @return string
     */
    public function transferWalletsOperationChat(int $operation_id, int $id): string
    {
        $user      = User::find($id);
        $operation = UserWalletsTransactions::find($operation_id);

        Banker::directAssignWalletsOperator($operation, $user);

        return 'true';
    }

    /**
     * @param null $admins
     *
     * @return mixed
     */
    private static function searchActiveUsers($admins = null)
    {
        $users = User::where(['is_logged_in' => true]);

        if ($admins) {
            $users = $users->where('role_id', '!=', 5);
        }

        return $users->get()->toArray();
    }
}
