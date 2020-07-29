<?php

namespace App;

use App\Akb\Banker;
use App\UserWalletsTransactions;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\TransactionOrderMessage;
use Illuminate\Support\Facades\Auth;

use Pusher\Laravel\Facades\Pusher;
use App\User;

class UserExchangeTransactions extends Model
{
    /** 
     * @var array
     */
    protected $fillable = [
        'user_id',
        'destination_account',
        'sender_fiat',
        'sender_fiat_amount',
        'receiver_fiat',
        'receiver_fiat_amount',
        'payment_way',
        'payment_source',
        'status',
        'approved_at',
        'approved_by',
        'metadata',
        'failed_at',
        'failed_by',
        'tracking_id',
        'payment_support',
        'wallet_transaction_id',
        'rejected_at',
        'rejected_by',
        'is_payed',
        'is_revised',
        'payed_at',
        'payed_by',
        'stripe_data',
        'exchange_rate',
        'attended_by',
        'attended_at',
        'exchange_rate_id',
        'trader_id',
        'trader_info',
        'refund_at',
        'refund_by',
        'notes',
        'forced_transfer',
        'previous_assignations',
        'forced_transfer_dates',
        'fee_at_moment',
        'revised_founds',
        'bank_move'
      
    ];

    protected $casts = [
        'metadata'                 => 'array',
        'stripe_data'              => 'array',
        'trader_info'              => 'array',
        'previous_assignations'    => 'array',
        'forced_transfer_dates'    => 'array',
        'destination_account_json' => 'array'
    ];

    public static function boot()
    {
        parent::boot();
        static::retrieved(
            static function ($model) {
                /**
                 * @var CurrencyWallet $model
                 */
                $anchorDateEDT    = new Carbon($model['created_at']);
                $anchorDateEDT    = $anchorDateEDT
                    ->setTimezone('EDT')
                    ->format('Y-m-d H:i:s');
                $model['edtDate'] = $anchorDateEDT;
            });
    }

    /**
     * Status
     * 1 - Approved
     * 2 - Rejected
     * 3 - Failed
     * 4 - In Process
     * 5 - Refund
     *
     * /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function merchant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function trader(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\User', 'trader_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contact(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\LocalContact', 'contact_id', 'local_contact_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function outgoing(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\OutgoingBtc', 'contact_id', 'contact_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TransactionOrderMessage::class, 'order_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function destinationAccount(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(DestinationAccount::class, 'id', 'destination_account');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function Operator(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'id', 'attended_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function bonusCoupon(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne('App\BonusCoupon', 'transaction_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userAccount(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function masterAccount(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'id', 'attended_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function walletTransaction(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(UserWalletsTransactions::class, 'id', 'wallet_transaction_id');
    }

    /**
     * Format dates for Blade templates
     *
     * @param $date
     *
     * @return string
     */
    public function getHumanDate($date): string
    {
        $humanDate = new Carbon($date);

        return $humanDate->format('m/d/Y');
    }

    /**
     * @return mixed
     */
    public function paymentMethod()
    {
        $paymentMethods = [
            'cash_deposit' => 'Pago en Efectivo',
            'Stripe'       => 'Tarjeta de Crédito o Débito',
            'Pago123'       => 'Tarjeta de Crédito o Débito',
            'QuickBook'    => 'Tarjeta de Crédito o Débito - QB',
            'zelle'        => 'Zelle',
            'venmo'        => 'Venmo',
            'cashapp'      => 'Cash App',
            'payoneer'     => 'Payoneer',
            'popmoney'     => 'PopMoney',
            'pandco'       => 'Pandco',
            'userWallet'   => 'Wallet AKB'
        ];

        return $paymentMethods[$this->payment_way];
    }

    public function getRate()
    {
        $rate = $this->receiver_fiat_amount / $this->sender_fiat_amount;

        if ($rate < 0.1) {
            return number_format(1 / $rate, 2) . ' ' . $this->sender_fiat . '/' . $this->receiver_fiat;
        }

        return number_format($rate, 2) . ' ' . $this->receiver_fiat . '/' . $this->sender_fiat;
    }

    public function payment()
    {
        return $this->hasMany('App\ExchangePaymentData', 'exchange_id');
    }

    /**
     * @param      $amount
     * @param      $isoCurrency
     * @param      $approvedBy //Master who approved the transaction or Stripe.
     * @param null $fastOut
     *
     * @return bool
     */
    public function foundsMovementAdd($amount, $isoCurrency, $approvedBy, $fastOut = null): bool
    {
        $userWallet = CurrencyWallet::where([
            'user_id'  => Auth::user()->id,
            'currency' => $isoCurrency
        ])->first();

        if (!$userWallet) {
            $userWallet           = new CurrencyWallet();
            $userWallet->user_id  = Auth::user()->id;
            $userWallet->currency = $isoCurrency;
            $userWallet->save();
        }

        $userWallet->balance += $amount;
        $userWallet->locked  += $amount;

        $walletTransaction                               = new UserWalletsTransactions();
        $walletTransaction->wallet_id                    = $userWallet->id;
        $walletTransaction->user_id                      = Auth::user()->id;
        $walletTransaction->type                         = 1;
        $walletTransaction->status                       = 0;
        $walletTransaction->amount                       = $amount;
        $walletTransaction->currency                     = $isoCurrency;
        $walletTransaction->user_exchange_transaction_id = $this->id;

        if ($approvedBy === 'Stripe') {
            $walletTransaction->origin_foundation = 1;
            $walletTransaction->status            = 1;
            $walletTransaction->approved_at       = Carbon::now()->toDateTimeString();
            $walletTransaction->approved_by       = $approvedBy;
            $userWallet->available                += $amount;
            $userWallet->locked                   -= $amount;
        }

        $walletTransaction->save();
        $userWallet->save();

        if ($fastOut) {
            /**
             * No esta aprobada hasta que el status cambie a completada en la administración.
             * Se puede revertir con Status = 2 de cancelada.
             */
            $walletOutTransaction                               = new UserWalletsTransactions();
            $walletOutTransaction->wallet_id                    = $userWallet->id;
            $walletOutTransaction->user_id                      = Auth::user()->id;
            $walletOutTransaction->type                         = 2;
            $walletOutTransaction->status                       = 0;
            $walletOutTransaction->amount                       = $amount;
            $walletOutTransaction->currency                     = $isoCurrency;
            $walletOutTransaction->withdraw_mode                = 1;
            $walletOutTransaction->user_exchange_transaction_id = $this->id;

            $walletOutTransaction->save();
        }

        return true;
    }

    /**
     * @return bool
     */
    public function changePayed(): bool
    {
        unset($this['edtDate']);
        $this->is_payed              = 1;
        $this->payed_at              = Carbon::now()->format('Y-m-d H:i:s');
        $this->payed_by              = 'Trader Master: ' . Auth::user()->name;
        $walletTransaction           = UserWalletsTransactions::where(['id' => $this->wallet_transaction_id])
            ->first();
        $walletTransaction->status   = 2;
        $banker                      = new Banker;
        $this->wallet_transaction_id = $banker->rechargeWalletFromHold($walletTransaction);
        $rechargeTransaction         = UserWalletsTransactions::find($this->wallet_transaction_id);
        $this->wallet_transaction_id = $banker->holdWalletCreditsFromTransaction($rechargeTransaction, 2);

        $walletTransaction->save();
        $this->save();

        return true;
    }

    /**
     * @param int $status
     *
     * @return bool
     */
    public function changeStatus(int $status): bool
    {
        $this->status = $status;
        unset($this['edtDate']);

        if ($this->payment_way === 'cash_deposit' && $status !== 0) {
            Pusher::trigger('transactions-channel',
                'queue-event',
                [
                    'message'        => 'Transaction with id = ' . $this->id . ' has been approved.',
                    'order_id'       => $this->id,
                    'order_finished' => true,
                    //Many operations out of the queue
                    'many_out'       => 1,
                    'operator_id'    => null
                ]
            );
        }

        //Approved
        if ($this->status === 1) {
            $walletTransaction = UserWalletsTransactions::where(['id' => $this->wallet_transaction_id])
                ->first();
            $checkHold         = UserWalletsTransactions::where(['wallet_transaction_related_id' => $walletTransaction->id])
                ->first();

            //Puede haber Hold si pagó con Stripe y solo si pagó por Stripe
            if ($checkHold) {
                $checkHold->status = 2;
                $checkHold->save();

                $banker = new Banker;
                if ($checkHold->purpose === 1) {
                    $this->wallet_transaction_id = $banker->removeHold($checkHold, 2, true);
                } else {
                    $this->wallet_transaction_id = $banker->withdrawWalletFromHold($checkHold);
                }
            } elseif ($walletTransaction->exchange_related === 1) {
                $walletTransaction->status = 2;
                $walletTransaction->save();

                $banker = new Banker;
                if ($walletTransaction->purpose === 1) {
                    $this->wallet_transaction_id = $banker->rechargeWalletFromHold($walletTransaction);
                } else {
                    $this->wallet_transaction_id = $banker->withdrawWalletFromHold($walletTransaction);
                }
            }
            //Si no hay hold el $walletTransaction es en si misma una operación de Hold.
        }

        //Rejected
        if ($this->status === 2 || $this->status === 3) {
            $walletTransaction = UserWalletsTransactions::where(['id' => $this->wallet_transaction_id])
                ->first();

             if ( $walletTransaction === null) {
             
                $this->save();
                return true;
             }

             
            $checkHold         = UserWalletsTransactions::where(['wallet_transaction_related_id' => $walletTransaction->id])
                ->first();

            //Puede haber Hold si pagó con Stripe
            if ($checkHold) {
//                $checkHold->status = 3;
//                $checkHold->save();

                $banker                      = new Banker;
                $this->wallet_transaction_id = $banker->removeHold($checkHold, 3, true);
            } else {
//                $walletTransaction->status = 3;
//                $walletTransaction->save();

                $banker = new Banker;
//                if ($walletTransaction->purpose === 1) {
                $banker->removeHold($walletTransaction, 3);
//                } else {
//                    $this->wallet_transaction_id = $banker->rechargeWalletFromHold($walletTransaction);
//                }
            }
            //Si no hay hold el $walletTransaction es en si misma una operación de Hold.
        }

        //Refund - Only from User Exchange Transaction
        if ($this->status === 5) {
            $walletTransaction = UserWalletsTransactions::where(['id' => $this->wallet_transaction_id])
                ->first();
            $checkHold         = UserWalletsTransactions::where(['wallet_transaction_related_id' => $walletTransaction->id])
                ->first();
            $banker            = new Banker;

            //Weird fix invert Values from Indirect payments and Chat
            if ($walletTransaction->type === 3) {
                $checkHold         = $walletTransaction;
                $walletTransaction = UserWalletsTransactions::where(['id' => $checkHold->wallet_transaction_related_id])
                    ->first();
            }

//            Status 5 is only for Stripe processed payments.
//            if ($checkHold) { //Implícito
//                 $checkHold->status = 3; //Lo hace la funcion de removeHold
//                 $checkHold->save(); //Lo hace la funcion de removeHold
            $this->wallet_transaction_id = $banker->removeHold($checkHold, 3, true);
//            }

            //Remove hold
            $walletTransaction->status = 3;
            $walletTransaction->save();
            //Remove load
            $banker->removeHold($walletTransaction, 3);
        }

        $this->save();

        return true;
    }

    /**
     * @return float
     */
    public function getProfit(): float
    {
        return ($this->receiver_fiat_amount / $this->contact->fiat_amount) * $this->outgoing->profit;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        $statuses = [
            'Open',
            'Approved',
            'Rejected',
            'Failed',
            'In Process',
            'Refund'
        ];

        return $statuses[$this->status];
    }
}
