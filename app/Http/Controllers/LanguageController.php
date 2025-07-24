<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Switch the application language.
     */
    public function saveLanguage($lang) {
        if (in_array($lang, ['en', 'ar'])) {
            // Store in session
            Session::put('applocale', $lang);
            
            // Set the application locale
            App::setLocale($lang);
            
            // Also set config for consistency
            config(['app.locale' => $lang]);
            
            // Flash a success message
            Session::flash('success', __('Language switched successfully'));
        }
        return redirect()->route('settings.language');
    }
}