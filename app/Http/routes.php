<?php

/**
 * Application Routes
 *
 * Here is where you can register all of the routes for an application.
 * It is a breeze. Simply tell Lumen the URIs it should respond to
 * and give it the Closure to call when that URI is requested.
 *
 * PHP version 5.5.9
 *
 * @category App/Http
 * @package  Routes
 * @author   Jensyn <zhangyongjun369@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://api.xiyoulinux.org
 */

Route::group(
    [
        'middleware' => ['auth'],
    ],
    function () {
        Route::get('/', function () {
            return response()->json(['message' => 'hello, adam']);
        });

        Route::get('/applist', 'PageController@appList');
    }
);

Route::group(['prefix' => 'auth'], function () {
    Route::auth();
});

Route::get('oauth/authorize', 'Auth\OAuthController@getAuthorize')
    ->name('oauth.authorize.get');
Route::post('oauth/authorize', 'Auth\OAuthController@postAuthorize')
    ->name('oauth.authorize.post');
Route::post('oauth/access_token', 'Auth\OAuthController@accessToken');

Route::group(
    [
        'middleware' => ['api', 'oauth'],
    ],
    function () {
        Route::get('/auth/user', 'Auth\OAuthController@getUser');
    }
);

Route::group(
    [
        'middleware' => ['api', 'oauth'],
    ],
    function () {
        Route::get('/users', 'UserController@index');
        Route::post('/users', 'UserController@create');
        Route::put('/users/{id}', 'UserController@update');
        Route::get('/users/{id}', 'UserController@show');
        Route::delete('/users/{id}', 'UserController@destory');

        Route::get('/news', 'NewsController@index');
        Route::get('/news/{id}', 'NewsController@show');
        Route::post('/news', 'NewsController@create');
        Route::put('/news/{id}', 'NewsController@update');
        Route::delete('/news/{id}', 'NewsController@destroy');

        Route::get('/messages', 'MessageController@index');
        Route::get('/messages/{id}', 'MessageController@show');
        Route::post('/messages', 'MessageController@create');
        Route::put('/messages/{id}', 'MessageController@update');
        Route::delete('/messages/{id}', 'MessageController@destroy');

        Route::get('/apps', 'AppController@index');
        Route::get('/apps/{id}', 'AppController@show');
        Route::post('/apps', 'AppController@create');
        Route::put('/apps/{id}', 'AppController@update');
        Route::put('/apps/{id}/confirm', 'AppController@confirm');
        Route::delete('/apps/{id}', 'AppController@destroy');
    }
);
