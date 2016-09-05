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

namespace App\Http\Controllers\Web;

use App\Models\App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

/**
 * Class: HomeController
 *
 * @category App\Http\Controllers
 * @package  PageController
 * @author   Jensyn <zhangyongjun369@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://api.xiyoulinux.org
 */
class HomeController extends Controller
{
    /**
     * Index.
     *
     * @param Request $request request object
     *
     * @return Array
     */
    public function index(Request $request)
    {
        $app = App::where('client_id', 'koala')->first();

        return redirect($app->homepage_url);
    }//end index()

    /**
     * appList.
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
    }//end appList()

}//end class
