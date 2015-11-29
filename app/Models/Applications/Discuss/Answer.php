<?php

/**
 * Modle  file for Answer
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
 * Model class for  Answer
 *
 * PHP version 5.5.9
 *
 * @category Model
 * @package  CS
 * @author   dreamleilei <1679211339@qq.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     https://github.com/xiyou-linuxer/cs-restful-api
 */
class Answer extends Model
{
    protected $table  =  'app_discuss_answer';

     /**
     *多个question属于一个answer
     *
     *@return void 
     */
    public function question() 
    {
        return $this->belongsTo('App\Models\Applications\DiscussQuestion');
    }
}
