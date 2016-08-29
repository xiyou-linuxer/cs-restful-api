<?php

namespace App\Http\Controllers\Api;


use Hash;
use Authorizer;
use App\Models\App;
use App\Models\User;
use App\Models\OAuthScope;
use App\Models\OAuthClient;
use App\Models\OAuthClientEndPoint;
use App\Models\OAuthClientScope;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AppController extends Controller
{
    public function index(Request $request)
    {
        $operatorId = (Integer)Authorizer::getResourceOwnerId();

        $querys = $request->query();
        $keyword = isset($querys['keyword']) ? $querys['keyword'] : '';

        $apps = App::distinct()->orderBy('id');

        if ($keyword) {
            $keyword = '%' . $keyword . '%';
            $apps = $apps->where('name', 'like', $keyword);
        }

        if (isset($querys['author_id'])) {
            $author_id = (Integer)$querys['author_id'];
            $apps = $apps->where('author_id', $author_id);
        }

        if (isset($querys['status'])) {
            $apps = $apps->where('status', $querys['status']);
        }

        $page = 1;
        if (isset($querys['page']) && is_numeric($querys['page'])) {
            $page = (Integer)$querys['page'];
        }

        $pageSize = 20;
        if (isset($querys['per_page']) && is_numeric($querys['per_page'])) {
            $pageSize = (Integer)$querys['per_page'];
        }

        $totalCount = count($apps->get());
        $skip = $pageSize * ($page - 1);
        $apps = $apps->skip($skip)->take($pageSize)->get();

        foreach ($apps as $app) {
            $app = $this->unfoldAppInfo($app);
        }

        $result = array(
            'page'        => $page,
            'per_page'    => $pageSize,
            'total_count' => $totalCount,
            'data'        => $apps
        );
        return response()->json($result);
    }

    public function create(Request $request)
    {
        $operatorId = (Integer)Authorizer::getResourceOwnerId();

        $data = $request->only([
            'client_id',
            'name',
            'homepage_url',
            'logo_url',
            'description',
            'redirect_uri',
            'scopes'
        ]);

        $validator = Validator::make(
            $data,
            [
                'client_id'     => 'required|unique:apps,client_id',
                'name'          => 'required|max:256',
                'homepage_url'  => 'url',
                'logo_url'      => 'url',
                'redirect_uri'  => 'required|url',
            ]
        );

        if ($validator->fails() === true) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        if (isset($data['scopes'])) {
            $failed_list = $this->checkOAuthScope($data['scopes']);
            if (count($failed_list) > 0) {
                return response()->json(['error' => '存在非法的权限值：' . implode(',', $failed_list)], 422);
            }
        }

        $data['author_id'] = $operatorId;
        $data['secret'] = Hash::make(time());
        $data['status'] = 0;
        $app = new App($data);
        $app->save();

        $app = $this->unfoldAppInfo($app);

        return response()->json($app, 201);
    }

    public function confirm(Request $request, $id)
    {
        $operatorId = (Integer)Authorizer::getResourceOwnerId();
        $operator = User::find($operatorId);

        if ($operator->group !== 1) {
          return response()->json(['error' => '只有管理员才能进行该操作'], 422);
        }

        $app = App::findOrFail($id);

        if ($app->status > 0) {
            return response()->json(['error' => '该应用无需审核'], 422);
        }

        $client = new OAuthClient([
            'id'     => $app->client_id,
            'name'   => $app->name,
            'secret' => $app->secret
        ]);
        $client->save();

        $endpoint = new OAuthClientEndPoint([
            'client_id'    => $app->client_id,
            'redirect_uri' => $app->redirect_uri
        ]);
        $endpoint->save();

        $scopes = [];
        if ($app->scopes) {
            $scopes = explode(',', $app->scopes);
        }

        foreach ($scopes as $scope_id) {
            $clientScope = new OAuthClientScope([
                'client_id'    => $app->client_id,
                'scope_id' => $scope_id
            ]);
            $clientScope->save();
        }

        $app->status = 1;
        $app->save();

        $app = $this->unfoldAppInfo($app);

        return response()->json($app, 201);
    }

    public function reject(Request $request, $id)
    {
        $operatorId = (Integer)Authorizer::getResourceOwnerId();
        $operator = User::find($operatorId);

        if ($operator->group !== 1) {
          return response()->json(['error' => '只有管理员才能进行该操作'], 422);
        }

        $app = App::findOrFail($id);

        if ($app->status === -1) {
            return response()->json(['error' => '该应用已被拒绝'], 422);
        }

        $app->status = -1;
        $app->save();

        $app = $this->unfoldAppInfo($app);

        return response()->json($app, 201);
    }

    public function refreshSecret(Request $request, $id)
    {
        $operatorId = (Integer)Authorizer::getResourceOwnerId();

        $app = App::findOrFail($id);

        if ($operatorId !== $app->author_id) {
            return response()->json(['error' => '只有应用创建者才能刷新secret'], 422);
        }

        $app->secret = Hash::make(time());;
        $app->save();

        if ($app->status >= 1) {
            $client = OAuthClient::find($app->client_id);
            $client->secret = $app->secret;
            $client->save();
        }

        $app = $this->unfoldAppInfo($app);

        return response()->json($app, 201);
    }

    public function update(Request $request, $id)
    {
        $operatorId = (Integer)Authorizer::getResourceOwnerId();

        $app = App::findOrFail($id);

        if ($operatorId !== $app->author_id) {
            return response()->json(['error' => '只有应用创建者才能更新应用信息'], 422);
        }

        $data = $request->all();

        $validator = Validator::make(
            $data,
            [
                'name'         => 'max:256',
                'homepage_url' => 'url',
                'logo_url'     => 'url',
                'redirect_uri' => 'url',
                'status'       => 'in:-1,0,1,2,3'
            ]
        );

        if ($validator->fails() === true) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        if (isset($data['scopes'])) {
            $failed_list = $this->checkOAuthScope($data['scopes']);
            if (count($failed_list) > 0) {
                return response()->json(['error' => '存在非法的权限值：' . implode(',', $failed_list)], 422);
            }
        }

        // 已通过审核的应用，开发者不能更改状态为审核中或者已拒绝
        if ($app->status >= 1 && (Integer)$data['status'] < 1) {
            unset($data['status']);
        }

        // 如果应用申请曾被拒绝或，应用权限发生变化时，需要重新审核
        if ($app->status <= 0 || $data['scopes'] !== $app->scopes) {
            $data['status'] = 0;
        }

        $result = $app->update($data);
        if ((bool)$result === false) {
          return response()->json(['error' => '应用信息更新失败'], 422);
        }

        if ($app->status >= 1) {
            $client = OAuthClient::find($app->client_id);
            $client->name = $app->name;
            $client->save();

            $endpoint = OAuthClientEndPoint::find($app->client_id);
            $endpoint->redirect_uri = $app->redirect_uri;
            $endpoint->save();

            OAuthClientScope::where('client_id', $app->client_id)->delete();

            $scopes = [];
            if ($scopeString !== null) {
                $scopes = explode(',', $scopeString);
            }

            foreach ($scopes as $scope_id) {
                $clientScope = new OAuthClientScope([
                    'client_id'    => $app->client_id,
                    'scope_id' => $scope_id
                ]);
                $clientScope->save();
            }
        } else {
            $client = OAuthClient::find($app->client_id);
            if ($client) {
                $client->delete();
            }

            $endpoint = OAuthClientEndPoint::find($app->client_id);
            if ($endpoint) {
                $endpoint->delete();
            }

            OAuthClientScope::where('client_id', $app->client_id)->delete();
        }

        $app = $this->unfoldAppInfo($app);

        return response()->json($app, 201);
    }

    public function show($id)
    {
        $app = App::findOrFail($id);

        $app = $this->unfoldAppInfo($app);

        return response()->json($app);
    }

    public function destroy($id)
    {
        $app = App::findOrFail($id);

        $client = OAuthClient::find($app->client_id);
        if ($client) {
            $client->delete();
        }
        $endpoint = OAuthClientEndPoint::find($app->client_id);
        if ($endpoint) {
            $endpoint->delete();
        }

        OAuthClientScope::where('client_id', $app->client_id)->delete();

        $app->delete();

        return response('', 204);
    }

    private function checkOAuthScope ($scopeString) {
        $scopes = [];
        if ($scopeString !== null) {
            $scopes = explode(',', $scopeString);
        }

        $not_exists = [];
        foreach ($scopes as $scope_id) {
            $scope = OAuthScope::find($scope_id);
            if (!$scope) {
                array_push($not_exists, $scope_id);
            }
        }

        return $not_exists;
    }

    private function unfoldAppInfo($app)
    {
        $operatorId = (Integer)Authorizer::getResourceOwnerId();

        if ($operatorId !== $app->author_id) {
            unset($app->secret);
            unset($app->redirect_uri);
        }


        $author = User::find($app->author_id);
        if ($author) {
            $app->author = array_only($author->toArray(), ['id', 'name', 'avatar_url']);
        }

        return $app;
    }
}
