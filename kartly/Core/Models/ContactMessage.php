<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{

    protected $table = "contact_messages";

    protected $fillable = ['name', 'email', 'subject', 'message'];

    protected $casts = [
        'read_at' => 'datetime',
    ];
}