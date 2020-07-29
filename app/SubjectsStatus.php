<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubjectsStatus extends Model
{

    protected $table = 'subjects_status'; // Nombre de la tabla
    protected $primaryKey = 'id'; // Clave primaria
    protected $fillable = [
        'status',
        'subject'
    ];
}
