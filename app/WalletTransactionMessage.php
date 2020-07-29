<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WalletTransactionMessage extends Model
{
    protected $fillable = [
        'sender_id',
        'msg',
        'attachment_name',
        'order_id',
        'type',
        'json_data'
    ];

    protected $casts = [
        'json_data' => 'array'
    ];

    public function transaction()
    {
        return $this->belongsTo('App\UserWalletsTransactions', 'order_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userAccount(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'id', 'sender_id');
    }
}
