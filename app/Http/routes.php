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
    Auth::logout();
    return redirect('auth/login');
});

Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

Route::get('oauth/authorize', 'Auth\OAuthController@getAuthorize')
    ->name('oauth.authorize.get');
Route::post('oauth/authorize', 'Auth\OAuthController@postAuthorize')
    ->name('oauth.authorize.post');
Route::post('oauth/access_token', 'Auth\OAuthController@accessToken');

Route::group(
    [
        //'middleware' => ['api', 'oauth'],
    ],
    function () {
        Route::get('/auth/user', 'Auth\OAuthController@getUser');

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

        Route::get('/messages', 'MessagesController@index');
        Route::get('/messages/{id}', 'MessagesController@show');
        Route::post('/messages', 'MessagesController@create');
        Route::put('/messages/{id}', 'MessagesController@update');
        Route::delete('/messages/{id}', 'MessagesController@destroy');

        Route::get('/apps', 'AppsController@index');
        Route::get('/apps/{id}', 'AppsController@show');
        Route::post('/apps/{id}', 'AppsController@create');
        Route::put('/apps/{id}', 'AppsController@update');
        Route::delete('/apps/{id}', 'AppsController@destroy');
    }
);
