<?php
/**
 *Descrip the model of Cs_User
 *
 * PHP version 5.6
 *
 * @category PHP
 * @package  PHP_Laveral
 * @author   teddyliao <sxliao@foxmail.com>
 * @license  http://xiyoulinux.org BSD Licence
 * @link     http://cs.xiyoulinux.org
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 *The class for CsUser
 *
 * PHP version 5.6
 *
 * @category PHP
 * @package  PHP_Laveral
 * @author   teddyliao <sxliao@foxmail.com>
 * @license  http://xiyoulinux.org BSD Licence
 * @link     http://cs.xiyoulinux.org
 */
class CsUser extends Model
{
    protected $table = 'cs_user';
    protected $fillable = ['privilege','password',
        'phone','mail','qq','blog','wechat','major','workplace','job','github','sex','native','grade'];

    protected $hidden = ['password'];

}
