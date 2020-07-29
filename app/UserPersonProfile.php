<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserPersonProfile extends Model
{
    /**
     * @var array
     * approval_status
     * 0 = aprobacion no requerida
     * 1 = aprobacion requerida
     * 2 = status aprobado
     * 3 = rechazado
     * 4 = Bloqueo TIER
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'mobile',
        'birth_month',
        'birth_day',
        'birth_year',
        'country',
        'state',
        'zip_code',
        'city',
        'street',
        'selfie',
        'id_confirmation',
        'status',
        'level',
        'approval_status',
        'selfie_id',
        'second_name',
        'second_last_name',
        'local_phone',
        'address_place_type',
        'address_floor',
        'address_place_number',
        'id_type',
        'id_number',
        'id_creation_date',
        'id_expiration_date',
        'mobile_verified',
        'mobile_verification_code',

        'datos_verified',
        'identity_verified',
        'id_confirmation_back',
        'gps',
    ];

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function exchangeTransactions(): HasMany
    {
        return $this->hasMany(UserExchangeTransactions::class, 'user_id');
    }

    /**
     * @return int
     */
    public function progression(): int
    {
        $progression = 0;

        if ($this->selfie) {
            $progression += 25;
        }

        if ($this->id_confirmation) {
            $progression += 25;
        }

        if ($this->street) {
            $progression += 25;
        }

        if ($this->selfie_id) {
            $progression += 25;
        }

        return $progression;
    }
}
