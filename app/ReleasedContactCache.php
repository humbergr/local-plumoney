<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReleasedContactCache extends Model
{
    public $primaryKey = 'contact_id';

    protected $fillable = [
        'account_name',
        'contact_id',
        'json_data',
        'status',
        'anchor_date_localbtc',
        'anchor_date_est',
        'is_selling',
        'is_buying',
        'released_localbtc',
        'released_est',
        'funded_localbtc',
        'funded_est'
    ];

    protected $casts = [
        'json_data' => 'array'
    ];
}
