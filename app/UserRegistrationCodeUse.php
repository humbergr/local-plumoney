<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRegistrationCodeUse extends Model
{
    protected $table = 'user_registration_code_uses';

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function code()
    {
        return $this->belongsTo('App\UserRegistrationCode', 'code_id');
    }
}