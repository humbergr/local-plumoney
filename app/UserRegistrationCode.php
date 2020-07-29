<?php

namespace App;

use App\Akb\Banker;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserRegistrationCode extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function registered_uses()
    {
        return $this->hasMany('App\UserRegistrationCodeUse');
    }

    /**
     * Return user earnings or user effective debt generated to publicist.
     * Can mark UserRegistrationCodeUse as payed.
     *
     * @param User        $user
     * @param bool        $asDebt
     * @param string|null $lastPaymentDate
     * @param bool        $paying
     *
     * @return mixed
     */
    public function getUserEarnings(
        User $user,
        bool $asDebt = false,
        string $lastPaymentDate = null,
        bool $paying = false
    ) {
        $totalExchangeTransactions = 0;
        $totalWalletsTransactions  = 0;
        $exchangeTransactionsArr   = $this->checkDisabledCode($user->exchangeTransactions);
        $walletsTransactionsArr    = $this->checkDisabledCode($user->walletsTransactions);

        foreach ($exchangeTransactionsArr as $exchangeTransaction) {
            if (isset($exchangeTransaction, $exchangeTransaction->walletTransaction)) {
                if ($lastPaymentDate && $exchangeTransaction->approved_at > $lastPaymentDate) {
                    $totalExchangeTransactions += $this->getTransactionValue(
                        $exchangeTransaction->walletTransaction->amount
                    );
                }

                if (
                    $lastPaymentDate && $exchangeTransaction->approved_at > $lastPaymentDate
                    && $exchangeTransaction->payment_way === 'userWallet'
                    && $exchangeTransaction->receiver_fiat !== 'USD'
                ) {
                    $totalExchangeTransactions += $this->getTransactionValue(
                        $exchangeTransaction->walletTransaction->amount
                    );
                }

                if ($lastPaymentDate === null && $exchangeTransaction->payment_way !== 'userWallet') {
                    $totalExchangeTransactions += $this->getTransactionValue(
                        $exchangeTransaction->walletTransaction->amount
                    );
                }

                if ($lastPaymentDate === null && $exchangeTransaction->payment_way === 'userWallet'
                    && $exchangeTransaction->receiver_fiat !== 'USD') {
                    $totalExchangeTransactions += $this->getTransactionValue(
                        $exchangeTransaction->walletTransaction->amount
                    );
                }
            }
        }

        foreach ($walletsTransactionsArr as $walletsTransaction) {
            if (isset($walletsTransaction)) {
                if ($lastPaymentDate &&
                    $walletsTransaction->approved_at > $lastPaymentDate &&
                    $walletsTransaction->sender_fiat !== 'USD') {
                    $totalWalletsTransactions += $this->getTransactionValue($walletsTransaction->amount);
                }

                if ($lastPaymentDate === null && $walletsTransaction->sender_fiat !== 'USD') {
                    $totalWalletsTransactions += $this->getTransactionValue($walletsTransaction->amount);
                }
            }
        }

        if ($asDebt) {
            $codeUsage = $user->codeRegistration;

            if ($codeUsage === null ||
                ($codeUsage->is_payed !== null && $codeUsage->is_payed !== 0) ||
                $codeUsage->created_at < $lastPaymentDate) {
                return $totalExchangeTransactions + $totalWalletsTransactions;
            }
        }

        //Can't be paying without $asDebt
        if ($paying && $asDebt) {
            $codeUsage           = $codeUsage ?? UserRegistrationCodeUse::where(['user_id' => $user->id])->first();
            $codeUsage->is_payed = 1;
            $codeUsage->payed_at = Carbon::now()->format('Y-m-d H:i:s');
            $codeUsage->payed_by = Auth::user()->name;
            $codeUsage->save();
        }

        return $totalExchangeTransactions + $totalWalletsTransactions + $this->referral_bonus;
    }

    /**
     * @param float $amount
     *
     * @return float|int
     */
    private function getTransactionValue(float $amount)
    {
        return ($amount * $this->profit_percent) / 100;
    }

    /**
     * @param Collection $transactions
     *
     * @return Collection
     */
    private function checkDisabledCode(Collection $transactions): Collection
    {
        if ($this->is_disabled === null) {
            return $transactions;
        }

        foreach ($transactions as $key => $transaction) {
            if ($transaction->approved_at > $this->disabled_at) {
                unset($transactions[$key]);
            }
        }

        return $transactions;
    }

    public function totalPayed()
    {
        $payments   = PromotorPayment::where(['code_id' => $this->id])->get();
        $totalPayed = 0;

        foreach ($payments as $payment) {
            $totalPayed += $payment->payment_total;
        }

        return $totalPayed;
    }

}
