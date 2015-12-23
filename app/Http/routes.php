<?php

/**
 * Application Routes
 *
 * Here is where you can register all of the routes for an application.
 * It's a breeze. Simply tell Laravel the URIs it should respond to
 * and give it the controller to call when that URI is requested.
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
        return view('cs.pages.home');
    }
);

Route::group(
    [
    'prefix' => 'online', 'namespace' => 'User'
    ], 
    function () {
        /*get the data of those who online and offline*/
        route::get('/', 'CsUserController@online');

        /*create a new online messg*/
        route::post('/{id}', 'CsUserController@create');

        /*update online info*/
        route::put('/{id}', 'CsUserController@update');
    }
);

Route::group(
    [
    'prefix' => 'admins', 'namespace' => 'Admin'
    ], 
    function () {
    }
);

Route::group(
    [
        'namespace' => 'User'
    ], 
    function () {

        Route::group(
            ['middleware' => 'jwt.auth'],
            function () {
                /*obey the restful rule api*/
                Route::resource('users', 'CsUserController');

                /*deliver privilege*/
                Route::post('/privilege', 'CsUserController@privilege');

                /*reset password*/
                Route::put('/{id}/password', 'CsUserController@resetpd');
            }
        );
    }
);

Route::group(
    ['prefix' => 'api', 'namespace' => 'Auth'],
    function () {
        Route::resource(
            'authenticate',
            'AuthenticateController',
            ['only' => ['index']]
        );
        Route::post('authenticate', 'AuthenticateController@authenticate');
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

