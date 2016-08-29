<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class App extends Model
{
    use SoftDeletes;

    protected $table = 'apps';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
      'client_id',
      'name',
      'author_id',
      'homepage_url',
      'logo_url',
      'description',
      'secret',
      'redirect_uri',
      'status',
      'scopes'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['deleted_at'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
