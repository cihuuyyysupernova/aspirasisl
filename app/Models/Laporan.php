<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel laporans
 * Menangani data laporan aspirasi dan kerusakan yang dibuat oleh siswa
 * Mendukung relasi dengan user dan feedback
 */
class Laporan extends Model
{
    use HasFactory;

    /**
     * Atribut yang bisa di-assign secara massal (mass assignable)
     * Melindungi dari mass assignment vulnerability
     *
     * @var array<string>
     */
    protected $fillable = [
        'judul',        // Judul laporan
        'kategori',     // Kategori (aspirasi/kerusakan)
        'deskripsi',    // Deskripsi detail laporan
        'lokasi',       // Lokasi kejadian (opsional)
        'foto',         // Path foto bukti (opsional)
        'status',       // Status laporan (menunggu/diproses/selesai)
        'user_id',      // ID user yang membuat laporan
    ];

    /**
     * Relasi many-to-one dengan model User
     * Setiap laporan dimiliki oleh satu user (siswa)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi one-to-many dengan model Feedback
     * Setiap laporan bisa memiliki banyak feedback dari admin
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    /**
     * Accessor untuk mendapatkan warna berdasarkan status
     * Digunakan untuk styling status badge di view
     *
     * @return string Warna status (gray/yellow/green)
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'menunggu' => 'gray',   // Status menunggu = warna abu-abu
            'diproses' => 'yellow', // Status diproses = warna kuning
            'selesai' => 'green',   // Status selesai = warna hijau
            default => 'gray'       // Default = warna abu-abu
        };
    }
}
