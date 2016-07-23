<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
    protected $fillable = [
        'message_id',
        'type',
        'app_id',
        'author_id',
        'receiver_id',
        'title',
        'content',
        'status'
    ];
}
