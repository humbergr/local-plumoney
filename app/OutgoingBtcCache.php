<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutgoingBtcCache extends Model
{
    protected $fillable = [
        'contact_id',
        'account_name',
        'contactInfo',
        'walletTransaction',
        'localbtc_released_date',
        'md5',
        'status'
    ];

    protected $casts = [
        'contactInfo'       => 'array',
        'walletTransaction' => 'array'
    ];
}
