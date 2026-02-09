<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

/**
 * Controller untuk mengelola autentikasi user
 * Menangani login dan logout tanpa password menggunakan identifier
 * Mendukung login untuk siswa (NISN) dan admin (email)
 */
class AuthController extends Controller
{
    /**
     * Menampilkan halaman login
     * Menampilkan form login dengan input identifier
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Memproses login user
     * Melakukan autentikasi berdasarkan identifier tanpa password
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validasi input identifier (wajib diisi)
        $request->validate([
            'identifier' => 'required|string',
        ]);

        // Ambil identifier dari request
        $identifier = $request->identifier;

        // Cari user berdasarkan identifier
        // Untuk siswa: identifier adalah NISN
        // Untuk admin: identifier adalah email
        $user = User::where('identifier', $identifier)->first();

        // Validasi: Jika user tidak ditemukan, kembali ke form dengan error
        if (!$user) {
            return back()->withErrors([
                'identifier' => 'Identifier tidak ditemukan.',
            ])->withInput();
        }

        // Login user tanpa password (sesuai requirement sistem)
        Auth::login($user);

        // Regenerate session untuk keamanan
        $request->session()->regenerate();

        // Redirect berdasarkan role user
        if ($user->role === 'admin') {
            // Jika admin, redirect ke dashboard admin
            return redirect()->intended(route('admin.dashboard'));
        } else {
            // Jika siswa, redirect ke dashboard siswa
            return redirect()->intended(route('siswa.dashboard'));
        }
    }

    /**
     * Memproses logout user
     * Menghapus session dan redirect ke halaman login
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Logout user dari sistem
        Auth::logout();

        // Invalidate session untuk keamanan
        $request->session()->invalidate();

        // Regenerate CSRF token
        $request->session()->regenerateToken();

        // Redirect ke halaman login
        return redirect()->route('login');
    }
}
