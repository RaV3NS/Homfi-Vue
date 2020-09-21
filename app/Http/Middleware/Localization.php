<?php

namespace App\Http\Middleware;

use Closure;

class Localization
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
        if($request->segment(1) === 'admin'){
            app()->setLocale(config('app.admin_locale'));
        }else{
            // Determine localizaton
            $locale = ($request->hasHeader('X-localization')) ? $request->header('X-localization') : config('app.locale');
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
