<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = [
        'komentar',
        'status_sebelumnya',
        'status_setelahnya',
        'laporan_id',
        'admin_id',
    ];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
