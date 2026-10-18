<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // SetLocale s'exécute sur toutes les pages web
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\HandleInertiaRequests::class,  // après SetLocale : la langue est déjà choisie
        ]);
        // Visiteur non connecté qui ouvre une page admin -> page de connexion
        $middleware->redirectGuestsTo(fn () => route('admin.login'));
        // Admin déjà connecté qui ouvre la page de connexion -> liste des commandes
        $middleware->redirectUsersTo(fn () => route('admin.orders.index'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
