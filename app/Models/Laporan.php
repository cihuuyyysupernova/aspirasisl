<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'kategori',
        'deskripsi',
        'lokasi',
        'foto',
        'status',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'menunggu' => 'gray',
            'diproses' => 'yellow',
            'selesai' => 'green',
            default => 'gray'
        };
    }
}
