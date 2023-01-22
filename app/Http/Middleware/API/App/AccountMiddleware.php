<?php

namespace App\Http\Middleware\API\App;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class AccountMiddleware
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
        $user = User::find(auth()->user()->id);
        $user->load(['accounts']);
        $actual_account = $user->accounts()->first();
        $inputs = $request->all();
        $inputs['actualAccount'] = $actual_account;
        $request->replace($inputs);
        return $next($request);
    }
}
