<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupportAgents extends Model
{
    protected $table = 'support_agents';
    protected $fillable = [
        'dept_id',
        'user_id',
        'created_by',
        'is_manager',
        'is_active',
        'created_at',
        'updated_at',
    ];
}