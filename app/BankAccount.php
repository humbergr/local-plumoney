<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'order_id',
        'wallet_transaction_id',
        'bank_name',
        'account_type',
        'account_number',
        'account_owner',
        'id_number',
        'confirmation_file',
        'limit_date',
        'payed',
        'payed_at',
        'failed',
        'fiat_amount',
        'failed_at',
        'canceled',
        'canceled_at'
    ];
}
