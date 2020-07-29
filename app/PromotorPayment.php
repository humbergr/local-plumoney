<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromotorPayment extends Model
{
    public function payer_admin()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    public function code()
    {
        return $this->belongsTo(UserRegistrationCode::class, 'code_id');
    }
}
