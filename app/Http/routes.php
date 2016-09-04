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
        'namespace' => 'Web',
        'domain' => env('AUTH_DOMAIN', 'sso.xiyoulinux.org')
    ],
    function () {
        Route::auth();

        Route::get('oauth/authorize', 'Auth\OAuthController@getAuthorize')
            ->name('oauth.authorize.get');
        Route::post('oauth/authorize', 'Auth\OAuthController@postAuthorize')
            ->name('oauth.authorize.post');
        Route::post('/oauth/access_token', 'Auth\OAuthController@getAccessToken');

        Route::group(
            [
                'middleware' => ['auth', 'csrf'],
            ],
            function () {
                Route::get('/', function () {
                    return response()->json(['message' => 'hello, adam']);
                });

                Route::get('/applist', 'PageController@appList');
            }
        );
    }
);

Route::group(
    [
        'namespace' => 'Api',
        'domain' => env('API_DOMAIN', 'api.xiyoulinux.org')
    ],
    function () {
        // get login user info
        Route::get('/me', ['middleware' => 'oauth', 'uses' => 'UserController@getAuthUser']);

        // get user info
        Route::group(
            [
                'middleware' => ['oauth:all|all_read|user_info_read']
            ],
            function () {
                Route::get('/users', 'UserController@index');
                Route::get('/users/{id}',  'UserController@show');
            }
        );

        // write user info
        Route::group(
            [
                'middleware' => ['oauth:all|all_write|user_info_write']
            ],
            function () {
                Route::post('/users', 'UserController@create');
                Route::put('/users/{id}', 'UserController@update');
                Route::delete('/users/{id}', 'UserController@destory');
            }
        );

        // get news info
        Route::group(
            [
                'middleware' => ['oauth:all|all_read|news_info_read']
            ],
            function () {
                Route::get('/news', 'NewsController@index');
                Route::get('/news/{id}', 'NewsController@show');
            }
        );

        // write news info
        Route::group(
            [
                'middleware' => ['oauth:all|all_write|news_info_write']
            ],
            function () {
                Route::post('/news', 'NewsController@create');
                Route::put('/news/{id}', 'NewsController@update');
                Route::delete('/news/{id}', 'NewsController@destroy');
                Route::post('/news/{id}/favors', 'NewsController@favor');
                Route::get('/news/{id}/comments', 'NewsController@getComments');
                Route::post('/news/{id}/comments', 'NewsController@createComment');
            }
        );

        // get message info
        Route::group(
            [
                'middleware' => ['oauth:all|all_read|message_info_read']
            ],
            function () {
                Route::get('/messages', 'MessageController@index');
                Route::get('/messages/{id}', 'MessageController@show');
            }
        );

        // write message info
        Route::group(
            [
                'middleware' => ['oauth:all|all_write|message_info_write']
            ],
            function () {
                Route::post('/messages', 'MessageController@create');
                Route::put('/messages/{id}', 'MessageController@update');
                Route::delete('/messages/{id}', 'MessageController@destroy');
            }
        );

        // get app info
        Route::group(
            [
                'middleware' => ['oauth:all|all_read|app_info_get']
            ],
            function () {
                Route::get('/apps', 'AppController@index');
                Route::get('/apps/{id}', 'AppController@show');
            }
        );

        Route::group(
            [
                'middleware' => ['oauth:all|all_write|app_info_write']
            ],
            function () {
                Route::post('/apps', 'AppController@create');
                Route::put('/apps/{id}', 'AppController@update');
                Route::put('/apps/{id}/confirm', 'AppController@confirm');
                Route::put('/apps/{id}/reject', 'AppController@reject');
                Route::put('/apps/{id}/secret', 'AppController@refreshSecret');
                Route::delete('/apps/{id}', 'AppController@destroy');
            }
        );

        Route::get('/avatar_url', 'HelperController@getAvatarUrlByEmail');
        Route::get('/oauth/scopes', 'HelperController@getOAuthScopes');
    }
);
