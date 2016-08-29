<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use LucaDegasperi\OAuth2Server\Exceptions\NoActiveAccessTokenException;
use League\OAuth2\Server\Exception\InvalidRequestException;
use League\OAuth2\Server\Exception\InvalidScopeException;
use League\OAuth2\Server\Exception\AccessDeniedException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        $url = $request->url();
        $validator = Validator::make(
            array('url' => $url),
            array('url' => 'regex:/' . env('API_DOMAIN') . '/')
        );

        if ($validator->fails() === true) {
          var_dump($e);
            return parent::render($request, $e);
        } else {
          // Define the response
          $response = [
              'error' => 'Sorry, something went wrong.'
          ];

          // Default response of 400
          $status = 400;
          if ($e instanceof NotFoundHttpException) {
              $response['error'] = '访问的路径不存在';
              $status = 404;
          } else if ($e instanceof NoActiveAccessTokenException || $e instanceof InvalidRequestException) {
              $response['error'] = '请求中不含 access token, 或者 access token 不合法';
              $status = 422;
          } else if ($e instanceof InvalidScopeException) {
              $response['error'] = '没有权限。请确认应用是否具有该操作权限，并检查授权时所使用的 scope 参数是否正确';
              $status = 422;
          } else if ($e instanceof AccessDeniedException) {
             $response['error'] = 'access token 已失效，请重新授权';
             $status = 422;
          } else if ($e instanceof ModelNotFoundException) {
              $response['error'] = '目标资源不存在';
              $status = 404;
          }

          // If the app is in debug mode
          if (config('app.debug'))
          {
              // Add the exception class name, message and stack trace to response
              $response['exception'] = get_class($e);
              $response['message'] = $e->getMessage();
              $response['trace'] = $e->getTrace();
          }

          // If this exception is an instance of HttpException
          if ($this->isHttpException($e))
          {
              $status = $e->getStatusCode();
          }

          return response()->json($response, $status);
        }
    }
}
