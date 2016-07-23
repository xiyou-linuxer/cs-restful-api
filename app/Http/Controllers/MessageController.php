<?php

namespace App\Http\Controllers;


use Authorizer;
use App\Models\User;
use App\Models\Message;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $operatorId = (Integer)Authorizer::getResourceOwnerId();

        $querys = $request->query();
        $category = isset($querys['category']) ? $querys['category'] : 'all';
        $keyword = isset($querys['keyword']) ? $querys['keyword'] : '';

        $messages = Message::orderBy('created_at', 'DESC');

        if ($keyword) {
            $keyword = '%' . $keyword . '%';
            $messages = $messages->where('title', 'like', $keyword);
        }

        if ($category === 'all') {
            $messages = $messages->where([
                ['receiver_id', $operatorId],
                ['status', '>', 0]
            ]);
        } else if ($category === 'unread') {
            $messages = $messages->where([
                ['receiver_id', $operatorId],
                ['status', 1]
            ]);
        } else if ($category === 'read') {
            $messages = $messages->where([
                ['receiver_id', $operatorId],
                ['status', 2]
            ]);
        } else if ($category === 'send') {
            $messages = $messages->where([
                ['author_id', $operatorId],
                ['status', '>', 0]
            ]);
        } else if ($category === 'draft') {
            $messages = $messages->where([
                ['author_id', $operatorId],
                ['status', 0]
            ]);
        }

        $messages = $messages->groupBy('message_id');

        $page = 1;
        if (isset($querys['page']) && is_numeric($querys['page'])) {
            $page = (Integer)$querys['page'];
        }

        $pageSize = 20;
        if (isset($querys['per_page']) && is_numeric($querys['per_page'])) {
            $pageSize = (Integer)$querys['per_page'];
        }

        $totalCount = count($messages->get());
        $skip = $pageSize * ($page - 1);
        $messages = $messages->skip($skip)->take($pageSize)->get();

        foreach ($messages as $message) {
            $author = User::find($message->author_id);
            if ($author) {
                $message->author_name = $author->name;
                $message->author_avatar = $author->avatar;
            }
        }

        $result = array(
            'page'        => $page,
            'per_page'    => $pageSize,
            'total_count' => $totalCount,
            'data'        => $messages
        );
        return response()->json($result);
    }

    public function create(Request $request)
    {
        $operatorId = (Integer)Authorizer::getResourceOwnerId();

        $data = $request->only(
            'type',
            'title',
            'content',
            'receivers',
            'status'
        );

        $validator = Validator::make(
            $data,
            [
                'type'      => 'max:1',
                'title'     => 'required',
                'content'   => 'required',
                'receivers' => 'required',
                'status'    => 'required|in:"0","1"',
            ]
        );

        if ($validator->fails() === true) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $data['author_id'] = $operatorId;

        $receivers = [];
        try {
            $receivers = json_decode($data['receivers']);
            unset($data['receivers']);
        } catch (Exception $e) {
            return response()->json(['error' => '收件人数据格式错误'], 500);
        }

        $lastMessage = Message::orderBy('message_id', 'DESC')->first();
        $newMessageId = $lastMessage->message_id + 1;

        foreach ($receivers as $receiver_id) {
            $data['receiver_id'] = $receiver_id;
            $data['message_id'] = $newMessageId;
            $message = new Message($data);
            $message->save();
        }

        return response()->json($message, 201);
    }

    public function update(Request $request, $id)
    {
        $message = Message::find($id);

        if (empty($message) === true) {
            return response()->json(['error' => '该消息不存在'], 404);
        }

        $data = $request->only(
            'type',
            'title',
            'content',
            'receiver_id',
            'status'
        );

        $validator = Validator::make(
            $data,
            [
                'type'      => 'in:"0","1","2","3"'
            ]
        );

        if ($validator->fails() === true) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $result = $message->update($data);

        return response()->json($message, 201);
    }


    public function show($id)
    {
        $message = Message::find($id);

        if (empty($message) === true) {
            return response()->json(['error' => '该消息不存在'], 404);
        }

        return response()->json($message);
    }

    public function destroy($id)
    {
        $message = Message::find($id);

        if (empty($message) === true) {
            return response()->json(['error' => '该消息不存在'], 404);
        }

        $result = $message->delete();

        return response('', 204);
    }
}
