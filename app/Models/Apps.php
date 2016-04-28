<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apps extends Model
{
    protected $table = 'apps';
    protected $fillable = ['name','description','status','redirect_url','key'];
}
