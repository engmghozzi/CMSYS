<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;
use Symfony\Component\HttpFoundation\Response;

class LogViewActions
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log if user is authenticated and it's a GET request
        if (Auth::check() && $request->isMethod('GET')) {
            $user = Auth::user();
            $routeName = $request->route()->getName();
            $path = $request->path();
            
            // Define which routes should be logged as view actions
            $viewRoutes = [
                'clients.show',
                'clients.index',
                'users.show',
                'users.index',
                'contracts.show',
                'contracts.index',
                'contracts.global',
                'payments.show',
                'payments.index',
                'payments.global',
                'machines.show',
                'machines.index',
                'dashboard',
                'logs.index'
            ];

            if (in_array($routeName, $viewRoutes)) {
                // Extract model information from the route
                $modelType = null;
                $modelId = null;
                $description = "Viewed {$routeName}";

                // Extract model info from route parameters
                if ($request->route()->hasParameter('client')) {
                    $modelType = 'Client';
                    $modelId = $request->route()->parameter('client');
                    $description = "Viewed Client #{$modelId}";
                } elseif ($request->route()->hasParameter('user')) {
                    $modelType = 'User';
                    $modelId = $request->route()->parameter('user');
                    $description = "Viewed User #{$modelId}";
                } elseif ($request->route()->hasParameter('contract')) {
                    $modelType = 'Contract';
                    $modelId = $request->route()->parameter('contract');
                    $description = "Viewed Contract #{$modelId}";
                } elseif ($request->route()->hasParameter('payment')) {
                    $modelType = 'Payment';
                    $modelId = $request->route()->parameter('payment');
                    $description = "Viewed Payment #{$modelId}";
                } elseif ($request->route()->hasParameter('machine')) {
                    $modelType = 'Machine';
                    $modelId = $request->route()->parameter('machine');
                    $description = "Viewed Machine #{$modelId}";
                }

                Log::create([
                    'user_id' => $user->id,
                    'action_type' => 'view',
                    'model_type' => $modelType,
                    'model_id' => $modelId,
                    'description' => $description,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }
        }

        return $response;
    }
}
