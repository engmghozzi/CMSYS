<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCanManageFeatures
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Only super_admin and admin can manage features and roles
        if (!in_array($user->role->name, ['super_admin', 'admin'])) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized. Only administrators can manage features and roles.'], 403);
            }
            
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to manage features and roles. Only administrators can access this area.');
        }

        return $next($request);
    }
}
