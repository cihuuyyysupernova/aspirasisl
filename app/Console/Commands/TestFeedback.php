<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Feedback;
use App\Models\Laporan;
use App\Models\User;

class TestFeedback extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:feedback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test feedback creation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $laporan = Laporan::first();
        $admin = User::where('role', 'admin')->first();

        if (!$laporan) {
            $this->error('No laporan found');
            return 1;
        }

        if (!$admin) {
            $this->error('No admin found');
            return 1;
        }

        try {
            $feedback = Feedback::create([
                'komentar' => 'Test feedback from command',
                'status_sebelumnya' => 'menunggu',
                'status_setelahnya' => 'diproses',
                'laporan_id' => $laporan->id,
                'admin_id' => $admin->id,
            ]);

            $this->info('Feedback created successfully with ID: ' . $feedback->id);
            return 0;
        } catch (\Exception $e) {
            $this->error('Error creating feedback: ' . $e->getMessage());
            return 1;
        }
    }
}
