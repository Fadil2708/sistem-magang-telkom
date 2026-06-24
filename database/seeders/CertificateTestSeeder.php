<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Certificate;
use App\Models\Evaluation;
use App\Models\FinalReport;
use App\Models\InternProfile;
use App\Models\Internship;
use App\Models\Logbook;
use App\Models\SupervisorProfile;
use App\Models\User;
use App\Models\Vacancy;
use App\Services\CertificateService;
use Illuminate\Database\Seeder;

class CertificateTestSeeder extends Seeder
{
    public function run(): void
    {
        // ─── 1. Admin + Supervisor (firstOrCreate agar aman) ─────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@telkom-skb.com'],
            ['password' => bcrypt('password'), 'role' => 'admin'],
        );

        $supervisor = User::firstOrCreate(
            ['email' => 'supervisor1@telkom-skb.com'],
            ['password' => bcrypt('password'), 'role' => 'supervisor'],
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

        // ─── 2. Intern baru untuk tes ────────────────────────────────
        $intern = User::firstOrCreate(
            ['email' => 'test-cert@telkom-skb.com'],
            ['password' => bcrypt('password'), 'role' => 'intern'],
        );
        InternProfile::firstOrCreate(
            ['user_id' => $intern->id],
            [
                'full_name' => 'Rina Amelia',
                'phone' => '085678901234',
                'address' => 'Jl. Raya Cicurug No. 7, Sukabumi',
                'institution_name' => 'Universitas Telkom',
                'institution_type' => 'university',
                'major' => 'Teknik Informatika',
                'student_id' => 'STU-TEST-001',
            ],
        );

        // ─── 3. Vacancy ──────────────────────────────────────────────
        $vacancy = Vacancy::firstOrCreate(
            ['title' => 'Software Developer Intern', 'created_by' => $admin->id],
            [
                'division' => 'Teknologi Informasi',
                'description' => 'Magang sebagai Software Developer untuk mengembangkan aplikasi internal perusahaan.',
                'qualifications' => 'Mahasiswa aktif jurusan Teknik Informatika/Sistem Informasi, memahami PHP/Laravel, bersedia WFO.',
                'quota' => 3,
                'start_date' => now()->subMonths(6),
                'end_date' => now()->addMonths(6),
                'application_deadline' => now()->subMonths(4),
                'status' => 'open',
            ],
        );

        // ─── 4. Application ──────────────────────────────────────────
        Application::firstOrCreate(
            ['intern_id' => $intern->id, 'vacancy_id' => $vacancy->id],
            [
                'status' => 'accepted',
                'admin_notes' => 'Diterima untuk periode magang Jan-Mar 2026',
                'applied_at' => now()->subMonths(5),
            ],
        );

        // ─── 5. Internship ───────────────────────────────────────────
        $internship = Internship::firstOrCreate(
            ['application_id' => Application::where('intern_id', $intern->id)->where('vacancy_id', $vacancy->id)->value('id')],
            [
                'intern_id' => $intern->id,
                'supervisor_id' => $supervisor->id,
                'vacancy_id' => $vacancy->id,
                'status' => 'completed',
                'actual_start_date' => '2026-01-06',
                'actual_end_date' => '2026-03-31',
            ],
        );

        // ─── 6. Logbook (5 entries, approved) ───────────────────────
        $activities = [
            ['date' => '2026-01-07', 'activity' => 'Mempelajari struktur kode dan dokumentasi project LMS yang sudah ada', 'output' => 'Catatan pemahaman arsitektur sistem'],
            ['date' => '2026-01-14', 'activity' => 'Mengembangkan fitur CRUD untuk modul materi pembelajaran', 'output' => 'Fitur CRUD materi pembelajaran selesai'],
            ['date' => '2026-01-21', 'activity' => 'Integrasi upload file pada modul materi pembelajaran', 'output' => 'Fitur upload file PDF dan gambar berfungsi'],
            ['date' => '2026-02-04', 'activity' => 'Membuat halaman dashboard admin dengan grafik statistik', 'output' => 'Dashboard admin dengan Chart.js'],
            ['date' => '2026-02-18', 'activity' => 'Testing dan perbaikan bug pada modul manajemen pengguna', 'output' => 'Testing selesai, 5 bug terfix'],
        ];

        foreach ($activities as $i => $item) {
            Logbook::firstOrCreate(
                ['internship_id' => $internship->id, 'activity_date' => $item['date']],
                [
                    'intern_id' => $intern->id,
                    'activities' => $item['activity'],
                    'output' => $item['output'],
                    'validation_status' => 'approved',
                    'reviewed_at' => now()->subMonths(4)->addDays($i * 10),
                    'supervisor_notes' => 'Pekerjaan baik, lanjutkan.',
                ],
            );
        }

        // ─── 7. Final Report ─────────────────────────────────────────
        FinalReport::firstOrCreate(
            ['internship_id' => $internship->id],
            [
                'intern_id' => $intern->id,
                'title' => 'Laporan Akhir Magang: Pengembangan LMS di Telkom Sukabumi',
                'file_url' => 'uploads/reports/sample-report.pdf',
                'file_size_kb' => 2048,
                'submitted_at' => '2026-03-25',
                'supervisor_approval' => 'approved',
                'approved_at' => '2026-03-28',
            ],
        );

        // ─── 8. Evaluation (Grade A) ─────────────────────────────────
        $evaluation = Evaluation::firstOrCreate(
            ['internship_id' => $internship->id],
            [
                'supervisor_id' => $supervisor->id,
                'soft_skill_score' => 88,
                'hard_skill_score' => 90,
                'attendance_score' => 85,
                'attitude_score' => 92,
                'remarks' => 'Peserta magang menunjukkan performa sangat baik. Kemampuan teknis di atas ekspektasi, sikap profesional, dan kehadiran sempurna.',
                'evaluated_at' => '2026-03-30',
            ],
        );
        $evaluation->calculateFinalScore();
        $evaluation->save();

        // ─── 9. Certificate ─────────────────────────────────────────
        if (!$internship->certificate()->exists()) {
            $internship->load('evaluation');
            app(CertificateService::class)->issue($internship, $admin->id);
        }

        $this->command->info('✅ Data tes sertifikat berhasil dibuat!');
        $this->command->info('   Intern: test-cert@telkom-skb.com / password');
        $this->command->info('   Nama: Rina Amelia');
        $this->command->info('   Grade: ' . $evaluation->grade . ' (' . $evaluation->final_score . ')');
        $this->command->info('   QR Token: ' . $internship->certificate->qr_code_token);
    }
}
