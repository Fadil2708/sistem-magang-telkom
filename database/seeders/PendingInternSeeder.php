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

class PendingInternSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@telkom-skb.com'],
            ['password' => bcrypt('password'), 'role' => 'admin', 'email_verified_at' => now()],
        );

        $supervisor = User::firstOrCreate(
            ['email' => 'supervisor1@telkom-skb.com'],
            ['password' => bcrypt('password'), 'role' => 'supervisor', 'email_verified_at' => now()],
        );
        SupervisorProfile::firstOrCreate(
            ['user_id' => $supervisor->id],
            [
                'full_name' => 'Budi Santoso',
                'employee_id' => 'SPV-001',
                'division' => 'Teknologi Informasi',
                'position' => 'IT Manager',
                'phone' => '081234567891',
            ],
        );

        $intern = User::firstOrCreate(
            ['email' => 'nilai-siap@telkom-skb.com'],
            ['password' => bcrypt('password'), 'role' => 'intern', 'email_verified_at' => now()],
        );
        InternProfile::firstOrCreate(
            ['user_id' => $intern->id],
            [
                'full_name' => 'Bella Safira',
                'phone' => '081377788899',
                'address' => 'Jl. Margonda Raya No. 50, Depok',
                'institution_name' => 'Universitas Indonesia',
                'institution_type' => 'university',
                'major' => 'Sistem Informasi',
                'student_id' => 'STU-PENDING-004',
            ],
        );

        $vacancy = Vacancy::firstOrCreate(
            ['title' => 'UI/UX Designer Intern', 'created_by' => $admin->id],
            [
                'division' => 'Teknologi Informasi',
                'description' => 'Magang sebagai UI/UX Designer untuk merancang antarmuka pengguna yang intuitif dan menarik untuk aplikasi internal perusahaan.',
                'qualifications' => 'Mahasiswa aktif jurusan Sistem Informasi/Desain Komunikasi Visual, memahami Figma & prototyping, bersedia WFO.',
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
                'admin_notes' => 'Diterima untuk periode magang Apr-Jun 2026',
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
                'actual_start_date' => '2026-04-06',
                'actual_end_date' => '2026-06-30',
            ],
        );

        $activities = [
            ['date' => '2026-04-07', 'activity' => 'Mempelajari design system dan komponen UI yang sudah ada', 'output' => 'Dokumentasi design system dipahami'],
            ['date' => '2026-04-21', 'activity' => 'Membuat wireframe dan mockup halaman dashboard admin', 'output' => 'Wireframe 8 halaman selesai di Figma'],
            ['date' => '2026-05-05', 'activity' => 'Melakukan user research dan usability testing', 'output' => 'Laporan hasil testing & rekomendasi perbaikan'],
            ['date' => '2026-05-19', 'activity' => 'Mendesain komponen reusable design system', 'output' => '30+ komponen siap pakai di design system'],
            ['date' => '2026-06-02', 'activity' => 'Menyusun prototype interaktif untuk presentasi ke stakeholder', 'output' => 'Prototype final telah di-approve stakeholder'],
        ];

        foreach ($activities as $i => $item) {
            Logbook::firstOrCreate(
                ['internship_id' => $internship->id, 'activity_date' => $item['date']],
                [
                    'intern_id' => $intern->id,
                    'activities' => $item['activity'],
                    'output' => $item['output'],
                    'validation_status' => 'approved',
                    'reviewed_at' => now()->subMonths(1)->addDays($i * 8),
                    'supervisor_notes' => 'Hasil kerja sangat baik, desainnya rapi.',
                ],
            );
        }

        FinalReport::firstOrCreate(
            ['internship_id' => $internship->id],
            [
                'intern_id' => $intern->id,
                'title' => 'Laporan Akhir Magang: Perancangan Design System dan UI Dashboard Manajemen',
                'file_url' => 'uploads/reports/uiux-report.pdf',
                'file_size_kb' => 2200,
                'submitted_at' => '2026-06-25',
                'supervisor_approval' => 'approved',
                'approved_at' => '2026-06-28',
            ],
        );

        $evaluation = Evaluation::firstOrCreate(
            ['internship_id' => $internship->id],
            [
                'supervisor_id' => $supervisor->id,
                'soft_skill_score' => 90,
                'hard_skill_score' => 88,
                'attendance_score' => 95,
                'attitude_score' => 92,
                'remarks' => 'Peserta magang memiliki kemampuan desain yang sangat baik, kreatif, disiplin, dan mampu berkomunikasi dengan baik dengan tim. Hasil kerjanya sangat memuaskan.',
                'evaluated_at' => '2026-06-30',
            ],
        );
        $evaluation->calculateFinalScore();
        $evaluation->save();

        $this->command->info('✅ Pending intern (Bella Safira) berhasil dibuat!');
        $this->command->info('   Email: nilai-siap@telkom-skb.com / password');
        $this->command->info('   Nama: Bella Safira');
        $this->command->info('   Grade: ' . $evaluation->grade . ' (' . $evaluation->final_score . ')');
        $this->command->info('   Status: Menunggu admin menerbitkan sertifikat');
    }
}
