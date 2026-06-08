<?php

namespace Core\Http\Middleware;

use Closure;
use Session;
use Illuminate\Http\Request;

class SetApiRequestLanguage
{

    public function handle(Request $request, Closure $next)
    {

        if ($request->hasHeader('Accept-Language')) {
            $acceptLanguage = $request->header('Accept-Language');
            $locale = substr($acceptLanguage, 0, strpos($acceptLanguage, ',') ?: strlen($acceptLanguage));
            $locale = str_replace('-', '_', $locale);
        } elseif (env('DEFAULT_LANGUAGE') != null) {
            $locale = env('DEFAULT_LANGUAGE');
        } else {
            $locale = 'en';
        }

        app()->setLocale($locale);
        Session::put('api_locale', $locale);
        return $next($request);
    }
}
