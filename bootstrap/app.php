<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

/*
|--------------------------------------------------------------------------
| Bootstrap Application Configuration
|--------------------------------------------------------------------------
|
| File ini mengkonfigurasi aplikasi Laravel saat startup
| Mendefinisikan routing, middleware, dan exception handling
| Ini adalah entry point untuk konfigurasi aplikasi
|
*/

// Konfigurasi dan buat instance aplikasi Laravel
return Application::configure(basePath: dirname(__DIR__))
    // Konfigurasi routing
    ->withRouting(
        web: __DIR__.'/../routes/web.php',     // Route untuk web (HTTP requests)
        commands: __DIR__.'/../routes/console.php', // Route untuk artisan commands
        health: '/up',                         // Health check endpoint
    )
    // Konfigurasi middleware
    ->withMiddleware(function (Middleware $middleware): void {
        // Registrasi middleware alias
        // 'role' alias akan merujuk ke RoleMiddleware class
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    // Konfigurasi exception handling
    ->withExceptions(function (Exceptions $exceptions): void {
        // Konfigurasi custom exception handling bisa ditambahkan di sini
        // Saat ini masih kosong (menggunakan default Laravel)
    })
    // Buat instance aplikasi
    ->create();
