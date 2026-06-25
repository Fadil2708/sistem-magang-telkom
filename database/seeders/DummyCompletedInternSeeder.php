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
use App\Jobs\GenerateCertificatePdfJob;
use App\Services\CertificateService;
use Illuminate\Database\Seeder;

class DummyCompletedInternSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@telkom-skb.com'],
            ['password' => bcrypt('password'), 'role' => 'admin', 'email_verified_at' => now()],
        );

        $supervisor = User::firstOrCreate(
            ['email' => 'supervisor3@telkom-skb.com'],
            ['password' => bcrypt('password'), 'role' => 'supervisor', 'email_verified_at' => now()],
        );
        SupervisorProfile::firstOrCreate(
            ['user_id' => $supervisor->id],
            [
                'full_name' => 'Siti Rahmawati',
                'employee_id' => 'SPV-003',
                'division' => 'Teknologi Informasi',
                'position' => 'Lead Developer',
                'phone' => '081234567892',
            ],
        );

        $intern = User::firstOrCreate(
            ['email' => 'dummy-lulus@telkom-skb.com'],
            ['password' => bcrypt('password'), 'role' => 'intern', 'email_verified_at' => now()],
        );
        InternProfile::firstOrCreate(
            ['user_id' => $intern->id],
            [
                'full_name' => 'Dimas Prasetyo',
                'phone' => '087654321098',
                'address' => 'Jl. Merdeka No. 15, Bandung',
                'institution_name' => 'Politeknik Negeri Bandung',
                'institution_type' => 'vocational',
                'major' => 'D4 Teknik Informatika',
                'student_id' => 'STU-DUMMY-002',
            ],
        );

        $vacancy = Vacancy::firstOrCreate(
            ['title' => 'Backend Developer Intern', 'created_by' => $admin->id],
            [
                'division' => 'Teknologi Informasi',
                'description' => 'Magang sebagai Backend Developer untuk mengembangkan dan memelihara API layanan internal perusahaan.',
                'qualifications' => 'Mahasiswa aktif jurusan Teknik Informatika/Sistem Informasi, memahami PHP & MySQL, bersedia WFO.',
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
                'admin_notes' => 'Diterima untuk periode magang Feb-Apr 2026',
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
                'actual_start_date' => '2026-02-03',
                'actual_end_date' => '2026-04-30',
            ],
        );

        $activities = [
            ['date' => '2026-02-04', 'activity' => 'Mempelajari dokumentasi API dan standar coding yang digunakan perusahaan', 'output' => 'Catatan pemahaman arsitektur API'],
            ['date' => '2026-02-18', 'activity' => 'Membuat endpoint CRUD untuk modul master data pengguna', 'output' => 'Endpoint REST API master data selesai'],
            ['date' => '2026-03-04', 'activity' => 'Implementasi autentikasi JWT dan middleware authorization', 'output' => 'Middleware auth berfungsi dengan role-based access'],
            ['date' => '2026-03-18', 'activity' => 'Mengembangkan fitur export laporan dalam format Excel dan PDF', 'output' => 'Fitur export laporan menggunakan Laravel Excel dan DomPDF'],
            ['date' => '2026-04-01', 'activity' => 'Menulis unit test dan integration test untuk modul yang telah dikembangkan', 'output' => '40+ test case berjalan sukses'],
            ['date' => '2026-04-15', 'activity' => 'Optimasi query database dan refactoring kode untuk meningkatkan performa', 'output' => 'Rata-rata response time turun 40%'],
        ];

        foreach ($activities as $i => $item) {
            Logbook::firstOrCreate(
                ['internship_id' => $internship->id, 'activity_date' => $item['date']],
                [
                    'intern_id' => $intern->id,
                    'activities' => $item['activity'],
                    'output' => $item['output'],
                    'validation_status' => 'approved',
                    'reviewed_at' => now()->subMonths(3)->addDays($i * 10),
                    'supervisor_notes' => 'Hasil kerja bagus, sesuai ekspektasi.',
                ],
            );
        }

        FinalReport::firstOrCreate(
            ['internship_id' => $internship->id],
            [
                'intern_id' => $intern->id,
                'title' => 'Laporan Akhir Magang: Pengembangan Backend API untuk Sistem Informasi Manajemen',
                'file_url' => 'uploads/reports/dummy-report.pdf',
                'file_size_kb' => 1536,
                'submitted_at' => '2026-04-25',
                'supervisor_approval' => 'approved',
                'approved_at' => '2026-04-28',
            ],
        );

        $evaluation = Evaluation::firstOrCreate(
            ['internship_id' => $internship->id],
            [
                'supervisor_id' => $supervisor->id,
                'soft_skill_score' => 82,
                'hard_skill_score' => 85,
                'attendance_score' => 90,
                'attitude_score' => 88,
                'remarks' => 'Peserta magang memiliki kemampuan teknis yang baik, cepat belajar, dan disiplin. Perlu sedikit peningkatan dalam komunikasi tim.',
                'evaluated_at' => '2026-04-30',
            ],
        );
        $evaluation->calculateFinalScore();
        $evaluation->save();

        if (!$internship->certificate()->exists()) {
            $internship->load('evaluation');
            $certificate = app(CertificateService::class)->issue($internship, $admin->id);
            dispatch(new GenerateCertificatePdfJob($certificate));
        }
        $internship->load('certificate');

        $this->command->info('✅ Dummy intern lulus berhasil dibuat!');
        $this->command->info('   Email: dummy-lulus@telkom-skb.com / password');
        $this->command->info('   Nama: Dimas Prasetyo');
        $this->command->info('   Grade: ' . $evaluation->grade . ' (' . $evaluation->final_score . ')');
        $this->command->info('   Logbook: ' . count($activities) . ' entry approved');
        $this->command->info('   QR Token: ' . $internship->certificate->qr_code_token);
    }
}
