<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

// Middleware = code exécuté AVANT chaque page.
// Ici : on lit la langue gardée en session et on l'applique.
class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->session()->get('locale', config('app.locale'));

        if (in_array($locale, ['ar', 'fr'])) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
