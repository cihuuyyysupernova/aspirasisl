<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel feedbacks
 * Menangani data feedback yang diberikan admin untuk laporan siswa
 * Mencatat perubahan status dan komentar admin
 */
class Feedback extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan (explicit table name)
     * Laravel akan menggunakan 'feedbacks' sebagai nama tabel
     *
     * @var string
     */
    protected $table = 'feedbacks';

    /**
     * Atribut yang bisa di-assign secara massal (mass assignable)
     * Melindungi dari mass assignment vulnerability
     *
     * @var array<string>
     */
    protected $fillable = [
        'komentar',           // Komentar atau tanggapan dari admin
        'status_sebelumnya',  // Status laporan sebelum feedback
        'status_setelahnya',  // Status laporan setelah feedback
        'laporan_id',         // ID laporan yang diberi feedback
        'admin_id',           // ID admin yang memberikan feedback
    ];

    /**
     * Relasi many-to-one dengan model Laporan
     * Setiap feedback terkait dengan satu laporan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }

    /**
     * Relasi many-to-one dengan model User sebagai admin
     * Setiap feedback diberikan oleh satu admin
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
