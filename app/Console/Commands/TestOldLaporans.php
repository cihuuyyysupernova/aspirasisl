<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Laporan;
use Carbon\Carbon;

class TestOldLaporans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:old-laporans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test old laporans deletion feature';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing old laporans feature...');

        // Total laporans
        $totalLaporans = Laporan::count();
        $this->info("Total laporans: {$totalLaporans}");

        // Laporans lebih dari 1 tahun
        $oldLaporans = Laporan::where('created_at', '<', Carbon::now()->subYear())->count();
        $this->info("Laporans > 1 tahun: {$oldLaporans}");

        // Buat laporan lama untuk testing jika tidak ada
        if ($oldLaporans == 0) {
            $this->info('Membuat laporan lama untuk testing...');
            $testLaporan = Laporan::create([
                'judul' => 'Test Laporan Lama',
                'kategori' => 'kerusakan',
                'deskripsi' => 'Ini adalah laporan test yang lebih dari 1 tahun',
                'status' => 'selesai',
                'user_id' => 2, // Ahmad Rizki
                'created_at' => Carbon::now()->subYears(2)->subDays(10),
                'updated_at' => Carbon::now()->subYears(2)->subDays(10),
            ]);
            $this->info("Created test old laporan with ID: {$testLaporan->id}");

            // Hitung ulang
            $oldLaporans = Laporan::where('created_at', '<', Carbon::now()->subYear())->count();
            $this->info("Updated laporans > 1 tahun: {$oldLaporans}");
        }

        // Laporans 30 hari terakhir
        $recentLaporans = Laporan::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        $this->info("Laporans 30 hari terakhir: {$recentLaporans}");

        // Laporans 7 hari terakhir
        $veryRecentLaporans = Laporan::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $this->info("Laporans 7 hari terakhir: {$veryRecentLaporans}");

        if ($oldLaporans > 0) {
            $this->warn("Ada {$oldLaporans} laporan lama yang bisa dihapus!");
        } else {
            $this->info("Tidak ada laporan lama (>1 tahun)");
        }

        return 0;
    }
}
