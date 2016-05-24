<?php

namespace App\Http\Controllers;


use Authorizer;
use App\Models\News;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $querys = $request->query();
        $keyword = isset($querys['keyword']) ? $querys['keyword'] : '';
        $author_id = isset($querys['author_id']) ? $querys['author_id'] : 0;

        $news = News::distinct()->orderBy('created_at', 'desc');

        if ($author_id) {
            $news = $news->where('author_id', $author_id);
        }

        if ($keyword) {
            $keyword = '%' . $keyword . '%';
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
        $operatorId = Authorizer::getResourceOwnerId();

        $operator = User::find($operatorId);

        $data = $request->all();

        $validator = Validator::make(
            $data,
            [
                'content'     => 'required',
            ]
        );

        if ($validator->fails() === true) {
            return response()->json(['error' => $validator->errors()], 422);
        }


        $data['type'] = 0;
        $data['app_id'] = 0;
        $data['author_id'] = $operator->id;

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

        $data = $request->all();

        $validator = Validator::make(
            $data,
            [
                'author_id' => 'alpha_num|max:32',
                'app_id'    => 'alpha_num|max:32',
                'topic'     => 'required',
                'content'   => 'required',

            ]
        );
        if ($validator->fails() === true) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $result = $new->update($data);

        if ((bool)$result === false) {
            return response()->json(['error' => 'new update failed'], 422);
        }

        return response()->json($new, 201);
    }

    public function show($id)
    {
        $new = News::find($id);

        if (empty($new) === true) {
            return response()->json(['error' => 'new not found'], 404);
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
