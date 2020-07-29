<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WalletTransactionsSentCache extends Model
{
    public $primaryKey  = 'fingerprint';

    protected $fillable = [
        'contact_id',
        'txid',
        'account_name',
        'json_data',
        'fee',
        'status',
        'anchor_date_localbtc',
        'anchor_date_est'
    ];

    protected $casts = [
        'json_data' => 'array'
    ];
}
