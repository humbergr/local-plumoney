<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lotedetalle extends Model
{
    protected $fillable = [
        'id',
        'lote_id',
        'lote',
        'monto',
        'currency',
        'tasa',
        'banco_id',
        'movimiento_id',
        'comentarios',
        'operacion',
        'partial',
    ];

    /**
     * The attributes that should be visible in arrays.
     *
     * @var array
     */
  /**
   * The accessors to append to the model's array form.
   *
   * @var array
   */
     

    public function scopeName($query, $name)
    {
        return $query->where('monto', 'like', '%' . $name . '%');
    }
    public function banco()
    {
        return $this->belongsTo(Banco::class);
    }
    public function lot()
    {
        return $this->belongsTo(Lote::class,'lote_id','id');
    }

    public function movimiento()
    {
        return $this->belongsTo(Movimiento::class);
    }

    public function getAnalisis()
    {
        $dineroEsperado = $this->monto /  $this->lot->tasa;
        $montoUsd = $this->monto /  $this->tasa;
        return    [ $this->monto /  $this->lot->tasa, ($this->monto /  $this->lot->tasa)-$montoUsd, $dineroEsperado ];
    }


   

    
}
