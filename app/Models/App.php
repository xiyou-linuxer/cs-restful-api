<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    protected $table = 'apps';
    protected $fillable = [
      'client_id',
      'name',
      'author_id',
      'homepage',
      'logo',
      'description',
      'secret',
      'redirect_uri',
      'status'
    ];
}
