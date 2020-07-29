<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Tier extends Model
{
    protected $table = 'tiers'; // Nombre de la tabla
    protected $primaryKey = 'id'; // Clave primaria

    protected $fillable = [
       'user_id',
       'tier_id',
       'transaction_id',
       'type_transaction',
       'traking_id',
       'in_review'
    ];

    public function tierLevel(): HasOne
    {
        return $this->hasOne(TierLevel::class, 'id', 'tier_id');
    }
}
