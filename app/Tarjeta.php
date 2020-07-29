<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tarjeta extends Model
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['numero', 'cvc', 'expiry', 'active','verified', 'foto','nombre', 'comentario'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
