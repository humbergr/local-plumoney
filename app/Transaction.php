<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\OutgoingBtc;
use App\IncomingBtc;

class Transaction extends Model
{
    protected $fillable = [
        'bank_name',
        'transaction_id',
        'amount',
        'amount_btc',
        'fee_btc',
        'msg',
        'currency',
        'type',
        'json_data',
        'usd_price',
        'released_date',
        'localbtc_released_date',
        'error',
        'account_name',
        'is_manual',
        'customer_name',
        'customer_rate',
        'bank_move',
        'user_exchange_transaction_id',
        'salida',
        'banco_id',
        'exchange',
        'fee_bank'
    ];

    protected $casts = ['json_data' => 'array'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function incomingbtc(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(IncomingBtc::class, 'transaction_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function outgoingbtc(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(OutgoingBtc::class, 'transaction_id');
    }

    public function banco()
    {
        return $this->belongsTo('App\Banco');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
