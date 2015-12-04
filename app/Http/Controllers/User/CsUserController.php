<?php
/**
 *Descrip the Contorller class for CsUser
 *
 * PHP version 5.6
 *
 * @category PHP
 * @package  PHP_Laveral
 * @author   teddyliao <sxliao@foxmail.com>
 * @license  http://xiyoulinux.org BSD Licence
 * @link     http://cs.xiyoulinux.org
 */

namespace App\Http\Controllers\User;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;
use App\Http\Controllers\Controller;
use App\Models\CsUser;
use App\Models\UserOnline;
use JWTAuth;

/**
 *The Controller class for CsUser
 *
 * PHP version 5.6
 *
 * @category PHP
 * @package  PHP_Laveral
 * @author   teddyliao <sxliao@foxmail.com>
 * @license  http://xiyoulinux.org BSD Licence
 * @link     http://cs.xiyoulinux.org
 */

class CsUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_infos = CsUser::all()->toArray();
        $size = 150;
        foreach($user_infos as &$user_info) {
            $user_info['avatar'] = "http://gravatar.duoshuo.com/avatar/"
                . md5(strtolower(trim($user_info['mail']))) . "?d=mm&s=" . $size;
        }

        return new Response(json_encode($user_infos),200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request used for create new user
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    }

    /**
      *Created resource in storage.
      *
      * @param \Illuminate\Http\Request $request used for store
      *
      * @return \Illuminate\Http\Response
      */
    public function store(Requests\User\CreateUserRequest $request)
    {
        $rules = [  
            'name'   => 'required|min:1',
            'sex'    => 'required|min:1',
            'phone'  => 'min:8',
            'mail'   => 'required|email|max:255|unique:cs_user',
            'qq'     => 'min:5',
            'wechar' => 'min:3',
            'blog'   => 'min:6',
            'github' => 'min:7',
            'grade'  => 'required|min:2',
            'major'  => 'min:0',
            'workplace' => 'min:0',
            'job'    => 'min:0'
        ];
    
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
        
            return new Response(json_encode($validator->errors()),422);
        }

        $user = new CsUser;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->sex = $request->sex;
        $user->phone = $request->phone;
        $user->mail = $request->mail;
        $user->qq = $request->qq;
        $user->wechat = $request->wechat;
        $user->blog = $request->blog;
        $user->github = $request->github;
        $user->native = $request->native;
        $user->grade = $request->grade;
        $user->major = $request->major;
        $user->workplace = $request->workplace;
        $user->job = $request->job;

        $user->save();
        $size = 150;
        $user->avatar = "http://gravatar.duoshuo.com/avatar/"
            . md5(strtolower(trim($user->mail))) . "?d=mm&s=" . $size;
        return new Response(json_encode($user), 201);
        
    }

    /**
      *Created resource in storage.
      *
      * @param \Illuminate\Http\Request $request used for store
      *
      * @return \Illuminate\Http\Response
      */
    public function online()
    {
        return 'hello world';
    }

    /**
     * Reset password.
     *
     * @param \Illuminate\Http\Request $request used for store
     *
     * @param int $id used for update
     *
     * @return \Illuminate\Http\Response
     */
    public function resetpd(Request $request, $id)
    {
        $user = CsUser::find($request->get('id'));
        $user->password = $request->get('password');
        $user->save();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id used for show
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $size = 150;
        $user_info = CsUser::findOrFail($id)->toArray();
        $user_info['avatar'] = "http://gravatar.duoshuo.com/avatar/"
            . md5(strtolower(trim($user_info['mail']))) . "?d=mm&s=" . $size;
        return new Response(json_encode($user_info),200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id used for edit
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $size = 150;
        $user_info = CsUser::findOrFail($id)->toArray();
        $user_info['avatar'] = "http://gravatar.duoshuo.com/avatar/"
            . md5(strtolower(trim($user_info['mail']))) . "?d=mm&s=" . $size;
        return new Response(json_encode($user_info),200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request used for update
     * @param int                      $id      used for update
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //access judged
        $token = JWTAuth::parseToken();
        $user = JWTAuth::parseToken()->authenticate();
        if($user->id != $id) {
            return new Response(json_encode(['error' => 'Access denied guys!']),403);
        }
        
        //input rules
        $rules = [
            'name'   => 'min:3',
            'sex'    => 'min:1',
            'phone'  => 'min:8',
            'mail'   => 'email|max:255|unique:cs_user',
            'qq'     => 'min:5',
            'wechar' => 'min:3',
            'blog'   => 'min:6',
            'github' => 'min:7',
            'grade'  => 'min:1',
            'major'  => 'min:0',
            'workplace' => 'min:0',
            'job'    => 'min:0'
        ];

        $validator = Validator::make($request->all(),$rules);
        
        if ($validator->fails()) {
        
            return new  Response(json_encode($validator->errors()),422);
        }
        if(is_null($user = CsUser::find($id))) {
            return new Response(json_encode(['error' => 'User not exist!']),    404);
        }
        $user->update($request->except('id'));

        $size = 150;
        $user_update = CsUser::findOrFail($id)->toArray();
        $user_update['avatar'] = "http://gravatar.duoshuo.com/avatar/"
            . md5(strtolower(trim($user_update['mail']))) . "?d=mm&s=" . $size;
        return new Response(json_encode($user_update),201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id used for destroy
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        //access judged
        $token = JWTAuth::parseToken();
        $user = JWTAuth::parseToken()->authenticate();
        if($user->privilege != 1) {
            return new Response(json_encode(['error' => 'Access denied guys!']),403);
        }

        $user = CsUser::findOrFail($id);
        $user->delete();
        return new Response('',204);
    }

}
