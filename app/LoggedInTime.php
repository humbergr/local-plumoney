<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoggedInTime extends Model
{
    protected $fillable = ['user_id', 'time'];
}
