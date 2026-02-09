<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

/**
 * Seeder untuk mengisi data user awal
 * Membuat akun admin dan sample siswa untuk testing
 * Digunakan saat setup awal aplikasi atau refresh database
 */
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Method utama yang dijalankan saat seeder dieksekusi
     * Membuat data user default untuk aplikasi
     */
    public function run(): void
    {
        // Buat akun admin default
        // Admin menggunakan email sebagai identifier
        User::create([
            'name' => 'Admin Sekolah',                    // Nama admin
            'email' => 'admin@sekolah.sch.id',            // Email admin (juga sebagai identifier)
            'role' => 'admin',                            // Role admin
            'identifier' => 'admin@sekolah.sch.id',      // Identifier untuk login
            'profile_photo' => null,                      // Belum ada foto profile
        ]);

        // Data sample siswa untuk testing
        // Setiap siswa menggunakan NISN sebagai identifier
        $students = [
            [
                'name' => 'Ahmad Rizki',                      // Nama siswa 1
                'email' => 'ahmad.rizki@sekolah.sch.id',      // Email siswa 1
                'role' => 'siswa',                            // Role siswa
                'identifier' => '1234567890',                  // NISN siswa 1
            ],
            [
                'name' => 'Siti Nurhaliza',                    // Nama siswa 2
                'email' => 'siti.nurhaliza@sekolah.sch.id',    // Email siswa 2
                'role' => 'siswa',                            // Role siswa
                'identifier' => '0987654321',                  // NISN siswa 2
            ],
            [
                'name' => 'Budi Santoso',                      // Nama siswa 3
                'email' => 'budi.santoso@sekolah.sch.id',      // Email siswa 3
                'role' => 'siswa',                            // Role siswa
                'identifier' => '1122334455',                  // NISN siswa 3
            ],
        ];

        // Loop untuk membuat setiap siswa dari array $students
        foreach ($students as $student) {
            User::create($student);
        }
    }
}
