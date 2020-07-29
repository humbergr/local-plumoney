<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departments extends Model
{
    protected $table = 'departments';
    protected $fillable = [
        'name',
        'created_by',
        'is_active',
        'is_public',
        'created_at',
        'updated_at',
    ];
}
