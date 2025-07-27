<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Client;
use Symfony\Component\HttpFoundation\Response;

class CheckBlockedClient
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get client from route parameters
        $clientId = $request->route('client');
        
        if ($clientId) {
            $client = Client::find($clientId);
            
            if ($client && $client->status === 'blocked') {
                // Check if this is an edit, update, or create operation
                $action = $request->route()->getActionMethod();
                $isRestrictedAction = in_array($action, ['edit', 'update', 'store', 'create']);
                
                if ($isRestrictedAction) {
                    return redirect()->back()->with('error', 'Cannot perform this action on a blocked client.');
                }
            }
        }

        return $next($request);
    }
} 