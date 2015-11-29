<?php

/**
 * Controller file for Answer
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

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Applications\Discuss\Answer;
use App\Models\Applications\Discuss\Question;
use Input, Redirect, Auth;
use Illuminate\Http\Response;

/**
 *Controller class for Answer
 *
 * PHP version 5.5.9
 *
 * @category Controller
 * @package  CS
 * @author   dreamleilei <1679211339@qq.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     https://github.com/xiyou-linuxer/cs-restful-api
 */

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param int $qid question _id
     *
     * @return \Illuminate\Http\Response
     */
    public function index($qid)
    {
        $answer = Question::find($qid)->answer;
        return new Response(json_encode($answer), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request request_form
     * @param int                      $id      question_id
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $answer = new Answer;
        $answer->content = Input::get('content');
        $answer->question_id = $id;
        $answer->author_id = 1000;
        if ($answer->save()) {
            return new Response(json_encode($answer), 200);
        } else {
            return  new Response('error', 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $qid question_id quesion_id
     * @param int $id  answer_id  answer_id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($qid, $id)
    {
        $answer =  Answer::find($id);
        return new Response(json_encode($answer), 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id question_id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request request
     * @param int                      $id      question_id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $answer = Answer::find($id);
        $answer->content = Input::get('content');
        $answer->question_id = $id;
        $answer->author_id = 1000;
        if ($answer->save()) {
            return new Response(json_encode($answer), 200);
        } else {
            return  new Response('error', 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $qid question_id
     * @param int $id  answer_id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($qid, $id)
    {
        Answer::destroy($id);
        return null;
    }
}
