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

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

/**
 * Class: UserController
 *
 * @category App\Http\Controllers
 * @package  UserController
 * @author   Jensyn <zhangyongjun369@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://api.xiyoulinux.org
 */
class UserController extends Controller
{
    /**
     * Index.
     *
     * @param Request $request request object
     *
     * @return Array
     */
    public function index(Request $request)
    {
        $querys = $request->query();
        $status = isset($querys['status']) ? $querys['status'] : '';
        $keywords = isset($querys['keywords']) ? $querys['keywords'] : '';

        $result = User::distinct()->orderBy('id');

        if ($keywords) {
            $keywords = $keywords . '%';
            $result = $result->where('name', 'like', $keywords)
                ->orWhere('email', 'like', $keywords);
        }

        if ($status) {
            $result = $result->where('name', $status);
        }

        $users = $result->get();

        return response()->json($users);

    }//end index()


    /**
     * Create.
     *
     * @param Request $request request object
     *
     * @return Object
     */
    public function create(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser();
        return response()->json(compact('user'));
        var_dump($user);

        $data = $request->only(
            'name',
            'sex',
            'phone',
            'native',
            'qq',
            'email',
            'grade',
            'major'
        );

        $validator = Validator::make(
            $data,
            [
                'name'   => 'required|max:32',
                'sex'    => 'required|in:"男","女"',
                'phone'  => 'alpha_num|max:20',
                'native' => 'required|max:128',
                'qq'     => 'alpha_num|max:12',
                'email'  => 'required|email|max:64|unique:users,email',
                'grade'  => 'required|integer|between:2006,2099',
                'major'  => 'max:32',
            ]
        );

        if ($validator->fails() === true) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = new User($data);

        $user->save();

        return response()->json($user, 201);

    }//end create()


    /**
     * Update.
     *
     * @param Request $request request object
     *
     * @return Object
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (empty($user) === true) {
          return response()->json(['error' => 'user not found'], 404);
        }

        $data = $request->only(
            'name',
            'sex',
            'phone',
            'native',
            'qq',
            'email',
            'grade',
            'major'
        );

        $validator = Validator::make(
            $data,
            [
                'name'   => 'required|max:32',
                'sex'    => 'required|in:"男","女"',
                'phone'  => 'alpha_num|max:20',
                'native' => 'required|max:128',
                'qq'     => 'alpha_num|max:12',
                'email'  => 'required|email|max:64|unique:users,email',
                'grade'  => 'required|integer|between:2006,2099',
                'major'  => 'max:32',
            ]
        );

        if ($validator->fails() === true) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $result = $user->update($data);

        if ((bool)$result === false) {
          return response()->json(['error' => 'user update failed'], 422);
        }

        return response()->json($user, 201);

    }//end create()


    /**
     * Show.
     *
     * @param int $id user id
     *
     * @return Object
     */
    public function show($id)
    {
        $user = User::find($id);

        if (empty($user) === true) {
            return response()->json(['error' => 'user not found'], 404);
        }

        return response()->json($user);

    }//end show()


    /**
     * Destory.
     *
     * @param int $id user id
     *
     * @return void
     */
    public function destory($id)
    {
        $user = User::find($id);

        if (empty($user) === true) {
            return response()->json(['error' => 'user not found'], 404);
        }

        $result = $user->delete();

        if ((bool)$result === false) {
            return response()->json(['error' => 'user delete failed'], 502);
        }

        return response('', 204);

    }//end destory()

}//end class
