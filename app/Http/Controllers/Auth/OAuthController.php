<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Authorizer;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OauthController extends Controller
{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('csrf', ['only' => [
            'postAuthorize'
        ]]);

        $this->middleware('check-authorization-params', ['only' => [
            'getAuthorize',
            'postAuthorize',
        ]]);

        $this->middleware('auth', ['only' => [
            'getAuthorize',
            'postAuthorize',
        ]]);
    }

    public function getAuthorize() {
        $authParams = Authorizer::getAuthCodeRequestParams();
        $formParams = array_except($authParams,'client');
        $formParams['client_id'] = $authParams['client']->getId();
        $formParams['scope'] = implode(
            config('oauth2.scope_delimiter'),
            array_map(
                function ($scope) {
                    return $scope->getId();
                },
                $authParams['scopes']
            )
        );

        return view(
            'auth.oauth',
            [
                'params' => $formParams,
                'client' => $authParams['client']
            ]
        );
    }

    public function postAuthorize(Request $request) {
        $params = Authorizer::getAuthCodeRequestParams();
        $params['user_id'] = Auth::user()->id;
        $redirectUri = '/';

        // If the user has allowed the client to access its data,
        // redirect back to the client with an auth code.
        if ($request->has('approve')) {
            $redirectUri = Authorizer::issueAuthCode(
                'user',
                $params['user_id'],
                $params
            );
        }

        // If the user has denied the client to access its data,
        // redirect back to the client with an error message.
        if ($request->has('deny')) {
            $redirectUri = Authorizer::authCodeRequestDeniedRedirectUri();
        }

        return redirect($redirectUri);
    }

    public function accessToken() {
        return response()->json(Authorizer::issueAccessToken(), 201);
    }

    public function getUser () {
        $id = Authorizer::getResourceOwnerId();

        $user = User::find($id);

        if (empty($user) === true) {
            return response()->json(['error' => 'user not found'], 404);
        }

        return response()->json($user);
    }
}
