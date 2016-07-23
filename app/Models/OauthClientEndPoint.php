<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OauthClientEndPoint extends Model
{
    protected $table = 'oauth_client_endpoints';
    protected $fillable = [
      'client_id',
      'redirect_uri'
    ];
}
