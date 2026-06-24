<?php

namespace Database\Seeders;

use App\Models\InternProfile;
use App\Models\SupervisorProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@telkom-skb.com'],
            ['password' => bcrypt('password'), 'role' => 'admin'],
        );

        $supervisor1 = User::firstOrCreate(
            ['email' => 'supervisor1@telkom-skb.com'],
            ['password' => bcrypt('password'), 'role' => 'supervisor'],
        );
        SupervisorProfile::firstOrCreate(
            ['user_id' => $supervisor1->id],
            ['full_name' => 'Budi Santoso', 'employee_id' => 'SPV-001', 'division' => 'Teknologi Informasi', 'position' => 'IT Manager', 'phone' => '081234567891'],
        );

        $supervisor2 = User::firstOrCreate(
            ['email' => 'supervisor2@telkom-skb.com'],
            ['password' => bcrypt('password'), 'role' => 'supervisor'],
        );
        SupervisorProfile::firstOrCreate(
            ['user_id' => $supervisor2->id],
            ['full_name' => 'Dewi Lestari', 'employee_id' => 'SPV-002', 'division' => 'Sumber Daya Manusia', 'position' => 'HR Coordinator', 'phone' => '081234567892'],
        );

        $intern1 = User::firstOrCreate(
            ['email' => 'intern1@telkom-skb.com'],
            ['password' => bcrypt('password'), 'role' => 'intern'],
        );
        InternProfile::firstOrCreate(
            ['user_id' => $intern1->id],
            ['full_name' => 'Ahmad Rizki', 'phone' => '081234567893', 'address' => 'Jl. Merdeka No. 1, Sukabumi', 'institution_name' => 'Universitas Nusantara', 'institution_type' => 'university', 'major' => 'Teknik Informatika', 'student_id' => 'STU-2024-001'],
        );

        $intern2 = User::firstOrCreate(
            ['email' => 'intern2@telkom-skb.com'],
            ['password' => bcrypt('password'), 'role' => 'intern'],
        );
        InternProfile::firstOrCreate(
            ['user_id' => $intern2->id],
            ['full_name' => 'Siti Nurhaliza', 'phone' => '081234567894', 'address' => 'Jl. Pelajar No. 5, Sukabumi', 'institution_name' => 'Politeknik Negeri', 'institution_type' => 'university', 'major' => 'Manajemen Bisnis', 'student_id' => 'STU-2024-002'],
        );

        $intern3 = User::firstOrCreate(
            ['email' => 'intern3@telkom-skb.com'],
            ['password' => bcrypt('password'), 'role' => 'intern'],
        );
        InternProfile::firstOrCreate(
            ['user_id' => $intern3->id],
            ['full_name' => 'Doni Prasetyo', 'phone' => '081234567895', 'address' => 'Jl. Raya Cisaat No. 10, Sukabumi', 'institution_name' => 'SMK Negeri 1 Sukabumi', 'institution_type' => 'vocational', 'major' => 'Rekayasa Perangkat Lunak', 'student_id' => 'STU-2024-003'],
        );
    }
}
