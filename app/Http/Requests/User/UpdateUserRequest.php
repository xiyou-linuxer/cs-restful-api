<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use JWTAuth;

class UpdateUserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
       // $token = JWTAuth::parseToken();
        //$user = JWTAuth::parseToken()->authenticate();
            
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
     /*   return [
            'name'   => 'required|min:3',
            'sex'    => 'required|min:2',
            'phone'  => 'min:8',
            'mail'   => 'required|email|max:255|unique:users',
            'qq'     => 'min:5',
            'wechar' => 'min:3',
            'blog'   => 'min:6',
            'github' => 'min:7',
            'grade'  => 'required|min:3',
            'major'  => 'min:0',
            'workplace' => 'min:0',
            'job'    => 'min:0'
        ];*/
        return [];
    }
}
