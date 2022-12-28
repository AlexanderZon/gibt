<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CrawlerAllowRequestMiddleware
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
        if($request->server('HTTP_HOST') == 'gibt'){
            return $next($request);
        } else {
            return response('You are not allowed to access this endpoint', 403);
        }
    }
}