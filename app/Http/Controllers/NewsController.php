<?php

namespace App\Http\Controllers;


use Authorizer;
use App\Models\App;
use App\Models\User;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $querys = $request->query();

        $news = News::distinct()->orderBy('created_at', 'desc');

        if (isset($querys['type'])) {
            $news = $news->where('type', $querys['type']);
        }

        if (isset($querys['app_id'])) {
            $news = $news->where('app_id', $querys['app_id']);
        }

        if (isset($querys['author_id'])) {
            $news = $news->where('author_id', $querys['author_id']);
        }

        if (isset($querys['keyword'])) {
            $keyword = '%' . $querys['keyword'] . '%';
            $news = $news->where('topic', 'like', $keyword);
        }

        $page = 1;
        if (isset($querys['page']) && is_numeric($querys['page'])) {
            $page = (Integer)$querys['page'];
        }

        $pageSize = 20;
        if (isset($querys['per_page']) && is_numeric($querys['per_page'])) {
            $pageSize = (Integer)$querys['per_page'];
        }

        $totalCount = count($news->get());
        $skip = $pageSize * ($page - 1);
        $news = $news->skip($skip)->take($pageSize)->get();

        foreach ($news as $_news) {
            $author = User::find($_news->author_id);
            if ($author) {
                $_news->author_name = $author->name;
                $_news->author_avatar = $author->avatar;
            }
            $app = App::where('client_id', $_news->app_id)->first();
            if ($app) {
                $_news->app_name = $app->name;
                $_news->app_logo = $app->logo;
                $_news->app_homepage = $app->homepage;
            }
        }

        $result = array(
          'page'        => $page,
          'per_page'    => $pageSize,
          'total_count' => $totalCount,
          'data'        => $news
        );

        return response()->json($result);
    }

    public function create(Request $request)
    {
        $clientId = Authorizer::getClientId();
        $operatorId = Authorizer::getResourceOwnerId();

        $data = $request->only(
            'type',
            'topic',
            'link',
            'content'
        );

        $validator = Validator::make(
            $data,
            [
                'content' => 'required',
                'link'    => 'url'
            ]
        );

        if ($validator->fails() === true) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        if (isset($data['type']) && (Integer)$data['type'] === 1) {
            $data['type'] =  1;
        } else {
            $data['type'] =  0;
        }

        $data['app_id'] = $clientId;
        $data['author_id'] = $operatorId;

        $new = new News($data);

        $new->save();

        return response()->json($new, 201);
    }

    public function update(Request $request, $id)
    {
        $new = News::find($id);
        if (empty($new) === true) {
            return response()->json(['error' => 'new not found'], 404);
        }

        $data = $request->only(
            'type',
            'topic',
            'link',
            'content'
        );

        $validator = Validator::make(
            $data,
            [
                'link'    => 'url'
            ]
        );
        if ($validator->fails() === true) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $result = $new->update($data);

        if ((bool)$result === false) {
            return response()->json(['error' => '动态信息更新失败'], 422);
        }

        return response()->json($new, 201);
    }

    public function show($id)
    {
        $new = News::find($id);

        if (empty($new) === true) {
            return response()->json(['error' => '未找到相关动态信息'], 404);
        }

        return response()->json($new);
    }

    public function destroy($id)
    {
        $new = News::find($id);

        if (empty($new) === true) {
            return response()->json(['error' => 'new not found    '], 404);
        }

        $result = $new->delete();

        if ((bool)$result === false) {
            return response()->json(['error' => 'new delete failed'], 502);
        }

        return response('', 204);
    }
}
