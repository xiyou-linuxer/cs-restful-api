<?php
/**
 *Descrip the request of CreateUser
 *
 * PHP version 5.6
 *
 * @category PHP
 * @package  PHP_Laveral
 * @author   teddyliao <sxliao@foxmail.com>
 * @license  http://xiyoulinux.org BSD Licence
 * @link     http://cs.xiyoulinux.org
 */
namespace App\Http\Requests\User;

use JWTAuth;
use App\Models\CsUser;
use App\Http\Requests\Request;
/**
 *The request for CreateUser
 *
 * PHP version 5.6
 *
 * @category PHP
 * @package  PHP_Laveral
 * @author   teddyliao <sxliao@foxmail.com>
 * @license  http://xiyoulinux.org BSD Licence
 * @link     http://cs.xiyoulinux.org
 */

class CreateUserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $token = JWTAuth::parseToken();
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->privilege == 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }
}
