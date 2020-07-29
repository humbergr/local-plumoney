<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupportOnlyUsers extends Model
{
    protected $table = 'support_only_users';
    protected $fillable = [
        // 'id',
        'name',
        'email',
        'user_id',
        'created_at',
        'updated_at',
    ];
}
