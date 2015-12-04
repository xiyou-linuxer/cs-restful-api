<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

abstract class Request extends FormRequest
{
    public function forbiddenResponse()
    {
        return new JsonResponse(['error' => 'Unauthorized'], 401);
        // or return Response::make('Unauthorized', 403);
    }
}
