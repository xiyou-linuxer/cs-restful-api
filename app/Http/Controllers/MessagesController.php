<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Messages;
use App\Http\Requests;
use Illuminate\Support\Facades\Validator;

class MessagesController extends Controller
{
    public function index(Request $request)
    {
        $querys = $request->query();
        $result = Messages::distinct()->orderBy('id');
        $keywords = isset($querys['keywords'])?$querys['keywords']:'';
        if ($keywords) {
            $keywords = '%' . $keywords . '%';
            $result = $result->where('title', 'like', $keywords);
        }

        $messages = $result->get();

        return response()->json($messages);
    }

    public function create(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make(
            $data,
            [
                'type'      => 'max:1',
                'author_id' => 'alpha_num|max:32',
                'app_id'    => 'alpha_num|max:32',
                'title'     => 'required',
                'content'   => 'required',
                'receivers' => 'required',
                'status'    => 'required|max:1|in:"0","1","2","3"',
            ]
        );

        if ($validator->fails() === true) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $message = new Messages($data);

        $message->save();

        return response()->json($message, 201);
    }

    public function update(Request $request, $id)
    {
        $message = Messages::find($id);

        if (empty($message) === true) {
            return response()->json(['error' => 'message not found'], 404);
        }

        $data = $request->all();

        $validator = Validator::make(
            $data,
            [
                'author_id' => 'alpha_num|max:32',
                'app_id'    => 'alpha_num|max:32',
                'title'     => 'required',
                'content'   => 'required',
                'status'    => 'required|max:1|in:"0","1","2","3"',
                'receivers' => 'required',
                'type'      => 'max:1',
            ]
        );

        if ($validator->fails() === true) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $result = $message->update($data);

        if ((bool)$result === false) {
            return response()->json(['error' => 'message update failed'], 422);
        }

        return response()->json($message, 201);
    }


    public function show($id)
    {
        $message = Messages::find($id);

        if (empty($message) === true) {
            return response()->json(['error' => 'message not found'], 404);
        }

        return response()->json($message);
    }

    public function destroy($id)
    {
        $message = Messages::find($id);

        if (empty($message) === true) {
            return response()->json(['error' => 'message not found    '], 404);
        }

        $result = $message->delete();

        if ((bool)$result === false) {
            return response()->json(['error' => 'message delete fa iled'], 502);
        }

        return response('', 204);
    }
}

