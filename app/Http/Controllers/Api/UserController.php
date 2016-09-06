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
use App\Models\News;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
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
        $keyword = isset($querys['keyword']) ? $querys['keyword'] : '';

        $users = User::distinct()->orderBy('id');

        if (isset($querys['group'])) {
            $users = $users->where('group', $querys['group']);
        }

        if (isset($querys['grade'])) {
            $users = $users->where('grade', $querys['grade']);
        }

        if (isset($querys['major'])) {
            $users = $users->where('major', $querys['major']);
        }

        if ($keyword) {
            $keyword = '%' . $keyword . '%';
            $users = $users->where('name', 'like', $keyword)
                ->orWhere('email', 'like', $keyword);
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
        $operatorId = (Integer)Authorizer::getResourceOwnerId();

        $operator = User::findOrFail($operatorId);

        if ($operator->group !== 1) {
          return response()->json(['error' => '非管理员不能添加成员信息'], 403);
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
                'grade'  => 'required|integer|between:1980,2099',
                'major'  => 'max:32',
            ]
        );

        if ($validator->fails() === true) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = new User($data);

        $user->save();


        $this->sendAppNews($operator->name . ' 刚刚创建了用户 ' . $user->name);

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
        $operatorId = (Integer)Authorizer::getResourceOwnerId();

        $user = User::findOrFail($id);
        $operator = User::findOrFail($operatorId);

        if ($user->id !== $operatorId && $operator->group !== 1) {
            return response()->json(['error' => '非本人或管理员不能修改成员资料'], 403);
        }

        $data = $request->all();

        if ($operator->group !== 1) {
            unset($data['name']);
            unset($data['group']);
        }

        if (!empty($data['email']) && ($data['email'] === $user->email)) {
            unset($data['email']);
        }

        $validator = Validator::make(
            $data,
            [
                'name'   => 'min:1|max:32',
                'sex'    => 'in:"男","女"',
                'group'    => 'in:0,1',
                'phone'  => 'alpha_num|min:6|max:20',
                'native' => 'max:128',
                'qq'     => 'alpha_num|max:12',
                'email'  => 'email|max:64|unique:users,email',
                'grade'  => 'integer|between:1980,2099',
                'major'  => 'max:32',
                'github_url' => 'url',
                'blog_url' => 'url'
            ]
        );

        if ($validator->fails() === true) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // 发送系统通知
        if (isset($data['group'])) {
            $group = (Integer)$data['group'];
            $content = '';
            if ($group === 1) {
                $content = $operator->name . ' 刚刚赋予了 ' . $user->name . ' 管理员权限。';
            } else {
                $content = $operator->name . ' 刚刚取消了 ' . $user->name . ' 的管理员权限。';
            }

            $this->sendAppNews($content);
        }

        $result = $user->update($data);

        if ((bool)$result === false) {
          return response()->json(['error' => '成员信息更新失败'], 500);
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
        $user = User::findOrFail($id);

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
        $operatorId = (Integer)Authorizer::getResourceOwnerId();

        $operator = User::findOrFail($operatorId);

        if ($operator->group !== 1) {
          return response()->json(['error' => '非管理员不能删除成员信息'], 403);
        }

        $user = User::findOrFail($id);

        $this->sendAppNews($operator->name . ' 刚刚删除了用户 ' . $user->name);

        $user->delete();

        return response('', 204);

    }//end destory()

    private function sendAppNews($content)
    {
        $clientId = Authorizer::getClientId();
        $operatorId = (Integer)Authorizer::getResourceOwnerId();

        $news = new News([
            'type' => 1,
            'topic' => '系统通知',
            'app_id' => $clientId,
            'author_id' => $operatorId,
            'content' => $content
        ]);
        $news->save();
    }
}//end class
