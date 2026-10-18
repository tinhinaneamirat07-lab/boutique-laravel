<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // GET /admin/login : affiche le formulaire
    public function showLogin()
    {
        return view('admin.login');
    }

    // POST /admin/login : vérifie email + mot de passe
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Auth::attempt compare le mot de passe avec le hash stocké en base
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'البريد أو كلمة السر غير صحيحة.'])->onlyInput('email');
        }

        // Nouvel identifiant de session après connexion (protège contre le vol de session)
        $request->session()->regenerate();

        return redirect()->intended(route('admin.orders.index'));
    }

    // POST /admin/logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
