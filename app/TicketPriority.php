<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketPriority extends Model
{
    // protected $primaryKey = 'priority_id';
    // public $timestamps = false;
    protected $table = 'ticket_priorities';
    protected $fillable = [
        // 'id',
        'priority',
        'status',
        'priority_desc',
        'priority_color',
        'priority_urgency',
        'is_public',
        'is_default',
        'created_at',
        'updated_at',
    ];
}
