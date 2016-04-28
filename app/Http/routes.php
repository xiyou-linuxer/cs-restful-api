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

Route::get('/', function () {
    return view('welcome');
});

Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

Route::get('oauth/authorize', 'Auth\OauthController@getAuthorize')
    ->name('oauth.authorize.get');
Route::post('oauth/authorize', 'Auth\OauthController@postAuthorize')
    ->name('oauth.authorize.post');
Route::post('oauth/access_token', 'Auth\OauthController@accessToken');

Route::group(
    [
        'middleware' => ['api', 'oauth'],
    ],
    function () {
        Route::get('/auth/user', 'Auth\OauthController@getUser');

        Route::get('/users', 'UserController@index');
        Route::post('/users', 'UserController@create');
        Route::put('/users/{id}', 'UserController@update');
        Route::get('/users/{id}', 'UserController@show');
        Route::delete('/users/{id}', 'UserController@destory');
    }
);
