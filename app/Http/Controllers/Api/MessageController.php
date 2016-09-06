<?php

namespace App\Http\Controllers\Api;

use Authorizer;
use App\Models\App;
use App\Models\User;
use App\Models\Message;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $operatorId = (Integer)Authorizer::getResourceOwnerId();

        $querys = $request->query();
        $keyword = isset($querys['keyword']) ? $querys['keyword'] : '';

        $messages = Message::orderBy('created_at', 'DESC');

        if ($keyword) {
            $keyword = '%' . $keyword . '%';
            $messages = $messages->where('title', 'like', $keyword);
        }

        if (isset($querys['app_id']) && is_numeric($querys['app_id'])) {
            $app_id = (Integer)$querys['app_id'];
            $messages = $messages->where('app_id', $app_id);
        }

        $category = -2; // default get all received
        // < -2: all; -1: send; 0: draft; 1: unread; 2: read
        if (isset($querys['category']) && is_numeric($querys['category'])) {
            $category = (Integer) $querys['category'];
        }

        if ($category === -1) { // send
            $messages = $messages->where([
                ['receiver_id', 0],
                ['author_id', $operatorId],
                ['status', '>', 0],
                ['type', 0]
            ]);
        } else if ($category < -1) { // all
            $messages = $messages->where([
                ['receiver_id', $operatorId],
                ['status', '>', 0]
            ]);
        } else if ($category === 0) {// draft
            $messages = $messages->where([
                ['author_id', $operatorId],
                ['status', 0]
            ]);
        } else {
            $messages = $messages->where([
                ['receiver_id', $operatorId],
                ['status', $category]
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
            $message = $this->unfoldMessageInfo($message);
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
        $clientId = Authorizer::getClientId();
        $operatorId = (Integer)Authorizer::getResourceOwnerId();

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
                'type'      => 'in:0,1',
                'title'     => 'required',
                'content'   => 'required',
                'status'    => 'required|in:0,1',
            ]
        );

        if ($validator->fails() === true) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        if (empty($data['type'])) {
            $data['type'] = 0;
        }

        $data['app_id'] = $clientId;
        $data['author_id'] = $operatorId;

        $lastMessage = Message::orderBy('message_id', 'DESC')->first();
        $newMessageId = $lastMessage ? $lastMessage->message_id + 1 : 1;

        $status = 0;
        if (isset($data['status']) && is_numeric($data['status'])) {
            $status = (Integer)$data['status'];
        }

        if ($status === 1 && empty($data['receiver_id'])) {
            return response()->json(['error' => '发送消息时收件人不能为空'], 422);
        }

        if (!empty($data['receiver_id'])) {
            $allReceiverIds = [];
            $allReceiverIds = array_unique(explode(',', $data['receiver_id']));
            $data['all_receiver_ids'] = implode(',', $allReceiverIds);
        }
        $data['receiver_id'] = 0;
        $data['message_id'] = $newMessageId;

        $data['content'] = preg_replace('/<(.+?)>|<(\/.+?)>/', '&lt;$1&gt;', $data['content']);

        $message = new Message($data);
        $message->save();

        if ($status === 1) {
            $failedList = $this->send($message, $data['all_receiver_ids']);
            if (count($failedList) > 0) {
                return response()->json(['error' => '消息已发送，部分接收人不存在：' . implode(',', $failedList)], 422);
            }
        }

        $message = $this->unfoldMessageInfo($message);

        return response()->json($message, 201);
    }

    public function update(Request $request, $id)
    {
        $message = Message::findOrFail($id);
        $operatorId = (Integer)Authorizer::getResourceOwnerId();

        if ($operatorId !== $message->author_id) {
            return response()->json(['error' => '只有写信人才能修改消息'], 403);
        }

        if ($message->status !== 0) {
            return response()->json(['error' => '无法修改已发送的消息'], 423);
        }

        $data = $request->except('message_id');
        $validator = Validator::make(
            $data,
            [
                'type'      => 'in:"0","1"',
                'status'   => 'in:0,1'
            ]
        );

        if ($validator->fails() === true) {
            return response()->json(['error' => $validator->errors()], 403);
        }

        $status = 0;
        if (isset($data['status']) && is_numeric($data['status'])) {
            $status = (Integer)$data['status'];
        }

        if ($status === 1 && empty($message->all_receiver_ids) && empty($data['receiver_id'])) {
            return response()->json(['error' => '发送消息时收件人不能为空'], 422);
        }

        if (!empty($data['receiver_id'])) {
            $allReceiverIds = [];
            $allReceiverIds = array_unique(explode(',', $data['receiver_id']));
            $data['all_receiver_ids'] = implode(',', $allReceiverIds);
        } else if ($status === 0) {
            $data['all_receiver_ids'] = '';
        }

        $data['receiver_id'] = 0;

        if (isset($data['content'])) {
            $data['content'] = preg_replace('/<(.+?)>|<(\/.+?)>/', '&lt;$1&gt;', $data['content']);
        }

        $message->update($data);

        if ($status === 1) {
            $failedList = $this->send($message);
            if (count($failedList) > 0) {
                return response()->json(['error' => '消息已发送，部分接收人不存在, ID为：' . implode(',', $failedList)], 422);
            }
        }

        $message = $this->unfoldMessageInfo($message);

        return response()->json($message, 201);
    }

    public function show($id)
    {
        $message = Message::findOrFail($id);
        $operatorId = (Integer)Authorizer::getResourceOwnerId();

        if ($message->status === 0 && $operatorId !== $message->author_id) {
            return response()->json(['error' => '只有写信人才能查看该草稿'], 403);
        } else if ($message->status > 0 && $operatorId !== $message->author_id && $operatorId !== $message->receiver_id) {
            return response()->json(['error' => '只有发送人或者接收人才能查看该消息'], 403);
        }

        if ($message->status === 1) {
            $message->status = 2;
            $message->save();
        }

        $message = $this->unfoldMessageInfo($message);

        return response()->json($message);
    }

    public function destroy($id)
    {
        $message = Message::findOrFail($id);
        $operatorId = (Integer)Authorizer::getResourceOwnerId();

        if ($message->receiver_id === 0) {
            if ($operatorId !== $message->author_id) {
                return response()->json(['error' => '只有发送人才能删除该消息'], 403);
            }
        } else {
            if ($operatorId !== $message->receiver_id) {
                return response()->json(['error' => '只有接收人才能删除该消息'], 403);
            }
        }

        $message->delete();

        return response('', 204);
    }

    private function send($message)
    {
        $data = array_only($message->toArray(), [
            'message_id',
            'type',
            'app_id',
            'author_id',
            'all_receiver_ids',
            'title',
            'content'
        ]);

        $allReceiverIds = [];
        $receiverIdString = $message['all_receiver_ids'];
        if (!empty($receiverIdString)) {
            $allReceiverIds = explode(',', $receiverIdString);
        }

        $failedList = [];
        foreach ($allReceiverIds as $receiverId) {
            if (!is_numeric ($receiverId)) {
                array_push($failedList, $receiverId);
                continue;
            }

            $user = User::find($receiverId);
            if (!$user) {
                array_push($failedList, $receiverId);
                continue;
            }

            $data['receiver_id'] = $receiverId;
            $data['status'] = 1;
            $message = new Message($data);
            $message->save();
        }

        return $failedList;
    }

    private function unfoldMessageInfo($message)
    {
        $onlyNeedFeilds = ['id', 'name', 'avatar_url'];

        $app = App::where('client_id', $message->app_id)->first();
        if ($app) {
            $message->app = array_only($app->toArray(), ['client_id', 'name', 'logo_url', 'homepage_url']);
        }

        $author = User::find($message->author_id);
        if ($author) {
            $message->author = array_only($author->toArray(), $onlyNeedFeilds);
        }

        $receiver= User::find($message->receiver_id);
        if ($receiver) {
            $message->receiver = array_only($receiver->toArray(), $onlyNeedFeilds);
        }


        $allReceiverIds = [];
        if (!empty($message->all_receiver_ids)) {
            $allReceiverIds = explode(',', $message->all_receiver_ids);
        }

        $all_receivers = [];
        foreach ($allReceiverIds as $receiverId) {
            $user = User::find($receiverId);
            if ($user) {
                array_push($all_receivers, array_only($user->toArray(), $onlyNeedFeilds));
            }
        }

        $message->all_receivers = $all_receivers;

        return $message;
    }
}
