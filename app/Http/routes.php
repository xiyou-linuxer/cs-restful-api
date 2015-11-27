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
    '/', function () {
    return view('test');
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
        route::post('/{id}','CsUserController@create');

        /*update online info*/
        route::put('/{id}','CsUserController@update');
    }
);

Route::group(
    [
    'prefix' => 'admins', 'namespace' => 'Admin'
    ], 
    function () {}
);

Route::group(
    [
    'prefix' => 'users', 'namespace' => 'User'
    ], 
    function () {
        /*get all users' information*/
        route::get('/', 'CsUserController@index');

        /*get user info from id*/
        route::get('/{id}', 'CsUserController@show');

        /*create member*/
        route::post('/', 'CsUserController@create');

        /*deliver privilege*/
        route::post('/privilege', 'CsUserController@privilege');

        /*delete member*/
        route::delete('/{id}', 'CsUserController@destroy');

        /*update personal information*/
        route::put('/{id}', 'CsUserController@update');
        
        /*reset password*/
        route::put('/{id}/password', 'CsUserController@resetpd');
    }
);
