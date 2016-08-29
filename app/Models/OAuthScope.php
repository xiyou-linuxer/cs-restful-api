<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OauthScope extends Model
{
    protected $table = 'oauth_scopes';
    protected $fillable = [
      'id',
      'description'
    ];
}
