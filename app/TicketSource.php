<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketSource extends Model
{
    // public $timestamps = false;
    protected $table = 'ticket_sources';
    protected $fillable = [
        'name',
        'created_by',
        'created_at',
        'updated_at',
    ];
}
