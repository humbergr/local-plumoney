<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ErrorTransaction extends Model
{
    protected $fillable = [
        'transaction_id',
        'amount_btc',
        'json_data',
        'was_solved',
        'was_verified',
        'not_funds',
        'not_usd_price',
        'released_date',
        'localbtc_released_date',
        'account_name'
    ];

    protected $casts = ['json_data' => 'array'];
}
