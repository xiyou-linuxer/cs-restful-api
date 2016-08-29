<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OauthClientScope extends Model
{
    protected $table = 'oauth_client_scopes';
    protected $fillable = [
      'client_id',
      'scope_id'
    ];
}
