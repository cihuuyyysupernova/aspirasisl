<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Laporan;
use Carbon\Carbon;

/**
 * Command untuk testing fitur penghapusan laporan lama
 * Digunakan untuk membuat dan menguji laporan lama (>1 tahun)
 * Membantu admin memverifikasi fitur batch delete berfungsi dengan benar
 */
class TestOldLaporans extends Command
{
    /**
     * Nama dan signature command yang bisa dijalankan via artisan
     * Command: php artisan test:old-laporans
     *
     * @var string
     */
    protected $signature = 'test:old-laporans';

    /**
     * Deskripsi dari command yang akan ditampilkan saat help
     * Menjelaskan tujuan dari command ini
     *
     * @var string
     */
    protected $description = 'Test old laporans deletion feature';

    /**
     * Execute the console command.
     * Method utama yang dijalankan saat command dieksekusi
     *
     * @return int Exit code (0 = success, 1 = error)
     */
    public function handle()
    {
        // Tampilkan pesan awal testing
        $this->info('Testing old laporans feature...');

        // Hitung total semua laporan yang ada di database
        $totalLaporans = Laporan::count();
        $this->info("Total laporans: {$totalLaporans}");

        // Hitung laporan yang lebih dari 1 tahun (kandidat untuk dihapus)
        $oldLaporans = Laporan::where('created_at', '<', Carbon::now()->subYear())->count();
        $this->info("Laporans > 1 tahun: {$oldLaporans}");

        // Buat laporan lama untuk testing jika tidak ada laporan lama
        if ($oldLaporans == 0) {
            $this->info('Membuat laporan lama untuk testing...');

            // Buat laporan test dengan created_at 2 tahun + 10 hari yang lalu
            $testLaporan = Laporan::create([
                'judul' => 'Test Laporan Lama',
                'kategori' => 'kerusakan',
                'deskripsi' => 'Ini adalah laporan test yang lebih dari 1 tahun',
                'status' => 'selesai',
                'user_id' => 2, // Ahmad Rizki (user ID 2)
                'created_at' => Carbon::now()->subYears(2)->subDays(10),
                'updated_at' => Carbon::now()->subYears(2)->subDays(10),
            ]);
            $this->info("Created test old laporan with ID: {$testLaporan->id}");

            // Hitung ulang jumlah laporan lama setelah membuat test data
            $oldLaporans = Laporan::where('created_at', '<', Carbon::now()->subYear())->count();
            $this->info("Updated laporans > 1 tahun: {$oldLaporans}");
        }

        // Hitung laporan yang dibuat dalam 30 hari terakhir
        $recentLaporans = Laporan::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        $this->info("Laporans 30 hari terakhir: {$recentLaporans}");

        // Hitung laporan yang dibuat dalam 7 hari terakhir
        $veryRecentLaporans = Laporan::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $this->info("Laporans 7 hari terakhir: {$veryRecentLaporans}");

        // Tampilkan hasil akhir testing
        if ($oldLaporans > 0) {
            // Jika ada laporan lama, tampilkan warning
            $this->warn("Ada {$oldLaporans} laporan lama yang bisa dihapus!");
        } else {
            // Jika tidak ada laporan lama
            $this->info("Tidak ada laporan lama (>1 tahun)");
        }

        // Return exit code 0 untuk sukses
        return 0;
    }
}
