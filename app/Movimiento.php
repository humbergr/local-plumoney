<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{

    protected $fillable = [
        'user_id',
        'banco_id',
        'cuenta_id',
        'tipo',
        'monto',
        'moneda',
        'descripcion',
        'operacion',
        'tasa',
        'monto_usd',
        'capture',
        'emision',
        'lote',
        'comision',
        'monto_bruto',
        'doc_id'
 



    ];

    public function scopeName($query, $name)
    {
        return $query->where('banco_id', 'like', '%' . $name . '%');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function banco()
    {
        return $this->belongsTo(Banco::class);
    }

    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class);
    }

    public function doc()
    {
        return $this->belongsTo(UserExchangeTransactions::class, 'doc_id', 'id');
    }

    public function inc()
    {
        return $this->belongsTo(Transaction::class, 'doc_id', 'id');
    }
}
