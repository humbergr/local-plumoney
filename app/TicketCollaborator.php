<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketCollaborator extends Model
{
    protected $table = 'ticket_collaborators';
    protected $fillable = [
        // 'id',
        'isactive',
        'ticket_id',
        'user_id',
        'role',
        'created_at',
        'updated_at',
    ];
}
