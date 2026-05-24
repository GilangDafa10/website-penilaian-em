<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Inertia\Inertia;
use Inertia\Response;
use function Termwind\render;

class AuthController extends Controller
{
    public function showLogin(): Response
    {
        return Inertia::render('Login', [
            'errors' => session('errors') ? session('errors')->getBag('default')->getMessages() : [],
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'nim' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('nim', 'password'))) {
            $request->session()->regenerate();

            // Redirect ke dashboard via Inertia
            return redirect()->intended('/dashboard');
        }

        // Kembalikan ke halaman login dengan error (via Inertia)
        return back()->withErrors([
            'nim' => 'The provided credentials do not match our records.',
        ])->onlyInput('nim');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke login via Inertia
        return redirect('/login');
    }

    public function showDashboard(): Response
    {
        $user = Auth::user()->load('role'); // pastikan relasi role() sudah ada di model User
        return Inertia::render('MainContent/Dashboard', [
            'auth' => [
                'user' => $user,
            ],
        ]);
    }
}
