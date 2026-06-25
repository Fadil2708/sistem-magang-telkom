<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Evaluation;
use App\Models\FinalReport;
use App\Models\InternProfile;
use App\Models\Internship;
use App\Models\Logbook;
use App\Models\SupervisorProfile;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Seeder;

class PendingCertificateInternSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@telkom-skb.com'],
            ['password' => bcrypt('password'), 'role' => 'admin', 'email_verified_at' => now()],
        );

        $supervisor = User::firstOrCreate(
            ['email' => 'supervisor4@telkom-skb.com'],
            ['password' => bcrypt('password'), 'role' => 'supervisor', 'email_verified_at' => now()],
        );
        SupervisorProfile::firstOrCreate(
            ['user_id' => $supervisor->id],
            [
                'full_name' => 'Siti Rahmawati',
                'employee_id' => 'SPV-004',
                'division' => 'Teknologi Informasi',
                'position' => 'Lead Developer',
                'phone' => '081234567892',
            ],
        );

        $intern = User::firstOrCreate(
            ['email' => 'siap-terbit@telkom-skb.com'],
            ['password' => bcrypt('password'), 'role' => 'intern', 'email_verified_at' => now()],
        );
        InternProfile::firstOrCreate(
            ['user_id' => $intern->id],
            [
                'full_name' => 'Aulia Rahman',
                'phone' => '081298765432',
                'address' => 'Jl. Dipatiukur No. 22, Bandung',
                'institution_name' => 'Universitas Padjadjaran',
                'institution_type' => 'university',
                'major' => 'Ilmu Komputer',
                'student_id' => 'STU-PENDING-003',
            ],
        );

        $vacancy = Vacancy::firstOrCreate(
            ['title' => 'Frontend Developer Intern', 'created_by' => $admin->id],
            [
                'division' => 'Teknologi Informasi',
                'description' => 'Magang sebagai Frontend Developer untuk mengembangkan antarmuka pengguna aplikasi internal perusahaan.',
                'qualifications' => 'Mahasiswa aktif jurusan Ilmu Komputer/Sistem Informasi, memahami HTML/CSS/JS & Vue.js/React, bersedia WFO.',
                'quota' => 2,
                'start_date' => now()->subMonths(6),
                'end_date' => now()->addMonths(6),
                'application_deadline' => now()->subMonths(4),
                'status' => 'open',
            ],
        );

        Application::firstOrCreate(
            ['intern_id' => $intern->id, 'vacancy_id' => $vacancy->id],
            [
                'status' => 'accepted',
                'admin_notes' => 'Diterima untuk periode magang Mar-Mei 2026',
                'applied_at' => now()->subMonths(5),
            ],
        );

        $internship = Internship::firstOrCreate(
            ['application_id' => Application::where('intern_id', $intern->id)->where('vacancy_id', $vacancy->id)->value('id')],
            [
                'intern_id' => $intern->id,
                'supervisor_id' => $supervisor->id,
                'vacancy_id' => $vacancy->id,
                'status' => 'completed',
                'actual_start_date' => '2026-03-02',
                'actual_end_date' => '2026-05-29',
            ],
        );

        $activities = [
            ['date' => '2026-03-03', 'activity' => 'Mempelajari desain sistem dan komponen UI yang sudah ada di repository', 'output' => 'Catatan pemahaman struktur komponen'],
            ['date' => '2026-03-17', 'activity' => 'Membuat komponen dashboard interaktif menggunakan Vue.js', 'output' => 'Komponen dashboard dengan grafik real-time'],
            ['date' => '2026-04-07', 'activity' => 'Integrasi API dengan frontend menggunakan Axios', 'output' => 'Seluruh halaman terhubung dengan backend API'],
            ['date' => '2026-04-21', 'activity' => 'Implementasi responsive design untuk tampilan mobile', 'output' => 'Semua halaman responsif di perangkat mobile'],
            ['date' => '2026-05-05', 'activity' => 'Testing UI/UX dan perbaikan bug pada modul pengguna', 'output' => '10 bug terfix, testing cross-browser selesai'],
        ];

        foreach ($activities as $i => $item) {
            Logbook::firstOrCreate(
                ['internship_id' => $internship->id, 'activity_date' => $item['date']],
                [
                    'intern_id' => $intern->id,
                    'activities' => $item['activity'],
                    'output' => $item['output'],
                    'validation_status' => 'approved',
                    'reviewed_at' => now()->subMonths(2)->addDays($i * 10),
                    'supervisor_notes' => 'Kinerja baik, terus tingkatkan kualitas kode.',
                ],
            );
        }

        FinalReport::firstOrCreate(
            ['internship_id' => $internship->id],
            [
                'intern_id' => $intern->id,
                'title' => 'Laporan Akhir Magang: Pengembangan Frontend Dashboard Manajemen di Telkom Sukabumi',
                'file_url' => 'uploads/reports/pending-report.pdf',
                'file_size_kb' => 1800,
                'submitted_at' => '2026-05-25',
                'supervisor_approval' => 'approved',
                'approved_at' => '2026-05-28',
            ],
        );

        $evaluation = Evaluation::firstOrCreate(
            ['internship_id' => $internship->id],
            [
                'supervisor_id' => $supervisor->id,
                'soft_skill_score' => 78,
                'hard_skill_score' => 80,
                'attendance_score' => 92,
                'attitude_score' => 85,
                'remarks' => 'Peserta magang memiliki kemampuan frontend yang baik, kreatif dalam mendesain UI, dan disiplin dalam kehadiran. Sedikit perlu peningkatan dalam komunikasi tertulis.',
                'evaluated_at' => '2026-05-29',
            ],
        );
        $evaluation->calculateFinalScore();
        $evaluation->save();

        $this->command->info('✅ Pending intern siap terbit berhasil dibuat!');
        $this->command->info('   Email: siap-terbit@telkom-skb.com / password');
        $this->command->info('   Nama: Aulia Rahman');
        $this->command->info('   Grade: ' . $evaluation->grade . ' (' . $evaluation->final_score . ')');
        $this->command->info('   Status: Menunggu admin menerbitkan sertifikat');
    }
}
