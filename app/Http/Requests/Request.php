<?php
/**
 *Descrip the basic request
 *
 * PHP version 5.6
 *
 * @category PHP
 * @package  PHP_Laveral
 * @author   teddyliao <sxliao@foxmail.com>
 * @license  http://xiyoulinux.org BSD Licence
 * @link     http://cs.xiyoulinux.org
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
/**
 *The class for basic request setting
 *
 * PHP version 5.6
 *
 * @category PHP
 * @package  PHP_Laveral
 * @author   teddyliao <sxliao@foxmail.com>
 * @license  http://xiyoulinux.org BSD Licence
 * @link     http://cs.xiyoulinux.org
 */
abstract class Request extends FormRequest
{
    /**
     * Setting response of 403.
     *
     * @return Json
     */
    public function forbiddenResponse()
    {
        return new JsonResponse(['error' => 'Unauthorized'], 401);
        // or return Response::make('Unauthorized', 403);
    }
}
