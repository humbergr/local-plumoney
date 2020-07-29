<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserWalletsTransactions extends Model
{
    protected $fillable = [
        'wallet_id',
        'user_id',
        'type',
        'status',
        'approved_at',
        'approved_by',
        'amount',
        'currency',
        'origin_foundation',
        'withdraw_mode',
        'metadata',
        'rejected_at',
        'rejected_by',
        'wallet_transaction_related_id',
        'purpose',
        'is_payed',
        'payed_at',
        'payed_by',
        'is_revised',
        'forced_transfer',
        'previous_assignations',
        'forced_transfer_dates',
        'fee_at_moment',
        'failed_at',
        'failed_by',
        'tracking_id',
        'payment_support',
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
        'contact_id',
        'sender_fiat',
        'sender_fiat_amount',
        'receiver_fiat',
        'receiver_fiat_amount',
        'payment_way',
        'payment_source',
        'exchange_related',
        'bank_move'
    ];

    protected $casts = [
        'metadata' => 'array',
        'stripe_data'           => 'array',
        'trader_info'           => 'array',
        'previous_assignations' => 'array',
        'forced_transfer_dates' => 'array'
    ];

    /**
     * wallet_transaction_related_id es solo para transacciones de intercambio o envío de dinero
     * Usualmente será un hold que está relaciónado con una recarga específica que deberá a posterior ser
     * acreditado al cliente o cobrado.
     **/

    /**
     * Purpose
     * Only for Hold transactions and Transfers
     * 1 = For incoming
     * 2 = For outgoing
     */

    /**
     * Status
     * 0 = Waiting
     * 5 = Refund
     *
     * For Hold (type 3)
     * 1 = Approved (is in hold)
     * 2 = Completed (Check **)
     * 3 = failed or rejected
     * 6 = In Process
     *  ** If the purpose was 1: Wallet increased
     *  ** If the purpose was 2: Wallet decreased
     *
     * For not Hold transactions
     * 1 = Approved
     * 4 = Rejected
     * 3 = Failed
     */

    /**
     * Types
     *
     * 1 = Add Founds
     * 2 = Withdraw Founds
     * 3 = Hold Founds
     * 4 = Transfer between wallets
     */

    /**
     * origin_foundation
     *
     * 1 = Cards by Stripe
     * 2 = Zelle
     * 3 = Venmo
     * 4 = CashApp
     * 5 = Payoneer
     * 6 = PopMoney
     * 7 = Pandco
     * 8 = FromWallet
     * 9 = Cash
     */

    /**
     * withdraw_mode
     *
     * 1 = Normal exchange transaction
     * 2 = Withdraw to bank account
     * 3 = Withdraw to BTC
     * 4 = Withdraw to Employee
     * 5 = Withdraw to service
     * 6 = Withdraw to Other Wallet
     */

    public function payment()
    {
        return $this->hasMany('App\ExchangePaymentData', 'wallet_transaction_id');
    }

    /**
     * @return HasOne
     */
    public function relatedTransaction(): HasOne
    {
        return $this->hasOne(UserExchangeTransactions::class, 'tracking_id', 'tracking_id')
            ->with('destinationAccount');
    }

    /**
     * @return HasOne
     */
    public function relatedWalletTransaction(): HasOne
    {
        return $this->hasOne(self::class, 'id', 'wallet_transaction_related_id');
    }

    /**
     * @return HasOne
     */
    public function userAccount(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function userPersonProfile(): HasOne
    {
        return $this->hasOne(UserPersonProfile::class, 'user_id', 'user_id');
    }

    /**
     * @return HasOne
     */
    public function masterAccount(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'attended_by');
    }

    /**
     * @return HasOne
     */
    public function Operator(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'attended_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function merchant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WalletTransactionMessage::class, 'order_id');
    }

    /**
     * @return HasOne
     */
    public function destinationAccount(): HasOne
    {
        return $this->hasOne(DestinationAccount::class, 'id', 'destination_account');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function trader(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\User', 'trader_id');
    }

    public function changeStatus()
    {

    }

    public function getUser()
    {

    }

    public function getWallet()
    {

    }
}
