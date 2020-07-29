<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionOrder extends Model
{
    protected $fillable = [
      'sender',
      'receiver',
      'to_send',
      'to_receive',
      'spending_date',
      'user_id',
      'is_active'
    ];
}
