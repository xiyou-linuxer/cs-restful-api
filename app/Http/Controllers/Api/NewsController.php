<?php

namespace App\Http\Controllers\Api;


use Authorizer;
use App\Models\App;
use App\Models\User;
use App\Models\News;
use App\Models\NewsComment;
use App\Models\NewsFavor;
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
            $_news = $this->unfoldNewsInfo($_news);
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
        $operatorId = (Integer)Authorizer::getResourceOwnerId();

        $data = $request->all();

        $validator = Validator::make(
            $data,
            [
                'content' => 'required',
                'link_url'    => 'url',
                'type'     => 'in:0,1'
            ]
        );

        if ($validator->fails() === true) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        if (!isset($data['type'])) {
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
        $operatorId = (Integer)Authorizer::getResourceOwnerId();

        $operator = User::findOrFail($operatorId);

        $news = News::findOrFail($id);

        if ($news->author_id !== $operatorId && $operator->group !== 1) {
            return response()->json(['error' => '非本人或管理员不能修改动态资料'], 403);
        }

        $data = $request->all();

        $validator = Validator::make(
            $data,
            [
                'link_url'    => 'url'
            ]
        );
        if ($validator->fails() === true) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $result = $news->update($data);

        if ((bool)$result === false) {
            return response()->json(['error' => '动态信息更新失败'], 500);
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
        $operatorId = (Integer)Authorizer::getResourceOwnerId();

        $news = News::findOrFail($id);

        if ($news->author_id !== $operatorId && $operator->group !== 1) {
            return response()->json(['error' => '非本人或管理员不能删除动态资料'], 403);
        }

        if ($news->type === 1) {
            return response()->json(['error' => '不能删除应用创建的动态'], 403);
        }


        $result = $news->delete();

        return response('', 204);
    }

    public function favor($newsId)
    {
        $clientId = Authorizer::getClientId();
        $operatorId = (Integer)Authorizer::getResourceOwnerId();

        $news = News::findOrFail($newsId);

        $hasFavored = false;
        $favors = $news->getFavors()->get();
        foreach ($favors as $favor) {
            if ($favor->author_id === $operatorId) {
                $hasFavored = true;
            }
        }

        if ($hasFavored) {
            return response()->json(['error' => '您已经点过赞了，不能重复点赞'], 403);
        }

        $favor = new NewsFavor([
            'app_id'    => $clientId,
            'author_id' => $operatorId,
            'news_id'   => $news->id
        ]);

        $favor->save();

        $news = $this->unfoldNewsInfo($news);

        return response()->json($news, 201);
    }

    public function createComment(Request $request, $newsId)
    {
        $clientId = Authorizer::getClientId();
        $operatorId = (Integer)Authorizer::getResourceOwnerId();

        $news = News::findOrFail($newsId);

        $data = $request->all();

        $validator = Validator::make(
            $data,
            [
                'content' => 'required'
            ]
        );

        if ($validator->fails() === true) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $data['news_id'] = $newsId;
        $data['app_id'] = $clientId;
        $data['author_id'] = $operatorId;

        $comment = new NewsComment($data);

        $comment->save();


        $news = $this->unfoldNewsInfo($news);

        return response()->json($news, 201);
    }

    public function getComments(Request $request, $newsId)
    {
        $news = News::findOrFail($newsId);

        $querys = $request->query();

        $comments = $news->getComments()->orderBy('created_at', 'desc');

        if (isset($querys['author_id']) && is_numeric($querys['author_id'])) {
            $author_id = (Integer)$querys['author_id'];
            $comments = $comments->where('author_id', $author_id);
        }

        $page = 1;
        if (isset($querys['page']) && is_numeric($querys['page'])) {
            $page = (Integer)$querys['page'];
        }

        $pageSize = 20;
        if (isset($querys['per_page']) && is_numeric($querys['per_page'])) {
            $pageSize = (Integer)$querys['per_page'];
        }

        $totalCount = count($comments->get());
        $skip = $pageSize * ($page - 1);
        $comments = $comments->skip($skip)->take($pageSize)->get();

        foreach ($comments as $comment) {
            $comment = $this->unfoldCommentInfo($comment);
        }

        $result = array(
            'page'        => $page,
            'per_page'    => $pageSize,
            'total_count' => $totalCount,
            'data'        => $comments
        );
        return response()->json($result);
    }

    private function unfoldNewsInfo($news)
    {
        $operatorId = (Integer)Authorizer::getResourceOwnerId();

        $author = User::find($news->author_id);
        if ($author) {
            $news->author = array_only($author->toArray(), ['id', 'name', 'avatar_url']);
        }

        $app = App::where('client_id', $news->app_id)->first();
        if ($app) {
            $news->app = array_only($app->toArray(), ['name', 'logo_url', 'homepage_url']);
        }

        $comments = $news->getComments()->orderBy('created_at', 'desc')->get();
        foreach ($comments as $comment) {
            $comment = $this->unfoldCommentInfo($comment);
        }
        $news->comments = $comments;

        $news->hasFavored = false;
        $favors = $news->getFavors()->get();
        foreach ($favors as $favor) {
            if ($favor->author_id === $operatorId) {
                $news->hasFavored = true;
            }
            $favor = $this->unfoldFavorInfo($favor);
        }
        $news->favors = $favors;


        return $news;
    }

    private function unfoldCommentInfo($comment)
    {
        $author = User::find($comment->author_id);
        if ($author) {
            $comment->author = array_only($author->toArray(), ['id', 'name', 'avatar_url']);
        }

        $app = App::where('client_id', $comment->app_id)->first();
        if ($app) {
            $comment->app = array_only($app->toArray(), ['name', 'logo_url', 'homepage_url']);
        }

        return $comment;
    }

    private function unfoldFavorInfo($favor)
    {
        $author = User::find($favor->author_id);
        if ($author) {
            $favor->author = array_only($author->toArray(), ['id', 'name', 'avatar_url']);
        }

        $app = App::where('client_id', $favor->app_id)->first();
        if ($app) {
            $favor->app = array_only($app->toArray(), ['name', 'logo_url', 'homepage_url']);
        }

        return $favor;
    }

}
