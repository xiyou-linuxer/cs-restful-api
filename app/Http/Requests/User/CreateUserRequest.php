<?php

namespace App\Http\Requests\User;

use JWTAuth;
use App\Models\CsUser;
use App\Http\Requests\Request;

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
        if($user->privilege == 0) {
            return false;
        }
        else 
            return true;
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
