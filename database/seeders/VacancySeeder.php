<?php

namespace Database\Seeders;

use App\Models\Vacancy;
use App\Models\User;
use Illuminate\Database\Seeder;

class VacancySeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        Vacancy::create([
            'created_by'           => $admin->id,
            'title'                => 'Software Developer Intern',
            'division'             => 'IT Development',
            'description'          => 'Membantu tim pengembangan dalam pembuatan aplikasi internal.',
            'qualifications'       => 'Mahasiswa S1 Teknik Informatika, menguasai PHP/Laravel, Git.',
            'quota'                => 3,
            'start_date'           => now()->addMonth(),
            'end_date'             => now()->addMonths(4),
            'application_deadline' => now()->addWeeks(3),
            'status'               => 'open',
        ]);

        Vacancy::create([
            'created_by'           => $admin->id,
            'title'                => 'Network Engineer Intern',
            'division'             => 'IT Infrastructure',
            'description'          => 'Membantu monitoring dan pemeliharaan infrastruktur jaringan.',
            'qualifications'       => 'Mahasiswa D3/S1 Teknik Jaringan, familiar dengan Cisco.',
            'quota'                => 2,
            'start_date'           => now()->addMonth(),
            'end_date'             => now()->addMonths(4),
            'application_deadline' => now()->addWeeks(3),
            'status'               => 'open',
        ]);

        Vacancy::create([
            'created_by'           => $admin->id,
            'title'                => 'UI/UX Designer Intern',
            'division'             => 'Digital Experience',
            'description'          => 'Merancang antarmuka pengguna yang intuitif dan menarik.',
            'qualifications'       => 'Mahasiswa D3/S1 Desain/SI, menguasai Figma, memiliki portfolio.',
            'quota'                => 2,
            'start_date'           => now()->addMonth(),
            'end_date'             => now()->addMonths(4),
            'application_deadline' => now()->addWeeks(3),
            'status'               => 'open',
        ]);

        Vacancy::create([
            'created_by'           => $admin->id,
            'title'                => 'Data Analyst Intern',
            'division'             => 'Business Intelligence',
            'description'          => 'Membantu pengolahan dan visualisasi data bisnis.',
            'qualifications'       => 'Mahasiswa S1 Statistika/SI, menguasai SQL & Excel.',
            'quota'                => 2,
            'start_date'           => now()->subMonths(2),
            'end_date'             => now()->addMonths(2),
            'application_deadline' => now()->subMonth(),
            'status'               => 'closed',
        ]);
    }
}
