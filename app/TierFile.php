<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TierFile extends Model
{
    
    protected $table = 'tier_files'; // Nombre de la tabla
    protected $primaryKey = 'id'; // Clave primaria

    protected $fillable = [
       'requeriments_tier',
       'user_id',
       'tier_id'
    ];
}
