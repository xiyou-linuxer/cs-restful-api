<?php

/**
 * File: app/Http/Controllers/UserController.php
 *
 * User controller
 *
 * PHP version 5.5.9
 *
 * @category App\Http\Controllers
 * @package  UserController
 * @author   Jensyn <zhangyongjun369@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://api.xiyoulinux.org
 */

namespace App\Http\Controllers;

use App\Models\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class: PageController
 *
 * @category App\Http\Controllers
 * @package  PageController
 * @author   Jensyn <zhangyongjun369@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://api.xiyoulinux.org
 */
class PageController extends Controller
{
    /**
     * Index.
     *
     * @param Request $request request object
     *
     * @return Array
     */
    public function appList(Request $request)
    {
        $apps = App::distinct()->orderBy('created_at', 'desc');

        $apps = $apps->get();

        return view('applist', ['apps' => $apps]);
    }//end index()
}//end class
