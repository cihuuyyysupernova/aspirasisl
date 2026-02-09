<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model untuk tabel users
 * Mewarisi Authenticatable untuk fitur autentikasi Laravel
 * Menangani data user (siswa dan admin) dengan role-based access
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Atribut yang bisa di-assign secara massal (mass assignable)
     * Melindungi dari mass assignment vulnerability
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',          // Nama lengkap user
        'email',         // Email user (untuk admin)
        'role',          // Role user (admin/siswa)
        'identifier',    // Identifier unik (NISN untuk siswa, email untuk admin)
        'profile_photo', // Path foto profile
    ];

    /**
     * Casting atribut ke tipe data tertentu
     * Mengkonversi tipe data saat diambil dari database
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [];
    }

    /**
     * Relasi one-to-many dengan model Laporan
     * User bisa memiliki banyak laporan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function laporans()
    {
        return $this->hasMany(Laporan::class);
    }

    /**
     * Relasi one-to-many dengan model Feedback sebagai admin
     * Admin bisa memberikan banyak feedback
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'admin_id');
    }

    /**
     * Method helper untuk mengecek apakah user adalah admin
     * Memudahkan pengecekan role tanpa perlu akses langsung atribut
     *
     * @return bool True jika user adalah admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Method helper untuk mengecek apakah user adalah siswa
     * Memudahkan pengecekan role tanpa perlu akses langsung atribut
     *
     * @return bool True jika user adalah siswa
     */
    public function isSiswa()
    {
        return $this->role === 'siswa';
    }
}
