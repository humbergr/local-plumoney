<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExchangePaymentData extends Model
{
  protected $fillable = [
      'exchange_id',
      'bank_name',
      'deposit_number',
      'account_number',
      'deposit_date',
      'attachment_name',
  ];

  public function exchange()
  {
      return $this->belongsTo('App\UserExchangeTransactions', 'exchange_id');
  }
}
