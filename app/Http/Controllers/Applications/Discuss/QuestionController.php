<?php
/**
 * Controller file for Question
 *
 * PHP version 5.5.9
 *
 * @category Controller
 * @package  CS
 * @author   dreamleilei <1679211339@qq.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  GIT: 
 * @link     https://github.com/xiyou-linuxer/cs-restful-api
 */
namespace App\Http\Controllers\Applications\Discuss;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Applications\Discuss\Question;
use Auth;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Input;
use Redirect;

/**
 *Controller class for Question
 *
 * PHP version 5.5.9
 *
 * @category Controller
 * @package  CS
 * @author   dreamleilei <1679211339@qq.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     https://github.com/xiyou-linuxer/cs-restful-api
 */

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $question = Question::where('status', '0')->get();
        return new Response(json_encode($question), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Response::json(['view' => 'create']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request used for store
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(), [
                'title' => 'required',
            ]
        );
        if ($validator->fails()) {
            return  new Response('error', 422);
        }
        $question = new Question;
        $question->title = Input::get('title');
        $question->content = Input::get('content');
        $question->tags = Input::get('tags');
        $question->author_id = 1000;
        if ($question->save()) {
            return new Response(json_encode($question), 200);
        } else {
            return  new Response('error', 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id used for show
     *
     * @return Response
     */
    public function show($id)
    {
        $question = Question::find($id);
        return new Response(json_encode($question, 200));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id used for edit
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $question = Question::find($id);
        return new Response(json_encode($question), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request used for update
     * @param int                      $id      used for update
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(), [
                'title' => 'required',
            ]
        );
        if ($question->save()) {
            return new Response(json_encode($question), 200);
        } else {
            return  new Response('error', 422);
        }
        $question = Question::find($id);
        $question->title = Input::get('title');
        $question->content = Input::get('content');
        $question->tags = Input::get('tags');
        $question->author_id = 1000;
        if ($question->save()) {
            return new Response(json_encode($question), 200);
        } else {
            return  new Response('error', 422);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id used for destory
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $question = Question::find($id);
        $question->status = 2;
        if ($question->save()) {
            return null;
        } else {
            return new Response('', 403);
        }
    }
}
