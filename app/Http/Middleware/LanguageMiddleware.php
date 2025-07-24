<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Force session to start if not already started
        if (!Session::isStarted()) {
            Session::start();
        }
        
        // Get locale from session, fallback to config
        $locale = Session::get('applocale', config('app.locale'));
        
        // Ensure the locale is supported
        if (!in_array($locale, ['en', 'ar'])) {
            $locale = config('app.locale');
        }
        
        // Set the application locale
        App::setLocale($locale);
        
        // Also set the config locale to ensure consistency
        config(['app.locale' => $locale]);
        
        // Set the HTML lang attribute for better SEO and accessibility
        $request->attributes->set('html_lang', $locale);
        
        return $next($request);
    }
}