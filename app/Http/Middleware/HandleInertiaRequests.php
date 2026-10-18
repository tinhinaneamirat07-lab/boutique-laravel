<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

// Inertia : données partagées avec TOUTES les pages React (props globales)
class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';   // resources/views/app.blade.php

    public function share(Request $request): array
    {
        $locale = app()->getLocale();
        $frPath = lang_path('fr.json');

        return array_merge(parent::share($request), [
            'locale' => $locale,
            // Dictionnaire arabe -> français (le même que __() côté Laravel)
            'translations' => $locale === 'fr' && file_exists($frPath)
                ? json_decode(file_get_contents($frPath), true)
                : (object) [],
            'flash' => ['success' => fn () => $request->session()->get('success')],
        ]);
    }
}
