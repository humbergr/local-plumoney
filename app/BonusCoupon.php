<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BonusCoupon extends Model
{
    protected $fillable = [
        'merchant_id',
        'transaction_id',
        'receiver_id_document',
        'receiver_account',
        'is_used',
    ];

    public function transaction()
    {
        return $this->belongsTo('App\UserExchangeTransactions', 'transaction_id');
    }
}
