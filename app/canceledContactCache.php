<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class canceledContactCache extends Model
{
    public $primaryKey  = 'contact_id';

    protected $fillable = [
        'account_name',
        'contact_id',
        'json_data',
        'status',
        'anchor_date_localbtc',
        'anchor_date_est',
        'is_selling',
        'is_buying'
    ];

    protected $casts = [
        'json_data' => 'array'
    ];
}
