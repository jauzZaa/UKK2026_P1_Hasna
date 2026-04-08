<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'email' => 'admin@ukk2026.com',
                'password' => Hash::make('123456'),
                'role' => 'Admin',
                
                
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'email' => 'petugas@ukk2026.com',
                'password' => Hash::make('123456'),
                'role' => 'Employee',
                
                
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'email' => 'peminjam@ukk2026.com',
                'password' => Hash::make('123456'),
                'role' => 'User',
                
                
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('user_details')->insert([
            [
                'nik' => '3174010101010001',
                'user_id' => 1,
                'name' => 'Admin Sistem',
                'no_hp' => '081111111111',
                'address' => 'Jakarta',
                'birth_date' => '1990-01-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => '3174010101010002',
                'user_id' => 2,
                'name' => 'Petugas Lapangan',
                'no_hp' => '082222222222',
                'address' => 'Bandung',
                'birth_date' => '1995-05-05',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => '3174010101010003',
                'user_id' => 3,
                'name' => 'User Peminjam',
                'no_hp' => '083333333333',
                'address' => 'Bogor',
                'birth_date' => '2000-10-10',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // DB::table('app_configs')->insert([
        //     [
        //         'name' => "Peminjaman", // sementara integer
        //         'late_point' => 10,
        //         'broken_point' => 50,
        //         'lost_point' => 100,
        //         'late_fine' => 5,
        //         'broken_fine' => 50,
        //         'lost_fine' => 100,
        //         'updated_at' => now(),
        //     ],
        // ]);
    }
}
