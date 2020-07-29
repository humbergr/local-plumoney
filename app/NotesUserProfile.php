<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class NotesUserProfile extends Model
{
    protected $table = 'notes_user_profiles'; // Nombre de la tabla
    protected $primaryKey = 'id'; // Clave primaria
    protected $fillable = [
        'msg',
        'client_id',
        'trader_id',
        'ip',
        'file'
    ];

    public function traderProfile(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'trader_id');
    }
}
