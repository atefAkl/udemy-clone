<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get supported locales from config
        $supportedLocales = array_keys(config('app.supported_locales'));

        // Priority order for locale detection:
        // 1. URL parameter 'lang'
        // 2. Session stored locale
        // 3. User's saved preference (if authenticated)
        // 4. Browser Accept-Language header
        // 5. Default app locale

        $locale = null;

        // Check URL parameter first
        if ($request->has('lang') && in_array($request->get('lang'), $supportedLocales)) {
            $locale = $request->get('lang');
            Session::put('locale', $locale);

            // Save to user preferences if authenticated
            if (Auth::check()) {
                Auth::user()->update(['preferred_language' => $locale]);
            }
        }
        // Check session
        elseif (Session::has('locale') && in_array(Session::get('locale'), $supportedLocales)) {
            $locale = Session::get('locale');
        }
        // Check user preference if authenticated
        elseif (
            Auth::check() && Auth::user()->preferred_language &&
            in_array(Auth::user()->preferred_language, $supportedLocales)
        ) {
            $locale = Auth::user()->preferred_language;
            Session::put('locale', $locale);
        }
        // Check browser language
        elseif ($request->header('Accept-Language')) {
            $browserLocales = $this->parseAcceptLanguage($request->header('Accept-Language'));
            foreach ($browserLocales as $browserLocale) {
                if (in_array($browserLocale, $supportedLocales)) {
                    $locale = $browserLocale;
                    Session::put('locale', $locale);
                    break;
                }
            }
        }

        // Fall back to default if no locale found
        if (!$locale || !in_array($locale, $supportedLocales)) {
            $locale = config('app.locale');
        }

        // Set the application locale
        App::setLocale($locale);

        return $next($request);
    }

    /**
     * Parse Accept-Language header
     */
    private function parseAcceptLanguage(string $acceptLanguage): array
    {
        $languages = [];

        foreach (explode(',', $acceptLanguage) as $lang) {
            $lang = trim($lang);
            if (strpos($lang, ';') !== false) {
                $lang = explode(';', $lang)[0];
            }

            // Convert locale format (en-US -> en, ar-SA -> ar)
            if (strpos($lang, '-') !== false) {
                $lang = explode('-', $lang)[0];
            }

            $languages[] = strtolower($lang);
        }

        return array_unique($languages);
    }
}
