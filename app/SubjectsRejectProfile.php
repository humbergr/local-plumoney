<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubjectsRejectProfile extends Model
{
    //
    protected $table = 'subjects_reject_profiles'; // Nombre de la tabla
    protected $primaryKey = 'id'; // Clave primaria
    protected $fillable = [
        'status',
        'subject',
        'details'
    ];
}
