<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutgoingBtc extends Model
{
    protected $fillable = [
        'amount_btc',
        'usd_price',
        'released_date',
        'localbtc_released_date',
        'fee_btc',
        'total_btc',
        'profit',
        'transaction_id',
        'account_name',
        'contact_id',
        'incoming_btcs_used',
        'category'
    ];

    protected $casts = [
        'incoming_btcs_used' => 'array'
    ];

    public $categories = [
        1 => 'Sale',
        2 => 'Employee\'s Salary',
        3 => 'Operational Expenses',
        4 => 'Regular Payments',
        5 => 'Internal Inventory Movement',
        6 => 'Other Expenses'
    ];

    public function incomings()
    {
        return $this->belongsToMany(
            'App\IncomingBtc',
            'incoming_btc_outgoing_btc',
            'outgoing_id',
            'incoming_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function transaction(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Transaction::class, 'id', 'transaction_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     */
    public function contact(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\LocalContact', 'local_contact_id', 'contact_id');
    }
}
