<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocalContact extends Model
{
    protected $fillable = [
        'ad_id',
        'trader_id',
        'local_contact_id',
        'fiat_amount',
        'amount_btc',
        'rate',
        'currency',
        'ad_username',
        'trade_type',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function trader(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\User', 'trader_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exchanges(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\UserExchangeTransactions', 'contact_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function outgoing(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne('App\OutgoingBtc', 'contact_id', 'local_contact_id');
    }
}
