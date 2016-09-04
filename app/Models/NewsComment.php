<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsComment extends Model
{
      use SoftDeletes;

      protected $table = 'news_comments';

      /**
       * The attributes that are mass assignable.
       *
       * @var array
       */

      protected $fillable = [
          'app_id',
          'author_id',
          'news_id',
          'content'
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
