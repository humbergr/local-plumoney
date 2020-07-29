<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VesUsdPrices extends Model
{
    protected $connection = 'mysql2';
    /**
     * @var array
     */
    protected $fillable = [
        'sell_price',
        'buy_price',
        'sell_price_2',
        'buy_price_2',
        'sell_price_announces',
        'buy_price_announces',
        'sell_price_2_announces',
        'buy_price_2_announces'
    ];

    protected $casts = [
        'sell_price_announces'   => 'array',
        'buy_price_announces'    => 'array',
        'sell_price_2_announces' => 'array',
        'buy_price_2_announces'  => 'array'
    ];

    /**
     * sell_price 15 <-> 35 Range
     * buy_price 15 <-> 35 Range
     * sell_price_2 100 infinite Range
     * buy_price_2 100 infinite Range
     *  *_announces LocalBitcoins Announces related to this Exchange Rate.
     */
}
