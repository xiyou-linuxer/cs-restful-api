<?php

/**
 * File: app/Http/Controllers/UserController.php
 *
 * User controller
 *
 * PHP version 5.5.9
 *
 * @category App\Http\Controllers
 * @package  UserController
 * @author   Jensyn <zhangyongjun369@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://api.xiyoulinux.org
 */

namespace App\Http\Controllers\Api;

use Authorizer;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

/**
 * Class: UserController
 *
 * @category App\Http\Controllers
 * @package  HelperController
 * @author   Jensyn <zhangyongjun369@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://api.xiyoulinux.org
 */
class HelperController extends Controller
{
    /**
     * Index.
     *
     * @param Request $request request object
     *
     * @return Array
     */
    public function getAvatarUrlByEmail(Request $request)
    {
        $data = $request->only(['email']);
        $user = User::where('email', $data['email'])->get();

        $result = array();
        if (count($user)) {
          $result['success'] = true;
          $result['avatar_url'] = $user[0]['avatar'];
        } else {
          $result['success'] = false;
          $result['avatar_url'] = env('GRAVATAR_SERVER') . '/' .  md5(strtolower(trim($data['email']))) . '?d=mm&s=150';
        }

        return response()->json($result);
    }//end index()

}//end class
