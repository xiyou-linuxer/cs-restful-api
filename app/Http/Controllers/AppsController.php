<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Apps;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests;

class AppsController extends Controller
{
     public function index(Request $request)
     {
         $result = Apps::all()->toArray();
         return response()->json($result);
     }
    
    public function create(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make(
            $data,
            [
                'name'          => 'required|max:256',
                'description'  => 'required',
                'key'           => 'max:128',
                'status'        => 'required|alpha_num|max:1',
                'redirect_url'  => 'required',
                'permission'    => 'required|max:2',
            ]
        );

        if ($validator->fails() === true) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $app = new Apps($data);

        $app->save();

        return response()->json($app, 201);
    }
    public function update(Request $request, $id)
    {
        $app = Apps::find($id);

        if (empty($app) === true) {
            return response()->json(['error' => 'app not found'], 404);
        }

        $data = $request->only(
            'name',
            'description',
            'status',
            'key',
            'redirect_url'
        );

        $validator = Validator::make(
            $data,
            [
                'name'          => 'required|max:256',
                'description'  => 'required',
                'key'           => 'required|max:128',
                'status'        => 'required|in:"0","1","2","3"',
                'redirect_url'  => 'required',
            ]
        );

        if ($validator->fails() === true) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $result = $app->update($data);

        if ((bool)$result === false) {
            return response()->json(['error' => 'app update failed'], 422);
        }

        return response()->json($app, 201);
    }
    public function show($id)
    {
        $app = Apps::find($id);

        if (empty($app) === true) {
            return response()->json(['error' => 'app not found'], 404);
        }

        return response()->json($app);
    }

    public function destroy($id)
    {
        $app = Apps::find($id);

        if (empty($app) === true) {
            return response()->json(['error' => 'app not found    '], 404);
        }

        $result = $app->delete();

        if ((bool)$result === false) {
            return response()->json(['error' => 'app delete fa iled'], 502);
        }

        return response('', 204);
    }
}
