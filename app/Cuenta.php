<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    protected $appends  = ['id_name'];
    protected $fillable = [

        'name',

    ];

    public function getIdNameAttribute()
    {
        return $this->id . ' ' . $this->name;
    }

    public function scopeName($query, $name)
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }
}
