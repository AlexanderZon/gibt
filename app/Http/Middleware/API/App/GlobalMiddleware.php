<?php

namespace App\Http\Middleware\API\App;

use Closure;
use Illuminate\Http\Request;

class GlobalMiddleware
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
        if(config('app.debug') == true AND !$request->headers->has('Access-Control-Allow-Origin')){
            // header('Access-Control-Allow-Origin: '.$request->server('HTTP_ORIGIN'));
        }
        if(config('app.debug') == true AND !$request->headers->has('Access-Control-Allow-Credentials')){
            // header('Access-Control-Allow-Credentials: true');
        }
        return $next($request);
    }
}
