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

        if (isset($querys['author_id']) && is_numeric($querys['author_id'])) {
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
        $operator = User::findOrFail($operatorId);

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
            return response()->json(['error' => $validator->errors()], 400);
        }

        if (isset($data['scopes'])) {
            $failed_list = $this->checkOAuthScope($data['scopes']);
            if (count($failed_list) > 0) {
                return response()->json(['error' => '存在非法的权限值：' . implode(',', $failed_list)], 400);
            }
        }

        $data['author_id'] = $operatorId;
        $data['secret'] = Hash::make(time());

        $data['status'] = 0;
        if ($operator->group === 1) {
            $data['status'] = 1;
        }

        if (isset($data['description'])) {
            $data['description'] = preg_replace('/<(.+?)>|<(\/.+?)>/', '&lt;$1&gt;', $data['description']);
        }

        $app = new App($data);
        $app->save();

        if ($app->status > 0) {
            $this->addOAuthClient($app);
        }

        $app = $this->unfoldAppInfo($app);

        return response()->json($app, 201);
    }

    public function confirm(Request $request, $id)
    {
        $operatorId = (Integer)Authorizer::getResourceOwnerId();
        $operator = User::findOrFail($operatorId);

        if ($operator->group !== 1) {
          return response()->json(['error' => '只有管理员才能进行该操作'], 403);
        }

        $app = App::where('client_id', $id)->first();
        if (!$app) {
            return response()->json(['error' => '资源不存在'], 404);
        }

        if ($app->status > 0) {
            return response()->json(['error' => '该应用无需审核'], 423);
        }

        $this->addOAuthClient($app);

        $app->status = $app->submit_status === null ? 1 : $app->submit_status;
        $app->save();

        $app = $this->unfoldAppInfo($app);

        return response()->json($app, 201);
    }

    public function reject(Request $request, $id)
    {
        $operatorId = (Integer)Authorizer::getResourceOwnerId();
        $operator = User::find($operatorId);

        if ($operator->group !== 1) {
          return response()->json(['error' => '只有管理员才能进行该操作'], 403);
        }

        $app = App::where('client_id', $id)->first();
        if (!$app) {
            return response()->json(['error' => '资源不存在'], 404);
        }

        if ($app->status === -1) {
            return response()->json(['error' => '该应用已被拒绝'], 423);
        }

        $app->status = -1;
        $app->save();

        $app = $this->unfoldAppInfo($app);

        return response()->json($app, 201);
    }

    public function refreshSecret(Request $request, $id)
    {
        $operatorId = (Integer)Authorizer::getResourceOwnerId();

        $app = App::where('client_id', $id)->first();
        if (!$app) {
            return response()->json(['error' => '资源不存在'], 404);
        }

        if ($operatorId !== $app->author_id) {
            return response()->json(['error' => '只有应用创建者才能刷新secret'], 403);
        }

        if ($app->status < 1) {
            return response()->json(['error' => '只有已通过审核并且未下线的应用才能刷新secret'], 423);
        }
        $app->secret = substr(Hash::make(time()), 0, 40);;
        $app->save();

        $client = OAuthClient::find($app->client_id);
        $client->secret = $app->secret;
        $client->save();

        $app = $this->unfoldAppInfo($app);

        return response()->json($app, 201);
    }

    public function update(Request $request, $id)
    {
        $operatorId = (Integer)Authorizer::getResourceOwnerId();
        $operator = User::findOrFail($operatorId);

        $app = App::where('client_id', $id)->first();
        if (!$app) {
            return response()->json(['error' => '资源不存在'], 404);
        }

        if ($operatorId !== $app->author_id && $operator->group !== 1) {
            return response()->json(['error' => '只有系统管理员或应用创建者才能修改应用信息'], 403);
        }

        $data = $request->all();

        $validator = Validator::make(
            $data,
            [
                'name'         => 'max:256',
                'homepage_url' => 'url',
                'logo_url'     => 'url',
                'redirect_uri' => 'url',
                'status'       => 'in:-2,2,3'
            ]
        );

        if ($validator->fails() === true) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        if (isset($data['scopes'])) {
            $failed_list = $this->checkOAuthScope($data['scopes']);
            if (count($failed_list) > 0) {
                return response()->json(['error' => '存在非法的权限值：' . implode(',', $failed_list)], 400);
            }
        }

        if (isset($data['status'])) {
            $data['submit_status'] = $data['status'];
        }

        // 如果是开发者修改信息，保存提交状态
        if ($operator->group !== 1) {
            $data['status'] = 0;
        } else if($app->status === 0 || $app->status === -1) {
            $data['status'] = 1;
        }

        if (isset($data['description'])) {
            $data['description'] = preg_replace('/<(.+?)>|<(\/.+?)>/', '&lt;$1&gt;', $data['description']);
        }

        $result = $app->update($data);
        if ((bool)$result === false) {
            return response()->json(['error' => '应用信息更新失败'], 500);
        }

        if ($app->status >= 1) {
            $this->syncOAuthClient($app);
        } else {
            $this->removeOAuthClient($app);
        }

        $app = $this->unfoldAppInfo($app);

        return response()->json($app, 201);
    }

    public function show($id)
    {
        $app = App::where('client_id', $id)->first();
        if (!$app) {
            return response()->json(['error' => '资源不存在'], 404);
        }

        $app = $this->unfoldAppInfo($app);

        return response()->json($app);
    }

    public function destroy($id)
    {
        $app = App::where('client_id', $id)->first();
        if (!$app) {
            return response()->json(['error' => '资源不存在'], 404);
        }

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
            $scope = OAuthScope::where('id', $scope_id)->first();
            if (!$scope) {
                array_push($not_exists, $scope_id);
            }
        }

        return $not_exists;
    }

    private function addOAuthClient($app)
    {
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
    }

    private function syncOAuthClient($app)
    {
        $client = OAuthClient::find($app->client_id);

        if (!$client) {
            return $this->addOAuthClient($app);
        }

        $client->name = $app->name;
        $client->save();

        $endpoint = OAuthClientEndPoint::find($app->client_id);
        $endpoint->redirect_uri = $app->redirect_uri;
        $endpoint->save();

        OAuthClientScope::where('client_id', $app->client_id)->delete();

        $scopes = [];
        $scopeString = $app->scopes;
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
    }

    private function removeOAuthClient($app)
    {
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

    private function unfoldAppInfo($app)
    {
        $operatorId = (Integer)Authorizer::getResourceOwnerId();
        $operator = User::findOrFail($operatorId);

        if ($operatorId !== $app->author_id && $operator->group !== 1) {
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
