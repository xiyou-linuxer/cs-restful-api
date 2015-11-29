<?php

/**
 * Modle  file for Question
 *
 * PHP version 5.5.9
 *
 * @category Model
 * @package  CS
 * @author   dreamleilei <1679211339@qq.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  GIT: 
 * @link     https://github.com/xiyou-linuxer/cs-restful-api
 */

namespace App\Models\Applications\Discuss;

use Illuminate\Database\Eloquent\Model;

/**
 * Model class for  Question
 *
 * PHP version 5.5.9
 *
 * @category Model
 * @package  CS
 * @author   dreamleilei <1679211339@qq.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     https://github.com/xiyou-linuxer/cs-restful-api
 */

class Question extends Model
{
    protected $table = 'app_discuss_question';
        /**
        * 一个question对应多个answer
        *
        *@return array Answer
        */
    public function answer() 
    {
        return $this->hasMany(
            'App\Models\Applications\Discuss\Answer',
            'question_id'
        );
    }

        /**
        * 一个question对应多个answer
        *
        *@return array User 
        */
    public function follower() 
    {
        return $this->belogsToMany('App\User');
    }
}
