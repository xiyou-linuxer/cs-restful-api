<?php

namespace App\Http\Controllers;


use Hash;
use Authorizer;
use App\Models\App;
use App\Models\User;
use App\Models\OauthClient;
use App\Models\OauthClientEndPoint;
use App\Http\Requests;
use Illuminate\Http\Request;
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
          $author = User::find($app->author_id);
          if ($author) {
              $app->author_name = $author->name;
              $app->author_avatar = $author->avatar;
          }

          if ($operatorId !== $app->author_id) {
              unset($app->secret);
              unset($app->redirect_uri);
              unset($app->permission);
          }
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
            'homepage',
            'logo',
            'description',
            'redirect_uri'
        ]);

        $validator = Validator::make(
            $data,
            [
                'client_id'     => 'required|unique:apps,client_id',
                'name'          => 'required|max:256',
                'homepage'      => 'url',
                'logo'          => 'url',
                'redirect_uri'  => 'required|url',
            ]
        );

        if ($validator->fails() === true) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $data['author_id'] = $operatorId;
        $data['secret'] = Hash::make(time());
        $data['status'] = 0;
        $app = new App($data);
        $app->save();

        return response()->json($app, 201);
    }

    public function confirm(Request $request, $id)
    {
        $operatorId = (Integer)Authorizer::getResourceOwnerId();
        $operator = User::find($operatorId);

        if ($operator->group !== 1) {
          return response()->json(['error' => '只有管理员才能进行该操作'], 422);
        }

        $app = App::find($id);

        if (empty($app) === true) {
            return response()->json(['error' => '未找到该应用'], 404);
        }

        if ($app->status !== 0) {
            return response()->json(['error' => '该应用无需审核'], 422);
        }

        $client = new OauthClient([
            'id'     => $app->client_id,
            'name'   => $app->name,
            'secret' => $app->secret
        ]);
        $client->save();

        $endpoint = new OauthClientEndPoint([
            'client_id'    => $app->client_id,
            'redirect_uri' => $app->redirect_uri
        ]);
        $endpoint->save();

        $app->status = 1;
        $app->save();

        return response()->json($app, 201);
    }

    public function update(Request $request, $id)
    {
        $operatorId = (Integer)Authorizer::getResourceOwnerId();

        $app = App::find($id);
        if ($operatorId !== $app->author_id) {
            return response()->json(['error' => '只有应用创建者才能更新应用信息'], 422);
        }

        if (empty($app) === true) {
            return response()->json(['error' => '未找到该应用'], 404);
        }

        $data = $request->only([
            'name',
            'homepage',
            'logo',
            'description',
            'redirect_uri',
            'status'
        ]);

        if ($app->status <= 0) {
            unset($data['status']);
        }

        $validator = Validator::make(
            $data,
            [
                'name'         => 'max:256',
                'homepage'     => 'url',
                'logo'         => 'url',
                'redirect_uri' => 'url',
                'status'       => 'alpha_num|min:1|max:3'
            ]
        );

        if ($validator->fails() === true) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $app = $app->update($data);
         if ($app->status >= 1) {
            $endpoint = OauthClientEndPoint::where('client_id', $app->client_id)->first();
            $endpoint->redirect_uri = $app->redirect_uri;
            $endpoint->save();
        }

        return response()->json($app, 201);
    }

    public function show($id)
    {
        $operatorId = (Integer)Authorizer::getResourceOwnerId();

        $app = App::find($id);

        if (empty($app) === true) {
            return response()->json(['error' => '未找到该应用'], 404);
        }

        $author = User::find($app->author_id);

        $app->author_name = $author->name;
        $app->author_avatar = $author->avatar;

        if ($operatorId !== $app->author_id) {
            unset($app->secret);
            unset($app->redirect_uri);
            unset($app->permission);
        }

        return response()->json($app);
    }

    public function destroy($id)
    {
        $app = App::find($id);

        if (empty($app) === true) {
            return response()->json(['error' => '未找到该应用'], 404);
        }

        $client = OauthClient::find($app->client_id);
        if ($client) {
            $client->delete();
        }
        $endpoint = OauthClientEndPoint::where('client_id', $app->client_id)->first();
        if ($endpoint) {
            $endpoint->delete();
        }

        $app->delete();

        return response('', 204);
    }
}
