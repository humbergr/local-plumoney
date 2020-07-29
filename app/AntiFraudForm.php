<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AntiFraudForm extends Model
{
    protected $fillable = ['type', 'email', 'fullname', 'phone', 'id_document', 'id_document_selfie', 'location', 'contact_id', 'token', 'form_data'];

    protected $casts = ['form_data' => 'array'];
}
