<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Admin Sekolah',
            'email' => 'admin@sekolah.sch.id',
            'role' => 'admin',
            'identifier' => 'admin@sekolah.sch.id',
            'profile_photo' => null,
        ]);

        // Create Sample Students
        $students = [
            [
                'name' => 'Ahmad Rizki',
                'email' => 'ahmad.rizki@sekolah.sch.id',
                'role' => 'siswa',
                'identifier' => '1234567890',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@sekolah.sch.id',
                'role' => 'siswa',
                'identifier' => '0987654321',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@sekolah.sch.id',
                'role' => 'siswa',
                'identifier' => '1122334455',
            ],
        ];

        foreach ($students as $student) {
            User::create($student);
        }
    }
}
