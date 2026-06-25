<?php

require __DIR__ . '/../../vendor/autoload.php';

// Hash digenerate via Node.js bcryptjs, disesuaikan ke format $2y$ untuk Laravel
$hash = '$2y$10$HHwOWcyDAWQ3yEPBn2q5RuMm7lXd6GzTQaQq11lqguhxDmsGm3Q12';
$now  = '2026-06-25 10:00:00';
$date = '2026-06-25';

// ══════════════════════════════════════════════════════════════════
// UUIDs (consistent agar relasi aman)
// ══════════════════════════════════════════════════════════════════
$uuid = [
    // users
    'admin'           => 'a0010000-0000-4000-8000-000000000001',
    'spv1'            => 'a0010000-0000-4000-8000-000000000002',
    'spv2'            => 'a0010000-0000-4000-8000-000000000003',
    'spv3'            => 'a0010000-0000-4000-8000-000000000004',
    'spv4'            => 'a0010000-0000-4000-8000-000000000005',
    'intern1'         => 'a0010000-0000-4000-8000-000000000011',
    'intern2'         => 'a0010000-0000-4000-8000-000000000012',
    'intern3'         => 'a0010000-0000-4000-8000-000000000013',
    'test_cert'       => 'a0010000-0000-4000-8000-000000000021',
    'dummy_lulus'     => 'a0010000-0000-4000-8000-000000000022',
    'siap_terbit'     => 'a0010000-0000-4000-8000-000000000023',
    'nilai_siap'      => 'a0010000-0000-4000-8000-000000000024',

    // supervisor_profiles
    'sp_prof_1'       => 'a0020000-0000-4000-8000-000000000001',
    'sp_prof_2'       => 'a0020000-0000-4000-8000-000000000002',
    'sp_prof_3'       => 'a0020000-0000-4000-8000-000000000003',
    'sp_prof_4'       => 'a0020000-0000-4000-8000-000000000004',

    // intern_profiles
    'ip_1'            => 'a0030000-0000-4000-8000-000000000001',
    'ip_2'            => 'a0030000-0000-4000-8000-000000000002',
    'ip_3'            => 'a0030000-0000-4000-8000-000000000003',
    'ip_4'            => 'a0030000-0000-4000-8000-000000000004',
    'ip_5'            => 'a0030000-0000-4000-8000-000000000005',
    'ip_6'            => 'a0030000-0000-4000-8000-000000000006',
    'ip_7'            => 'a0030000-0000-4000-8000-000000000007',

    // vacancies
    'vac_swd'         => 'a0040000-0000-4000-8000-000000000001',
    'vac_net'         => 'a0040000-0000-4000-8000-000000000002',
    'vac_uiux'        => 'a0040000-0000-4000-8000-000000000003',
    'vac_da'          => 'a0040000-0000-4000-8000-000000000004',
    'vac_be'          => 'a0040000-0000-4000-8000-000000000005',
    'vac_fe'          => 'a0040000-0000-4000-8000-000000000006',

    // applications
    'app_1'           => 'a0050000-0000-4000-8000-000000000001',
    'app_2'           => 'a0050000-0000-4000-8000-000000000002',
    'app_3'           => 'a0050000-0000-4000-8000-000000000003',
    'app_4'           => 'a0050000-0000-4000-8000-000000000004',
    'app_5'           => 'a0050000-0000-4000-8000-000000000005',

    // internships
    'ins_1'           => 'a0060000-0000-4000-8000-000000000001',
    'ins_2'           => 'a0060000-0000-4000-8000-000000000002',
    'ins_3'           => 'a0060000-0000-4000-8000-000000000003',
    'ins_4'           => 'a0060000-0000-4000-8000-000000000004',

    // final_reports
    'fr_1'            => 'a0070000-0000-4000-8000-000000000001',
    'fr_2'            => 'a0070000-0000-4000-8000-000000000002',
    'fr_3'            => 'a0070000-0000-4000-8000-000000000003',
    'fr_4'            => 'a0070000-0000-4000-8000-000000000004',

    // evaluations
    'ev_1'            => 'a0080000-0000-4000-8000-000000000001',
    'ev_2'            => 'a0080000-0000-4000-8000-000000000002',
    'ev_3'            => 'a0080000-0000-4000-8000-000000000003',
    'ev_4'            => 'a0080000-0000-4000-8000-000000000004',

    // certificates
    'cert_1'          => 'a0090000-0000-4000-8000-000000000001',
    'cert_2'          => 'a0090000-0000-4000-8000-000000000002',
];

// ──────────────────────────────────────────────────────────────────
// Helper: escape string
// ──────────────────────────────────────────────────────────────────
function e($v) { return str_replace("'", "''", $v); }

// ──────────────────────────────────────────────────────────────────
// SITE SETTINGS
// ──────────────────────────────────────────────────────────────────
$sql = "-- ============================================================
-- DATA DUMMY LENGKAP — Sistem Magang & PKL Telkom Sukabumi
-- Generated: $date
-- ============================================================
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- 1. SITE SETTINGS
-- ============================================================
TRUNCATE TABLE site_settings;
INSERT INTO site_settings (`key`, `value`, created_at, updated_at) VALUES
('announcement_text',    'Pendaftaran Magang Gelombang 2 telah dibuka!', '$now', '$now'),
('announcement_deadline','15 Juli 2026', '$now', '$now'),
('announcement_enabled', '1', '$now', '$now');

-- ============================================================
-- 2. FAQS
-- ============================================================
TRUNCATE TABLE faqs;
INSERT INTO faqs (question, answer, sort_order, is_active, created_at, updated_at) VALUES
('Siapa saja yang bisa mendaftar program magang?',
 'Program magang terbuka untuk mahasiswa aktif minimal semester 4 dari berbagai jurusan yang relevan. Siswa SMK kelas 11-12 juga dapat mendaftar untuk program PKL. Pastikan kamu memiliki surat rekomendasi dari institusi pendidikan.',
 1, 1, '$now', '$now'),
('Berapa lama durasi program magang?',
 'Durasi magang bervariasi antara 2 hingga 6 bulan, tergantung pada kebijakan institusi pendidikan dan kebutuhan divisi. Program PKL umumnya berlangsung 3 bulan. Jadwal dapat disesuaikan dengan kalender akademik.',
 2, 1, '$now', '$now'),
('Apakah ada sertifikat setelah menyelesaikan magang?',
 'Ya, setiap peserta yang menyelesaikan program magang akan mendapatkan sertifikat digital resmi dari Telkom Sukabumi yang dilengkapi QR code. Sertifikat dapat diverifikasi secara publik melalui platform ini.',
 3, 1, '$now', '$now'),
('Bagaimana cara memantau status pendaftaran saya?',
 'Setelah mendaftar, kamu dapat login ke dashboard untuk memantau status lamaran secara real-time. Status akan diperbarui oleh tim admin di setiap tahap seleksi. Pastikan email yang didaftarkan aktif untuk menerima notifikasi.',
 4, 1, '$now', '$now'),
('Apakah program magang ini berbayar?',
 'Program magang di Telkom Sukabumi tidak dipungut biaya pendaftaran. Informasi mengenai tunjangan atau insentif akan dijelaskan lebih lanjut pada saat proses seleksi dan tergantung pada kebijakan masing-masing divisi.',
 5, 1, '$now', '$now');

-- ============================================================
-- 3. SKILLS
-- ============================================================
TRUNCATE TABLE skills;
INSERT INTO skills (name, category, created_at, updated_at) VALUES
('PHP',                    'Programming',  '$now', '$now'),
('JavaScript',             'Programming',  '$now', '$now'),
('Python',                 'Programming',  '$now', '$now'),
('Java',                   'Programming',  '$now', '$now'),
('HTML & CSS',             'Programming',  '$now', '$now'),
('SQL',                    'Programming',  '$now', '$now'),
('Laravel',                'Programming',  '$now', '$now'),
('React',                  'Programming',  '$now', '$now'),
('Node.js',                'Programming',  '$now', '$now'),
('Flutter',                'Programming',  '$now', '$now'),
('Dart',                   'Programming',  '$now', '$now'),
('TypeScript',             'Programming',  '$now', '$now'),
('Tailwind CSS',           'Programming',  '$now', '$now'),
('Git',                    'Programming',  '$now', '$now'),
('Adobe Photoshop',        'Design',       '$now', '$now'),
('Adobe Illustrator',      'Design',       '$now', '$now'),
('Figma',                  'Design',       '$now', '$now'),
('Canva',                  'Design',       '$now', '$now'),
('UI / UX Design',         'Design',       '$now', '$now'),
('Microsoft Excel',        'Office',       '$now', '$now'),
('Microsoft Word',         'Office',       '$now', '$now'),
('Microsoft PowerPoint',   'Office',       '$now', '$now'),
('Google Docs',            'Office',       '$now', '$now'),
('Google Sheets',          'Office',       '$now', '$now'),
('Cisco',                  'Networking',   '$now', '$now'),
('MikroTik',               'Networking',   '$now', '$now'),
('Network Configuration',  'Networking',   '$now', '$now'),
('TCP / IP',               'Networking',   '$now', '$now'),
('Komunikasi',             'Soft Skills',  '$now', '$now'),
('Teamwork',               'Soft Skills',  '$now', '$now'),
('Kepemimpinan',           'Soft Skills',  '$now', '$now'),
('Manajemen Waktu',        'Soft Skills',  '$now', '$now'),
('Problem Solving',        'Soft Skills',  '$now', '$now'),
('Video Editing',          'Multimedia',   '$now', '$now'),
('Photography',            'Multimedia',   '$now', '$now'),
('Content Writing',        'Multimedia',   '$now', '$now'),
('Social Media Management','Multimedia',   '$now', '$now'),
('Digital Marketing',      'Lainnya',      '$now', '$now'),
('Data Analysis',          'Lainnya',      '$now', '$now'),
('Public Speaking',        'Lainnya',      '$now', '$now');

-- ============================================================
-- 4. USERS
-- ============================================================
TRUNCATE TABLE users;
INSERT INTO users (id, email, password, role, is_active, email_verified_at, remember_token, created_at, updated_at) VALUES
('{$uuid['admin']}',       'admin@telkom-skb.com',       '$hash', 'admin',      1, '$now', NULL, '$now', '$now'),
('{$uuid['spv1']}',        'supervisor1@telkom-skb.com', '$hash', 'supervisor', 1, '$now', NULL, '$now', '$now'),
('{$uuid['spv2']}',        'supervisor2@telkom-skb.com', '$hash', 'supervisor', 1, '$now', NULL, '$now', '$now'),
('{$uuid['spv3']}',        'supervisor3@telkom-skb.com', '$hash', 'supervisor', 1, '$now', NULL, '$now', '$now'),
('{$uuid['spv4']}',        'supervisor4@telkom-skb.com', '$hash', 'supervisor', 1, '$now', NULL, '$now', '$now'),
('{$uuid['intern1']}',     'intern1@telkom-skb.com',     '$hash', 'intern',     1, '$now', NULL, '$now', '$now'),
('{$uuid['intern2']}',     'intern2@telkom-skb.com',     '$hash', 'intern',     1, '$now', NULL, '$now', '$now'),
('{$uuid['intern3']}',     'intern3@telkom-skb.com',     '$hash', 'intern',     1, '$now', NULL, '$now', '$now'),
('{$uuid['test_cert']}',   'test-cert@telkom-skb.com',   '$hash', 'intern',     1, '$now', NULL, '$now', '$now'),
('{$uuid['dummy_lulus']}', 'dummy-lulus@telkom-skb.com', '$hash', 'intern',     1, '$now', NULL, '$now', '$now'),
('{$uuid['siap_terbit']}', 'siap-terbit@telkom-skb.com', '$hash', 'intern',     1, '$now', NULL, '$now', '$now'),
('{$uuid['nilai_siap']}',  'nilai-siap@telkom-skb.com',  '$hash', 'intern',     1, '$now', NULL, '$now', '$now');

-- ============================================================
-- 5. SUPERVISOR PROFILES
-- ============================================================
TRUNCATE TABLE supervisor_profiles;
INSERT INTO supervisor_profiles (id, user_id, full_name, employee_id, division, position, phone, created_at, updated_at) VALUES
('{$uuid['sp_prof_1']}', '{$uuid['spv1']}', 'Budi Santoso',    'SPV-001', 'Teknologi Informasi', 'IT Manager',     '081234567891', '$now', '$now'),
('{$uuid['sp_prof_2']}', '{$uuid['spv2']}', 'Dewi Lestari',    'SPV-002', 'Sumber Daya Manusia', 'HR Coordinator', '081234567892', '$now', '$now'),
('{$uuid['sp_prof_3']}', '{$uuid['spv3']}', 'Siti Rahmawati',  'SPV-003', 'Teknologi Informasi', 'Lead Developer', '081234567892', '$now', '$now'),
('{$uuid['sp_prof_4']}', '{$uuid['spv4']}', 'Siti Rahmawati',  'SPV-004', 'Teknologi Informasi', 'Lead Developer', '081234567892', '$now', '$now');

-- ============================================================
-- 6. INTERN PROFILES
-- ============================================================
TRUNCATE TABLE intern_profiles;
INSERT INTO intern_profiles (id, user_id, full_name, phone, address, institution_name, institution_type, major, student_id, created_at, updated_at) VALUES
('{$uuid['ip_1']}', '{$uuid['intern1']}',     'Ahmad Rizki',    '081234567893', 'Jl. Merdeka No. 1, Sukabumi',          'Universitas Nusantara',          'university', 'Teknik Informatika',     'STU-2024-001', '$now', '$now'),
('{$uuid['ip_2']}', '{$uuid['intern2']}',     'Siti Nurhaliza', '081234567894', 'Jl. Pelajar No. 5, Sukabumi',           'Politeknik Negeri',              'university', 'Manajemen Bisnis',       'STU-2024-002', '$now', '$now'),
('{$uuid['ip_3']}', '{$uuid['intern3']}',     'Doni Prasetyo',  '081234567895', 'Jl. Raya Cisaat No. 10, Sukabumi',      'SMK Negeri 1 Sukabumi',          'vocational', 'Rekayasa Perangkat Lunak','STU-2024-003', '$now', '$now'),
('{$uuid['ip_4']}', '{$uuid['test_cert']}',   'Rina Amelia',    '085678901234', 'Jl. Raya Cicurug No. 7, Sukabumi',      'Universitas Telkom',             'university', 'Teknik Informatika',     'STU-TEST-001', '$now', '$now'),
('{$uuid['ip_5']}', '{$uuid['dummy_lulus']}', 'Dimas Prasetyo', '087654321098', 'Jl. Merdeka No. 15, Bandung',            'Politeknik Negeri Bandung',      'vocational', 'D4 Teknik Informatika',   'STU-DUMMY-002', '$now', '$now'),
('{$uuid['ip_6']}', '{$uuid['siap_terbit']}', 'Aulia Rahman',   '081298765432', 'Jl. Dipatiukur No. 22, Bandung',        'Universitas Padjadjaran',        'university', 'Ilmu Komputer',          'STU-PENDING-003', '$now', '$now'),
('{$uuid['ip_7']}', '{$uuid['nilai_siap']}',  'Bella Safira',   '081377788899', 'Jl. Margonda Raya No. 50, Depok',       'Universitas Indonesia',          'university', 'Sistem Informasi',       'STU-PENDING-004', '$now', '$now');

-- ============================================================
-- 7. VACANCIES
-- ============================================================
TRUNCATE TABLE vacancies;
INSERT INTO vacancies (id, created_by, title, division, description, qualifications, quota, start_date, end_date, application_deadline, status, created_at, updated_at) VALUES
('{$uuid['vac_swd']}',  '{$uuid['admin']}', 'Software Developer Intern',  'IT Development',       'Membantu tim pengembangan dalam pembuatan aplikasi internal.',                                                                          'Mahasiswa S1 Teknik Informatika, menguasai PHP/Laravel, Git.',                                                                                       3,  '2026-07-25', '2026-10-25', '2026-07-16', 'open',   '$now', '$now'),
('{$uuid['vac_net']}',  '{$uuid['admin']}', 'Network Engineer Intern',    'IT Infrastructure',    'Membantu monitoring dan pemeliharaan infrastruktur jaringan.',                                                                           'Mahasiswa D3/S1 Teknik Jaringan, familiar dengan Cisco.',                                                                                            2,  '2026-07-25', '2026-10-25', '2026-07-16', 'open',   '$now', '$now'),
('{$uuid['vac_uiux']}', '{$uuid['admin']}', 'UI/UX Designer Intern',      'Digital Experience',   'Merancang antarmuka pengguna yang intuitif dan menarik.',                                                                                  'Mahasiswa D3/S1 Desain/SI, menguasai Figma, memiliki portfolio.',                                                                                   2,  '2026-07-25', '2026-10-25', '2026-07-16', 'open',   '$now', '$now'),
('{$uuid['vac_da']}',   '{$uuid['admin']}', 'Data Analyst Intern',        'Business Intelligence','Membantu pengolahan dan visualisasi data bisnis.',                                                                                          'Mahasiswa S1 Statistika/SI, menguasai SQL & Excel.',                                                                                                2,  '2026-04-25', '2026-08-25', '2026-05-25', 'closed', '$now', '$now'),
('{$uuid['vac_be']}',   '{$uuid['admin']}', 'Backend Developer Intern',   'Teknologi Informasi',  'Magang sebagai Backend Developer untuk mengembangkan dan memelihara API layanan internal perusahaan.',                                   'Mahasiswa aktif jurusan Teknik Informatika/Sistem Informasi, memahami PHP & MySQL, bersedia WFO.',                                                  2,  '2026-01-25', '2027-01-25', '2026-02-25', 'open',   '$now', '$now'),
('{$uuid['vac_fe']}',   '{$uuid['admin']}', 'Frontend Developer Intern',  'Teknologi Informasi',  'Magang sebagai Frontend Developer untuk mengembangkan antarmuka pengguna aplikasi internal perusahaan.',                                 'Mahasiswa aktif jurusan Ilmu Komputer/Sistem Informasi, memahami HTML/CSS/JS & Vue.js/React, bersedia WFO.',                                        2,  '2026-01-25', '2027-01-25', '2026-02-25', 'open',   '$now', '$now');

-- ============================================================
-- 8. APPLICATIONS
-- ============================================================
TRUNCATE TABLE applications;
INSERT INTO applications (id, intern_id, vacancy_id, status, admin_notes, applied_at, created_at, updated_at) VALUES
('{$uuid['app_1']}', '{$uuid['test_cert']}',   '{$uuid['vac_swd']}', 'accepted', 'Diterima untuk periode magang Jan-Mar 2026', '2026-01-25 10:00:00', '$now', '$now'),
('{$uuid['app_2']}', '{$uuid['dummy_lulus']}', '{$uuid['vac_be']}',  'accepted', 'Diterima untuk periode magang Feb-Apr 2026', '2026-01-25 10:00:00', '$now', '$now'),
('{$uuid['app_3']}', '{$uuid['siap_terbit']}', '{$uuid['vac_fe']}',  'accepted', 'Diterima untuk periode magang Mar-Mei 2026', '2026-02-25 10:00:00', '$now', '$now'),
('{$uuid['app_4']}', '{$uuid['nilai_siap']}',  '{$uuid['vac_uiux']}','accepted', 'Diterima untuk periode magang Apr-Jun 2026', '2026-03-25 10:00:00', '$now', '$now'),
('{$uuid['app_5']}', '{$uuid['intern1']}',     '{$uuid['vac_swd']}', 'submitted', NULL,                                           '2026-06-25 10:00:00', '$now', '$now');

-- ============================================================
-- 9. INTERNSHIPS
-- ============================================================
TRUNCATE TABLE internships;
INSERT INTO internships (id, application_id, intern_id, supervisor_id, vacancy_id, actual_start_date, actual_end_date, status, created_at, updated_at) VALUES
('{$uuid['ins_1']}', '{$uuid['app_1']}', '{$uuid['test_cert']}',   '{$uuid['spv1']}', '{$uuid['vac_swd']}', '2026-01-06', '2026-03-31', 'completed', '$now', '$now'),
('{$uuid['ins_2']}', '{$uuid['app_2']}', '{$uuid['dummy_lulus']}', '{$uuid['spv3']}', '{$uuid['vac_be']}',  '2026-02-03', '2026-04-30', 'completed', '$now', '$now'),
('{$uuid['ins_3']}', '{$uuid['app_3']}', '{$uuid['siap_terbit']}', '{$uuid['spv4']}', '{$uuid['vac_fe']}',  '2026-03-02', '2026-05-29', 'completed', '$now', '$now'),
('{$uuid['ins_4']}', '{$uuid['app_4']}', '{$uuid['nilai_siap']}',  '{$uuid['spv1']}', '{$uuid['vac_uiux']}','2026-04-06', '2026-06-30', 'completed', '$now', '$now');

-- ============================================================
-- 10. LOGBOOKS
-- ============================================================
TRUNCATE TABLE logbooks;
INSERT INTO logbooks (id, internship_id, intern_id, activity_date, activities, output, validation_status, supervisor_notes, reviewed_at, created_at, updated_at) VALUES

-- Rina Amelia (test-cert) — 5 entries
('a00a0000-0000-4000-8000-000000000001', '{$uuid['ins_1']}', '{$uuid['test_cert']}',   '2026-01-07', 'Mempelajari struktur kode dan dokumentasi project LMS yang sudah ada',                  'Catatan pemahaman arsitektur sistem',                                    'approved', 'Pekerjaan baik, lanjutkan.', '2026-02-20 10:00:00', '$now', '$now'),
('a00a0000-0000-4000-8000-000000000002', '{$uuid['ins_1']}', '{$uuid['test_cert']}',   '2026-01-14', 'Mengembangkan fitur CRUD untuk modul materi pembelajaran',                             'Fitur CRUD materi pembelajaran selesai',                                 'approved', 'Pekerjaan baik, lanjutkan.', '2026-03-02 10:00:00', '$now', '$now'),
('a00a0000-0000-4000-8000-000000000003', '{$uuid['ins_1']}', '{$uuid['test_cert']}',   '2026-01-21', 'Integrasi upload file pada modul materi pembelajaran',                                'Fitur upload file PDF dan gambar berfungsi',                             'approved', 'Pekerjaan baik, lanjutkan.', '2026-03-12 10:00:00', '$now', '$now'),
('a00a0000-0000-4000-8000-000000000004', '{$uuid['ins_1']}', '{$uuid['test_cert']}',   '2026-02-04', 'Membuat halaman dashboard admin dengan grafik statistik',                              'Dashboard admin dengan Chart.js',                                        'approved', 'Pekerjaan baik, lanjutkan.', '2026-03-22 10:00:00', '$now', '$now'),
('a00a0000-0000-4000-8000-000000000005', '{$uuid['ins_1']}', '{$uuid['test_cert']}',   '2026-02-18', 'Testing dan perbaikan bug pada modul manajemen pengguna',                              'Testing selesai, 5 bug terfix',                                          'approved', 'Pekerjaan baik, lanjutkan.', '2026-04-02 10:00:00', '$now', '$now'),

-- Dimas Prasetyo (dummy-lulus) — 6 entries
('a00a0000-0000-4000-8000-000000000011', '{$uuid['ins_2']}', '{$uuid['dummy_lulus']}', '2026-02-04', 'Mempelajari dokumentasi API dan standar coding yang digunakan perusahaan',              'Catatan pemahaman arsitektur API',                                       'approved', 'Hasil kerja bagus, sesuai ekspektasi.', '2026-03-04 10:00:00', '$now', '$now'),
('a00a0000-0000-4000-8000-000000000012', '{$uuid['ins_2']}', '{$uuid['dummy_lulus']}', '2026-02-18', 'Membuat endpoint CRUD untuk modul master data pengguna',                               'Endpoint REST API master data selesai',                                  'approved', 'Hasil kerja bagus, sesuai ekspektasi.', '2026-03-14 10:00:00', '$now', '$now'),
('a00a0000-0000-4000-8000-000000000013', '{$uuid['ins_2']}', '{$uuid['dummy_lulus']}', '2026-03-04', 'Implementasi autentikasi JWT dan middleware authorization',                            'Middleware auth berfungsi dengan role-based access',                     'approved', 'Hasil kerja bagus, sesuai ekspektasi.', '2026-03-24 10:00:00', '$now', '$now'),
('a00a0000-0000-4000-8000-000000000014', '{$uuid['ins_2']}', '{$uuid['dummy_lulus']}', '2026-03-18', 'Mengembangkan fitur export laporan dalam format Excel dan PDF',                        'Fitur export laporan menggunakan Laravel Excel dan DomPDF',             'approved', 'Hasil kerja bagus, sesuai ekspektasi.', '2026-04-04 10:00:00', '$now', '$now'),
('a00a0000-0000-4000-8000-000000000015', '{$uuid['ins_2']}', '{$uuid['dummy_lulus']}', '2026-04-01', 'Menulis unit test dan integration test untuk modul yang telah dikembangkan',            '40+ test case berjalan sukses',                                          'approved', 'Hasil kerja bagus, sesuai ekspektasi.', '2026-04-14 10:00:00', '$now', '$now'),
('a00a0000-0000-4000-8000-000000000016', '{$uuid['ins_2']}', '{$uuid['dummy_lulus']}', '2026-04-15', 'Optimasi query database dan refactoring kode untuk meningkatkan performa',              'Rata-rata response time turun 40%',                                      'approved', 'Hasil kerja bagus, sesuai ekspektasi.', '2026-04-24 10:00:00', '$now', '$now'),

-- Aulia Rahman (siap-terbit) — 5 entries
('a00a0000-0000-4000-8000-000000000021', '{$uuid['ins_3']}', '{$uuid['siap_terbit']}', '2026-03-03', 'Mempelajari desain sistem dan komponen UI yang sudah ada di repository',               'Catatan pemahaman struktur komponen',                                    'approved', 'Kinerja baik, terus tingkatkan kualitas kode.', '2026-03-13 10:00:00', '$now', '$now'),
('a00a0000-0000-4000-8000-000000000022', '{$uuid['ins_3']}', '{$uuid['siap_terbit']}', '2026-03-17', 'Membuat komponen dashboard interaktif menggunakan Vue.js',                              'Komponen dashboard dengan grafik real-time',                            'approved', 'Kinerja baik, terus tingkatkan kualitas kode.', '2026-03-23 10:00:00', '$now', '$now'),
('a00a0000-0000-4000-8000-000000000023', '{$uuid['ins_3']}', '{$uuid['siap_terbit']}', '2026-04-07', 'Integrasi API dengan frontend menggunakan Axios',                                      'Seluruh halaman terhubung dengan backend API',                          'approved', 'Kinerja baik, terus tingkatkan kualitas kode.', '2026-04-02 10:00:00', '$now', '$now'),
('a00a0000-0000-4000-8000-000000000024', '{$uuid['ins_3']}', '{$uuid['siap_terbit']}', '2026-04-21', 'Implementasi responsive design untuk tampilan mobile',                                  'Semua halaman responsif di perangkat mobile',                            'approved', 'Kinerja baik, terus tingkatkan kualitas kode.', '2026-04-12 10:00:00', '$now', '$now'),
('a00a0000-0000-4000-8000-000000000025', '{$uuid['ins_3']}', '{$uuid['siap_terbit']}', '2026-05-05', 'Testing UI/UX dan perbaikan bug pada modul pengguna',                                   '10 bug terfix, testing cross-browser selesai',                          'approved', 'Kinerja baik, terus tingkatkan kualitas kode.', '2026-04-22 10:00:00', '$now', '$now'),

-- Bella Safira (nilai-siap) — 5 entries
('a00a0000-0000-4000-8000-000000000031', '{$uuid['ins_4']}', '{$uuid['nilai_siap']}',  '2026-04-07', 'Mempelajari design system dan komponen UI yang sudah ada',                              'Dokumentasi design system dipahami',                                    'approved', 'Hasil kerja sangat baik, desainnya rapi.', '2026-05-01 10:00:00', '$now', '$now'),
('a00a0000-0000-4000-8000-000000000032', '{$uuid['ins_4']}', '{$uuid['nilai_siap']}',  '2026-04-21', 'Membuat wireframe dan mockup halaman dashboard admin',                                 'Wireframe 8 halaman selesai di Figma',                                  'approved', 'Hasil kerja sangat baik, desainnya rapi.', '2026-05-09 10:00:00', '$now', '$now'),
('a00a0000-0000-4000-8000-000000000033', '{$uuid['ins_4']}', '{$uuid['nilai_siap']}',  '2026-05-05', 'Melakukan user research dan usability testing',                                        'Laporan hasil testing & rekomendasi perbaikan',                        'approved', 'Hasil kerja sangat baik, desainnya rapi.', '2026-05-17 10:00:00', '$now', '$now'),
('a00a0000-0000-4000-8000-000000000034', '{$uuid['ins_4']}', '{$uuid['nilai_siap']}',  '2026-05-19', 'Mendesain komponen reusable design system',                                            '30+ komponen siap pakai di design system',                              'approved', 'Hasil kerja sangat baik, desainnya rapi.', '2026-05-25 10:00:00', '$now', '$now'),
('a00a0000-0000-4000-8000-000000000035', '{$uuid['ins_4']}', '{$uuid['nilai_siap']}',  '2026-06-02', 'Menyusun prototype interaktif untuk presentasi ke stakeholder',                         'Prototype final telah di-approve stakeholder',                         'approved', 'Hasil kerja sangat baik, desainnya rapi.', '2026-06-02 10:00:00', '$now', '$now');

-- ============================================================
-- 11. FINAL REPORTS
-- ============================================================
TRUNCATE TABLE final_reports;
INSERT INTO final_reports (id, internship_id, intern_id, title, file_url, file_size_kb, submitted_at, supervisor_approval, approved_at, created_at, updated_at) VALUES
('{$uuid['fr_1']}', '{$uuid['ins_1']}', '{$uuid['test_cert']}',   'Laporan Akhir Magang: Pengembangan LMS di Telkom Sukabumi',                                   'uploads/reports/sample-report.pdf',  2048, '2026-03-25 10:00:00', 'approved', '2026-03-28 10:00:00', '$now', '$now'),
('{$uuid['fr_2']}', '{$uuid['ins_2']}', '{$uuid['dummy_lulus']}', 'Laporan Akhir Magang: Pengembangan Backend API untuk Sistem Informasi Manajemen',              'uploads/reports/dummy-report.pdf',   1536, '2026-04-25 10:00:00', 'approved', '2026-04-28 10:00:00', '$now', '$now'),
('{$uuid['fr_3']}', '{$uuid['ins_3']}', '{$uuid['siap_terbit']}', 'Laporan Akhir Magang: Pengembangan Frontend Dashboard Manajemen di Telkom Sukabumi',           'uploads/reports/pending-report.pdf', 1800, '2026-05-25 10:00:00', 'approved', '2026-05-28 10:00:00', '$now', '$now'),
('{$uuid['fr_4']}', '{$uuid['ins_4']}', '{$uuid['nilai_siap']}',  'Laporan Akhir Magang: Perancangan Design System dan UI Dashboard Manajemen',                   'uploads/reports/uiux-report.pdf',    2200, '2026-06-25 10:00:00', 'approved', '2026-06-28 10:00:00', '$now', '$now');

-- ============================================================
-- 12. EVALUATIONS
-- ============================================================
TRUNCATE TABLE evaluations;
INSERT INTO evaluations (id, internship_id, supervisor_id, soft_skill_score, hard_skill_score, attendance_score, attitude_score, final_score, grade, remarks, evaluated_at, created_at, updated_at) VALUES
('{$uuid['ev_1']}', '{$uuid['ins_1']}', '{$uuid['spv1']}', 88, 90, 85, 92, 88.90, 'A', 'Peserta magang menunjukkan performa sangat baik. Kemampuan teknis di atas ekspektasi, sikap profesional, dan kehadiran sempurna.',              '2026-03-30 10:00:00', '$now', '$now'),
('{$uuid['ev_2']}', '{$uuid['ins_2']}', '{$uuid['spv3']}', 82, 85, 90, 88, 85.80, 'A', 'Peserta magang memiliki kemampuan teknis yang baik, cepat belajar, dan disiplin. Perlu sedikit peningkatan dalam komunikasi tim.',           '2026-04-30 10:00:00', '$now', '$now'),
('{$uuid['ev_3']}', '{$uuid['ins_3']}', '{$uuid['spv4']}', 78, 80, 92, 85, 82.90, 'B', 'Peserta magang memiliki kemampuan frontend yang baik, kreatif dalam mendesain UI, dan disiplin dalam kehadiran. Sedikit perlu peningkatan dalam komunikasi tertulis.', '2026-05-29 10:00:00', '$now', '$now'),
('{$uuid['ev_4']}', '{$uuid['ins_4']}', '{$uuid['spv1']}', 90, 88, 95, 92, 90.70, 'A', 'Peserta magang memiliki kemampuan desain yang sangat baik, kreatif, disiplin, dan mampu berkomunikasi dengan baik dengan tim. Hasil kerjanya sangat memuaskan.', '2026-06-30 10:00:00', '$now', '$now');

-- ============================================================
-- 13. CERTIFICATES
-- ============================================================
TRUNCATE TABLE certificates;
INSERT INTO certificates (id, internship_id, intern_id, certificate_number, issued_by, final_score, grade, qr_code_token, qr_code_url, certificate_file_url, issued_at, created_at, updated_at) VALUES
('{$uuid['cert_1']}', '{$uuid['ins_1']}', '{$uuid['test_cert']}',   'CERT-2026-00001', '{$uuid['admin']}', 88.90, 'A', 'QR-CERT-TEST-001',    'https://telkom-magang.nfy.fyi/verify/QR-CERT-TEST-001',    'certificates/CERT-2026-00001.pdf', '2026-03-31 10:00:00', '$now', '$now'),
('{$uuid['cert_2']}', '{$uuid['ins_2']}', '{$uuid['dummy_lulus']}', 'CERT-2026-00002', '{$uuid['admin']}', 85.80, 'A', 'QR-DUMMY-LULUS-002', 'https://telkom-magang.nfy.fyi/verify/QR-DUMMY-LULUS-002', 'certificates/CERT-2026-00002.pdf', '2026-05-01 10:00:00', '$now', '$now');

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- SELESAI
-- ============================================================
";

// ══════════════════════════════════════════════════════════════════
// OUTPUT
// ══════════════════════════════════════════════════════════════════
file_put_contents(__DIR__ . '/dummy-data.sql', $sql);
echo "✅ File dummy-data.sql berhasil dibuat di: " . __DIR__ . DIRECTORY_SEPARATOR . "dummy-data.sql\n";
echo "   Password untuk semua user: password\n";
