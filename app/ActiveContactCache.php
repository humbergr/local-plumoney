<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActiveContactCache extends Model
{
    public $primaryKey  = 'contact_id';

    protected $fillable = [
        'contact_id',
        'account_name',
        'json_data',
        'status',
        'anchor_date_localbtc',
        'anchor_date_est',
        'process_fee'
    ];

    protected $casts = [
        'json_data' => 'array'
    ];
}
