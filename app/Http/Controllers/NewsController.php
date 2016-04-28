<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $querys = $request->query();
        $result = News::distinct()->orderBy('id');
        $keywords = isset(querys['keyword'])?querys['keywords'] : '';

        if ($keywords) {
            $keywords = '%' . $keywords . '%';
            $result = $result->where('topic', 'like', $keywords);
        }

        $news = $result->get();

        return response()->json($news);
    }

    public function create(Request $request)
    {
        $data = $request->only(
            'author_id',
            'app_id',
            'type',
            'topic',
            'content'
        )

        $validator = Validator::make(
            $data,
            [
                'type'      => 'max:1',
                'author_id' => 'alpha_num|max:32',
                'app_id'    => 'alpha_num|max:32',
                'topic'     => '',
                'content'     => '',
            ]
       );

       if ($validator->fails() === true) {
           return response()->json(['error' => $validator->errors()], 422);
       }

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
            'id',
            'author_id',
            'app_id',
            'topic',
            'content'
        );

        $validator = Validator::make(
            $data,
            [
                'id'        => 'required',
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
