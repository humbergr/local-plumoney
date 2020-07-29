<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GenericPaymentsMethods extends Model
{
    protected $fillable = [
        'title',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];
}
