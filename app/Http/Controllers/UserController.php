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

use Authorizer;
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
        $keyword = isset($querys['keyword']) ? $querys['keyword'] : '';

        $users = User::distinct()->orderBy('id');

        if ($keyword) {
            $keyword = '%' . $keyword . '%';
            $users = $users->where('name', 'like', $keyword)
                ->orWhere('email', 'like', $keyword);
        }

        if ($status) {
            $users = $users->where('name', $status);
        }

        $page = 1;
        if (isset($querys['page']) && is_numeric($querys['page'])) {
            $page = (Integer)$querys['page'];
        }

        $pageSize = 20;
        if (isset($querys['per_page']) && is_numeric($querys['per_page'])) {
            $pageSize = (Integer)$querys['per_page'];
        }

        $totalCount = count($users->get());
        $skip = $pageSize * ($page - 1);
        $users = $users->skip($skip)->take($pageSize)->get();

        $result = array(
          'page'        => $page,
          'per_page'    => $pageSize,
          'total_count' => $totalCount,
          'data'        => $users
        );

        return response()->json($result);
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
        $operatorId = Authorizer::getResourceOwnerId();

        $operator = User::find($operatorId);

        if ($operator->group != 1) {
          return response()->json(['error' => '非管理员不能添加用户'], 422);
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
        $operatorId = Authorizer::getResourceOwnerId();

        $user = User::find($id);

        if (empty($user) === true) {
          return response()->json(['error' => 'user not found'], 404);
        }


        if ($user->id != $operatorId) {
            return response()->json(['error' => '非本人不能修改用户资料'], 422);
        }

        $data = $request->all();

        if (!empty($data['email']) && ($data['email'] === $user->email)) {
            unset($data['email']);
        }

        $validator = Validator::make(
            $data,
            [
                'name'   => 'min:1|max:32',
                'sex'    => 'in:"男","女"',
                'phone'  => 'alpha_num|min:6|max:20',
                'native' => 'max:128',
                'qq'     => 'alpha_num|max:12',
                'email'  => 'email|max:64|unique:users,email',
                'grade'  => 'integer|between:1980,2099',
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
        $operatorId = Authorizer::getResourceOwnerId();

        $operator = User::find($operatorId);

        if ($operator->group != 1) {
          return response()->json(['error' => '非管理员不能删除用户'], 422);
        }

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
