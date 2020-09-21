<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 07.07.20
 * Time: 13:06
 */

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;

class JsonForceResponse
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
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
