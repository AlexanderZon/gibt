<?php

namespace App\Http\Middleware\API\Crawler;

use Closure;
use Illuminate\Http\Request;

class AllowRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(config('app.env') == 'production'){
            if($request->server('HTTP_HOST') == 'gibt'){
                return $next($request);
            } else {
                return response('You are not allowed to access this endpoint', 403);
            }
        } else {
            return $next($request);
        }
    }
}
