<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{

    protected $appends  = ['id_name'];
    protected $fillable = [
        'cajaobanco',
        'pais',
        'name',
        'numero',
        'tipo',
        'beneficiario',
        'ejecutivo',
        'telefono',
        'saldo',

        'saldo_usd',

        'moneda',
        'active',
        'direccion',
        'email',
        'comision'

    ];

    public function getIdNameAttribute()
    {
        return $this->id . ' ' . $this->name;
    }

    public function scopeName($query, $name)
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }
    public function usdSaldo()
    {

        $lote = Lote::where('banco_id', $this->id)->where('active', true)->get();
        $saldo = 0;
        foreach ($lote as $item) {
            $saldo += $item->saldo / $item->tasa;
            # code...
        }
        return $saldo;
    }
}
