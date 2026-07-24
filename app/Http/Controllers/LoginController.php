<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View
    {
        $setupRequired = !env('AUTH_USERNAME') || !env('AUTH_PASSWORD');
        return view('auth.login', compact('setupRequired'));
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $validUsername = env('AUTH_USERNAME');
        $validPassword = env('AUTH_PASSWORD');

        if (!$validUsername || !$validPassword) {
            return back()->withErrors(['error' => 'Konfigurasi login belum diatur.']);
        }

        if ($request->username === $validUsername && $request->password === $validPassword) {
            session(['auth_single_user' => true]);
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['error' => 'Username atau password salah.']);
    }

    public function logout(): RedirectResponse
    {
        session()->forget('auth_single_user');
        return redirect()->route('login');
    }
}
