<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use SoftDeletes;

    protected $table = 'news';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['type', 'author_id', 'app_id', 'topic', 'link_url', 'content'];

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

    public function getComments()
    {
        return $this->hasMany('App\Models\NewsComment', 'news_id', 'id');
    }

    public function getFavors()
    {
        return $this->hasMany('App\Models\NewsFavor', 'news_id', 'id');
    }

}
