<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Feedback;
use App\Models\Laporan;
use App\Models\User;

/**
 * Command untuk testing fitur pembuatan feedback
 * Digunakan untuk membuat feedback test dan memverifikasi relasi database
 * Membantu developer memastikan fitur feedback berfungsi dengan benar
 */
class TestFeedback extends Command
{
    /**
     * Nama dan signature command yang bisa dijalankan via artisan
     * Command: php artisan test:feedback
     *
     * @var string
     */
    protected $signature = 'test:feedback';

    /**
     * Deskripsi dari command yang akan ditampilkan saat help
     * Menjelaskan tujuan dari command ini
     *
     * @var string
     */
    protected $description = 'Test feedback creation';

    /**
     * Execute the console command.
     * Method utama yang dijalankan saat command dieksekusi
     *
     * @return int Exit code (0 = success, 1 = error)
     */
    public function handle()
    {
        // Ambil laporan pertama dari database untuk test
        $laporan = Laporan::first();

        // Ambil admin pertama dari database untuk test
        $admin = User::where('role', 'admin')->first();

        // Validasi: Pastikan ada laporan di database
        if (!$laporan) {
            $this->error('No laporan found');
            return 1; // Exit code error
        }

        // Validasi: Pastikan ada admin di database
        if (!$admin) {
            $this->error('No admin found');
            return 1; // Exit code error
        }

        try {
            // Buat feedback baru dengan data test
            $feedback = Feedback::create([
                'komentar' => 'Test feedback from command',
                'status_sebelumnya' => 'menunggu',
                'status_setelahnya' => 'diproses',
                'laporan_id' => $laporan->id,
                'admin_id' => $admin->id,
            ]);

            // Tampilkan pesan sukses dengan ID feedback yang dibuat
            $this->info('Feedback created successfully with ID: ' . $feedback->id);
            return 0; // Exit code sukses
        } catch (\Exception $e) {
            // Tangkap dan tampilkan error jika terjadi exception
            $this->error('Error creating feedback: ' . $e->getMessage());
            return 1; // Exit code error
        }
    }
}
