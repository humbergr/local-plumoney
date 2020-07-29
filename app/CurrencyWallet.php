<?php

namespace App;

use App\Akb\Banker;
use Illuminate\Database\Eloquent\Model;
use App\UserWalletsTransactions;
use Illuminate\Database\Eloquent\Relations\HasMany;
use function foo\func;

class CurrencyWallet extends Model
{
    protected $fillable = [
        'hash',
        'user_id',
        'status',
        'currency'
    ];

    public function changeStatus()
    {

    }

    public function getUser()
    {

    }

    public function getTransactions()
    {

    }

    public function user(): HasMany
    {
        return $this->hasMany(User::class, 'id', 'user_id');
    }

    /**
     * @return HasMany
     */
    public function relatedTransactions(): HasMany
    {
        return $this->hasMany(UserWalletsTransactions::class, 'wallet_id')
            ->with('relatedTransaction')
            ->with(['relatedWalletTransaction' => static function ($query) {
                $query->with('relatedTransaction');
            }])
            ->orderBy('created_at', 'desc');
    }

    public function getTransactionsToCustomer(array $allTransactions)
    {
        foreach ($allTransactions as $key => $transaction) {
            if (($transaction['type'] === 3 && $transaction['status'] === 2) ||
                ($transaction['type'] !== 3 && $transaction['status'] === 2)) {
                unset($allTransactions[$key]);
            }
        }

        $groupByDates = [];
        $inHold       = [];

        foreach ($allTransactions as $key => $transaction) {
            if ($transaction['type'] === 3 && ($transaction['status'] === 1 || $transaction['status'] === 6)) {
                $inHold[] = $transaction;
            } else {
                $explodedDate = explode('-',$transaction['created_at']);
                $groupByDates[$explodedDate[0]][date('F', mktime(0, 0, 0, $explodedDate[1], 10))][] = $transaction;
            }
        }

        return ['inHold' => $inHold, 'completed' => $groupByDates];
    }

    public static function boot()
    {
        parent::boot();
        static::retrieved(
            static function ($model) {
                /**
                 * @var CurrencyWallet $model
                 */
                $model['relatedTransactions']  = $model->relatedTransactions()->get()->toArray();
                $model['customerTransactions'] = $model->getTransactionsToCustomer($model['relatedTransactions']);
                $model['numbers']              = Banker::getTotalBalanceUserWallets($model);
            });
    }

    public function getReport()
    {

    }

    public function generateHash($isoCurrency = 'USD')
    {
        $hash          = uniqid('akb-' . $isoCurrency . '-', true);
        $controlWallet = new self();
        $controlWallet = $controlWallet::where(['hash' => $hash])->first();
        if ($controlWallet === null) {
            return $hash;
        }

        return $this->generateHash($isoCurrency);
    }
}
