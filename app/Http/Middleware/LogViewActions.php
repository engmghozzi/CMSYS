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
                    $client = $request->route()->parameter('client');
                    $modelId = is_object($client) ? $client->getKey() : (is_array($client) ? $client['id'] ?? null : $client);
                    $description = "Viewed Client #{$modelId}";
                } elseif ($request->route()->hasParameter('user')) {
                    $modelType = 'User';
                    $user = $request->route()->parameter('user');
                    $modelId = is_object($user) ? $user->getKey() : (is_array($user) ? $user['id'] ?? null : $user);
                    $description = "Viewed User #{$modelId}";
                } elseif ($request->route()->hasParameter('contract')) {
                    $modelType = 'Contract';
                    $contract = $request->route()->parameter('contract');
                    $modelId = is_object($contract) ? $contract->getKey() : (is_array($contract) ? $contract['id'] ?? null : $contract);
                    $description = "Viewed Contract #{$modelId}";
                } elseif ($request->route()->hasParameter('payment')) {
                    $modelType = 'Payment';
                    $payment = $request->route()->parameter('payment');
                    $modelId = is_object($payment) ? $payment->getKey() : (is_array($payment) ? $payment['id'] ?? null : $payment);
                    $description = "Viewed Payment #{$modelId}";
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
