<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasMany;

class TierLevel extends Model
{
    protected $table = 'tiers_levels'; // Nombre de la tabla
    protected $primaryKey = 'id'; // Clave primaria

    protected $fillable = [
        'name',
        'requirements'
    ];

    public function tier()
    {
        return $this->hasMany('App\Tier', 'tier_id');
    }
}
