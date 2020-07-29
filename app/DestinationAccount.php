<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DestinationAccount extends Model
{
    protected $fillable = [
        'name',
        'lastname',
        'phone_number',
        'email',
        'id_number',
        'country',
        'bank_name',
        'account_number',
        'account_type',
        'user_id',
        'currency',
        'birthday',
        'id_type',
        'account_type',
        'type',
        'id_origin_date',
        'id_end_date',
        'id_origin_country',
        'id_origin_state',
        'state',
        'zip_code',
        'city',
        'street',
        'aba_number',
        'dba',
        'website',
        'tax_id_number',
        'irs',
        'ein',
        'office_phone_number',
        'pago_movil'
    ];

    /**
     * ID Types
     *
     * 1 - Cédula
     * 2 - Licencia de conducir
     * 3 - Número de seguro social
     * 4 - Pasaporte
     * 5 - RIF
     *
     * Account Types
     * 1 - Cheques
     * 2 - Ahorro
     *
     * Types
     * 1 - Persona
     * 2 - Negocios
     */

    /**
     * Return country name
     * @return array
     */
    public function getCountry(): array
    {
        $apiHelper = new ApiHelper();

        return $apiHelper->getCountry($this->country);
    }

    public function getAccountType()
    {
        $accountTypesEN = [
            1 => 'Checks',
            2 => 'Savings'
        ];

        $accountTypesES = [
            1 => 'Cheques',
            2 => 'Ahorros'
        ];

        return [
            $accountTypesEN[$this->account_type] ?? null,
            $accountTypesES[$this->account_type] ?? null
        ];
    }
}
