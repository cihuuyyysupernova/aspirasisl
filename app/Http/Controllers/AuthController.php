<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
        ]);

        $identifier = $request->identifier;

        // Cari user berdasarkan identifier (NISN untuk siswa, email untuk admin)
        $user = User::where('identifier', $identifier)->first();

        if (!$user) {
            return back()->withErrors([
                'identifier' => 'Identifier tidak ditemukan.',
            ])->withInput();
        }

        // Login user tanpa password
        Auth::login($user);

        $request->session()->regenerate();

        // Redirect berdasarkan role
        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        } else {
            return redirect()->intended(route('siswa.dashboard'));
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
