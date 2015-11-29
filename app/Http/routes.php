<?php

/**
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 *
 * PHP version 5.6
 *
 * @category PHP
 * @package  PHP_Laveral
 * @author   teddyliao <sxliao@foxmail.com>
 * @license  http://xiyoulinux.org BSD Licence
 * @link     http://cs.xiyoulinux.org
 */

Route::get(
    '/', 
    function () {
        return  Response::json(['error' => 'null']);
    }
);

Route::group(
    [
        'prefix' => 'discuss', 
        'namespace' => 'Applications\Discuss'
    ],
    function () {
         Route::resource('questions', 'QuestionController');
         Route::resource('questions/{question_id}/answers', 'AnswerController');
    }
);

