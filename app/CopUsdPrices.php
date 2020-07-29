<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CopUsdPrices extends Model
{
    //
    protected $fillable = [
        'sell_price',
        'buy_price',
        'sell_price_announces',
        'buy_price_announces'
    ];

    protected $casts = [
        'sell_price_announces' => 'array',
        'buy_price_announces'  => 'array'
    ];
}
