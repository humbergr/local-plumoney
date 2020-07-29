<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    protected $fillable = [
        'id',
        'lote',
        'saldo',
        'monto',
        'currency',
        'banco_id',
        'movimiento_id',
         
    ];

    public function scopeName($query, $name)
    {
        return $query->where('monto', 'like', '%' . $name . '%');
    }
    public function banco()
    {
        return $this->belongsTo(Banco::class);
    }

    public function movimiento()
    {
        return $this->belongsTo(Movimiento::class);
    }

    public function lotes()
    {
        return $this->hasMany('App\Lotedetalle', 'lote_id', 'id');
    }

    public function profit()
    {
        $number=0;

        if ($this->active == false) {
            return 0;
        }
        foreach ($this->lotes as $key => $item) {
            if ($key > 0 && $item->movimiento->cuenta_id !== 9 ) {
                
               
                $number +=  ($item->monto * -1) / $item->tasa ;
            }
        }
        return round($number - $this->monto / $this->tasa,2);
    }
   

}
