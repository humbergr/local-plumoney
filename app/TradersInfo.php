<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TradersInfo extends Model
{
    protected $fillable = ['completed_trades', 'cancelled_trades', 'score'];
}
