<?php

namespace App\Http\Middleware\Api;

use App\Helpers\ResponseHelper;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!$request->hasHeader('X-Auth-Token'))
            return ResponseHelper::error(['Unauthorized token'], 'Unauthorized', 401);

        $token  = $request->header('X-Auth-Token');
        $user   = User::whereRelation('tokens', 'token', '=', $token)->first();

        if(!$user)
            return ResponseHelper::error(['Unauthorized token'], 'Unauthorized', 401);

        Auth::login($user);
        return $next($request);
    }
}
