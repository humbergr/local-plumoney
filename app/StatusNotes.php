<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;


class StatusNotes extends Model
{

    protected $table = 'status_notes'; // Nombre de la tabla
    protected $primaryKey = 'id'; // Clave primaria

    protected $fillable = [
        'status',
        'subject_id',
        'client_id',
        'transaction_id',
        'msg',
        'reply',
        'ip',
        'ip_reply',
        'reply_file',
        'trader_id',
        'is_reply'
    ];

    public function personProfile(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'client_id');
    }
    public function transaction(): HasOne
    {
        return $this->hasOne(UserExchangeTransactions::class, 'id', 'transaction_id');
    }
    
    public function traderProfile(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'trader_id');
    }

    public function subject(): HasOne
    {
        return $this->hasOne(SubjectsStatus::class, 'id', 'subject_id');
    }
}
