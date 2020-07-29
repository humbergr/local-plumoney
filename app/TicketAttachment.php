<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketAttachment extends Model
{
    protected $table = 'ticket_attachments';
    protected $fillable = [
        // 'id',
        'thread_id',
        'file',
        'updated_at',
        'created_at',
    ];
}
