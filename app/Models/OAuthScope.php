<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OauthScope extends Model
{
    protected $table = 'oauth_scopes';

    protected $primaryKey = 'level';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
      'id',
      'description',
      'level'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];
}
