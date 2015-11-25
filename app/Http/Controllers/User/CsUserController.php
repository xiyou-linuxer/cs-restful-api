<?php
/**
 *Descrip the Contorller class for Cs_User
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

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Cs_User;

/**
 *The Controller class for Cs_User
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
        $user_info_all = Cs_User::all();
        return $user_info_all->toJson();
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
        $user = new Cs_User;
        $user->name = $request->name;
        $user->password = $request->password;
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
    }

    /**
      *Created resource in storage.
      *
      * @param \Illuminate\Http\Request $request used for store
      *
      * @return \Illuminate\Http\Response
      */
    public function store(Request $request)
    {
        
    }

    /**
     * Reset password.
     *
     * @param \Illuminate\Http\Request $request used for store
     *
     * @return \Illuminate\Http\Response
     */
    public function resetpd(Request $request)
    {
        $user = Cs_User::find($request->get('id'));

        $user->update(['password' => $request->get('password')]);
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
        $user_info = Cs_User::findOrFail($id);
        return $user_info->toJson();
    }

    /**
     * Get gravatar pic.
     *
     * @param int $id used for getting gravatar pic
     *
     * @return \Illuminate\Http\Response
     */
    public function gravatar($id)
    {
        $mail = Cs_User::find($id)->mail;
        $size = 150;
        $gra_url = "http://gravatar.duoshuo.com/avatar/"
            . md5(strtolower(trim($mail))) . "?d=mm&s=" . $size;
        return $gra_url;
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
        //
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
        $user = Cs_User::find($id);
        $user->update(
            $request->except('password', 'sex', 'name', 'grade')
        );
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
        //
    }
}
