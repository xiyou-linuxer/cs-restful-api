<?php

namespace App\Http\Controllers\Api;


use Authorizer;
use App\Models\App;
use App\Models\User;
use App\Models\News;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
            $news = $this->unfoldNewsInfo($_news);
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
            'link_url',
            'content'
        );

        $validator = Validator::make(
            $data,
            [
                'content' => 'required',
                'link_url'    => 'url'
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

        $news = new News($data);

        $news->save();

        $news = $this->unfoldNewsInfo($news);

        return response()->json($news, 201);
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $data = $request->all();

        $validator = Validator::make(
            $data,
            [
                'link_url'    => 'url'
            ]
        );
        if ($validator->fails() === true) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $result = $news->update($data);

        if ((bool)$result === false) {
            return response()->json(['error' => '动态信息更新失败'], 422);
        }

        $news = $this->unfoldNewsInfo($news);

        return response()->json($news, 201);
    }

    public function show($id)
    {
        $news = News::findOrFail($id);

        $news = $this->unfoldNewsInfo($news);

        return response()->json($news);
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);

        $result = $news->delete();

        if ((bool)$result === false) {
            return response()->json(['error' => '动态信息删除失败'], 502);
        }

        return response('', 204);
    }

    private function unfoldNewsInfo($news)
    {
        $author = User::find($news->author_id);
        if ($author) {
            $news->author = array_only($author->toArray(), ['id', 'name', 'avatar_url']);
        }

        $app = App::where('client_id', $news->app_id)->first();
        if ($app) {
            $news->app = array_only($app->toArray(), ['name', 'logo_url', 'homepage_url']);
        }

        return $news;
    }
}
