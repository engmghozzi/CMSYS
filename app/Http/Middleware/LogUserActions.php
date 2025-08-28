<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\LogService;

class LogUserActions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Closure): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Log the request if it's a login or logout action
        if ($request->is('login') && $request->isMethod('post')) {
            // This will be handled by the login event listener
        } elseif ($request->is('logout') && $request->isMethod('post')) {
            // This will be handled by the logout event listener
        }

        return $next($request);
    }
}
