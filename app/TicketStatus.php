<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketStatus extends Model
{
    protected $table = 'ticket_statuses';
    protected $fillable = [
        // 'id',
        'name',
        'state',
        'message',
        'properties',
        'created_by',
        'created_at',
        'updated_at',
    ];
}
