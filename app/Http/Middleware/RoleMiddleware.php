<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk membatasi akses berdasarkan role user
 * Memastikan hanya user dengan role tertentu yang bisa mengakses route
 * Digunakan untuk memisahkan akses antara admin dan siswa
 */
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Memeriksa role user sebelum melanjutkan request
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  Role yang diizinkan untuk mengakses route
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Validasi 1: Pastikan user sudah login
        // Jika belum login, redirect ke halaman login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Validasi 2: Periksa role user
        // Jika role user tidak sesuai dengan role yang diizinkan, abort dengan 403
        if (auth()->user()->role !== $role) {
            abort(403, 'Unauthorized action.');
        }

        // Jika semua validasi lulus, lanjutkan request ke controller
        return $next($request);
    }
}
