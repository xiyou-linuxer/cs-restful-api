<?php

/**
 * App\Models\User
 *
 * App\Models\User
 *
 * PHP version 5.5.9
 *
 * @category App\Models
 * @package  User
 * @author   Jensyn <zhangyongjun369@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://api.xiyoulinux.org
 */

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
//use Tymon\JWTAuth\Contracts\JWTSubject as JWTSubjectContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * Class: User
 *
 * @category App\Models
 * @package  User
 * @author   Jensyn <zhangyongjun369@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://api.xiyoulinux.org
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract//, JWTSubjectContract
{
    use SoftDeletes;
    use Authenticatable;
    use CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $guarded = [/*
        'sex',
        'group',
        'password',
        'phone',
        'email',
        'qq',
        'wechat',
        'blog',
        'github',
        'native',
        'job',
        'workplace',
        'grade',
        'major',*/
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'deleted_at', 'remember_token'];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

}//end class
