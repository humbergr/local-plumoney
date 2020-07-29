<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubjectsStatusIncoming extends Model
{

    protected $table = 'status_subjects_incoming'; // Nombre de la tabla
    protected $primaryKey = 'id'; // Clave primaria
    protected $fillable = [
        'status',
        'subject'
    ];
}
