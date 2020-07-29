<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GirosPrice extends Model
{
    protected $fillable = [
        'price_id',
        'url',
        'currency',
        'iso',
        'country',
        'payment_method',
        'json_data'
    ];

    protected $casts = [
        'json_data'  => 'array',
    ];
}
