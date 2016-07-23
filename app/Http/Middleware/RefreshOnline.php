<?php

namespace App\Http\Middleware;

use Closure;
use Authorizer;
use App\Models\User;

class RefreshOnline
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $operatorId = (Integer)Authorizer::getResourceOwnerId();

        $user = User::find($operatorId);
        $user->update([
          'online_at' => date('Y-m-d H:i:s', time())
        ]);

        return $response;
    }
}
