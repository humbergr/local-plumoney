<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserCompanyProfile extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'tax_number',
        'mobile',
        'office_phone',
        'website',
        'email',
        'country',
        'state',
        'zip_code',
        'city',
        'street',
        'shareholders',
        'logo',
        'id_confirmation',
        'status',
        'level',
        'public_service_doc',
        'tax_id_doc',
        'address_place_type',
        'address_floor',
        'address_place_number',
    ];

    protected $casts = ['shareholders' => 'array'];

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id');
    }
}
