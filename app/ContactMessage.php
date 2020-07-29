<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'contact_id',
        'msg',
        'sender',
        'msg_number',
        'lbc_created_at',
        'attachment_name'
    ];
}
