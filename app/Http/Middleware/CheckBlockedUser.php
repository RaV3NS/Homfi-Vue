<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class CheckBlockedUser
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
        if (auth('api')->check() && auth('api')->user()->status === User::STATUS_BLOCKED) {
            auth('api')->logout();

            return response()->json(['error' => 'Blocked'], 403);
        }

        return $next($request);
    }
}
