-- ============================================================
-- DATA DUMMY LENGKAP — Sistem Magang & PKL Telkom Sukabumi
-- Generated: 2026-06-25
-- Password semua user: password
-- ============================================================
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- 1. SITE SETTINGS (3 baris)
-- ============================================================
TRUNCATE TABLE site_settings;
INSERT INTO site_settings (`key`, `value`, created_at, updated_at) VALUES
('announcement_text',     'Pendaftaran Magang Gelombang 2 telah dibuka!', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('announcement_deadline', '15 Juli 2026',                                  '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('announcement_enabled',  '1',                                             '2026-06-25 10:00:00', '2026-06-25 10:00:00');

-- ============================================================
-- 2. FAQS (5 baris)
-- ============================================================
TRUNCATE TABLE faqs;
INSERT INTO faqs (question, answer, sort_order, is_active, created_at, updated_at) VALUES
('Siapa saja yang bisa mendaftar program magang?',
 'Program magang terbuka untuk mahasiswa aktif minimal semester 4 dari berbagai jurusan yang relevan. Siswa SMK kelas 11-12 juga dapat mendaftar untuk program PKL. Pastikan kamu memiliki surat rekomendasi dari institusi pendidikan.',
 1, 1, '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Berapa lama durasi program magang?',
 'Durasi magang bervariasi antara 2 hingga 6 bulan, tergantung pada kebijakan institusi pendidikan dan kebutuhan divisi. Program PKL umumnya berlangsung 3 bulan. Jadwal dapat disesuaikan dengan kalender akademik.',
 2, 1, '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Apakah ada sertifikat setelah menyelesaikan magang?',
 'Ya, setiap peserta yang menyelesaikan program magang akan mendapatkan sertifikat digital resmi dari Telkom Sukabumi yang dilengkapi QR code. Sertifikat dapat diverifikasi secara publik melalui platform ini.',
 3, 1, '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Bagaimana cara memantau status pendaftaran saya?',
 'Setelah mendaftar, kamu dapat login ke dashboard untuk memantau status lamaran secara real-time. Status akan diperbarui oleh tim admin di setiap tahap seleksi. Pastikan email yang didaftarkan aktif untuk menerima notifikasi.',
 4, 1, '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Apakah program magang ini berbayar?',
 'Program magang di Telkom Sukabumi tidak dipungut biaya pendaftaran. Informasi mengenai tunjangan atau insentif akan dijelaskan lebih lanjut pada saat proses seleksi dan tergantung pada kebijakan masing-masing divisi.',
 5, 1, '2026-06-25 10:00:00', '2026-06-25 10:00:00');

-- ============================================================
-- 3. SKILLS (39 baris)
-- ============================================================
TRUNCATE TABLE skills;
INSERT INTO skills (name, category, created_at, updated_at) VALUES
('PHP',                     'Programming',  '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('JavaScript',              'Programming',  '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Python',                  'Programming',  '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Java',                    'Programming',  '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('HTML & CSS',              'Programming',  '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('SQL',                     'Programming',  '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Laravel',                 'Programming',  '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('React',                   'Programming',  '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Node.js',                 'Programming',  '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Flutter',                 'Programming',  '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Dart',                    'Programming',  '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('TypeScript',              'Programming',  '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Tailwind CSS',            'Programming',  '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Git',                     'Programming',  '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Adobe Photoshop',         'Design',       '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Adobe Illustrator',       'Design',       '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Figma',                   'Design',       '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Canva',                   'Design',       '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('UI / UX Design',          'Design',       '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Microsoft Excel',         'Office',       '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Microsoft Word',          'Office',       '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Microsoft PowerPoint',    'Office',       '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Google Docs',             'Office',       '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Google Sheets',           'Office',       '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Cisco',                   'Networking',   '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('MikroTik',                'Networking',   '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Network Configuration',   'Networking',   '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('TCP / IP',                'Networking',   '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Komunikasi',              'Soft Skills',  '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Teamwork',                'Soft Skills',  '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Kepemimpinan',            'Soft Skills',  '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Manajemen Waktu',         'Soft Skills',  '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Problem Solving',         'Soft Skills',  '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Video Editing',           'Multimedia',   '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Photography',             'Multimedia',   '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Content Writing',         'Multimedia',   '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Social Media Management', 'Multimedia',   '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Digital Marketing',       'Lainnya',      '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Data Analysis',           'Lainnya',      '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('Public Speaking',         'Lainnya',      '2026-06-25 10:00:00', '2026-06-25 10:00:00');

-- ============================================================
-- 4. USERS (12 baris)
--    Password semua: password (bcrypt hash)
-- ============================================================
TRUNCATE TABLE users;
INSERT INTO users (id, email, password, role, is_active, email_verified_at, created_at, updated_at) VALUES
('a0010000-0000-4000-8000-000000000001', 'admin@telkom-skb.com',       '$2y$10$HHwOWcyDAWQ3yEPBn2q5RuMm7lXd6GzTQaQq11lqguhxDmsGm3Q12', 'admin',      1, '2026-06-25 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0010000-0000-4000-8000-000000000002', 'supervisor1@telkom-skb.com', '$2y$10$HHwOWcyDAWQ3yEPBn2q5RuMm7lXd6GzTQaQq11lqguhxDmsGm3Q12', 'supervisor', 1, '2026-06-25 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0010000-0000-4000-8000-000000000003', 'supervisor2@telkom-skb.com', '$2y$10$HHwOWcyDAWQ3yEPBn2q5RuMm7lXd6GzTQaQq11lqguhxDmsGm3Q12', 'supervisor', 1, '2026-06-25 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0010000-0000-4000-8000-000000000004', 'supervisor3@telkom-skb.com', '$2y$10$HHwOWcyDAWQ3yEPBn2q5RuMm7lXd6GzTQaQq11lqguhxDmsGm3Q12', 'supervisor', 1, '2026-06-25 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0010000-0000-4000-8000-000000000005', 'supervisor4@telkom-skb.com', '$2y$10$HHwOWcyDAWQ3yEPBn2q5RuMm7lXd6GzTQaQq11lqguhxDmsGm3Q12', 'supervisor', 1, '2026-06-25 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0010000-0000-4000-8000-000000000011', 'intern1@telkom-skb.com',     '$2y$10$HHwOWcyDAWQ3yEPBn2q5RuMm7lXd6GzTQaQq11lqguhxDmsGm3Q12', 'intern',     1, '2026-06-25 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0010000-0000-4000-8000-000000000012', 'intern2@telkom-skb.com',     '$2y$10$HHwOWcyDAWQ3yEPBn2q5RuMm7lXd6GzTQaQq11lqguhxDmsGm3Q12', 'intern',     1, '2026-06-25 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0010000-0000-4000-8000-000000000013', 'intern3@telkom-skb.com',     '$2y$10$HHwOWcyDAWQ3yEPBn2q5RuMm7lXd6GzTQaQq11lqguhxDmsGm3Q12', 'intern',     1, '2026-06-25 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0010000-0000-4000-8000-000000000021', 'test-cert@telkom-skb.com',   '$2y$10$HHwOWcyDAWQ3yEPBn2q5RuMm7lXd6GzTQaQq11lqguhxDmsGm3Q12', 'intern',     1, '2026-06-25 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0010000-0000-4000-8000-000000000022', 'dummy-lulus@telkom-skb.com', '$2y$10$HHwOWcyDAWQ3yEPBn2q5RuMm7lXd6GzTQaQq11lqguhxDmsGm3Q12', 'intern',     1, '2026-06-25 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0010000-0000-4000-8000-000000000023', 'siap-terbit@telkom-skb.com', '$2y$10$HHwOWcyDAWQ3yEPBn2q5RuMm7lXd6GzTQaQq11lqguhxDmsGm3Q12', 'intern',     1, '2026-06-25 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0010000-0000-4000-8000-000000000024', 'nilai-siap@telkom-skb.com',  '$2y$10$HHwOWcyDAWQ3yEPBn2q5RuMm7lXd6GzTQaQq11lqguhxDmsGm3Q12', 'intern',     1, '2026-06-25 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00');

-- ============================================================
-- 5. SUPERVISOR PROFILES (4 baris)
-- ============================================================
TRUNCATE TABLE supervisor_profiles;
INSERT INTO supervisor_profiles (id, user_id, full_name, employee_id, division, position, phone, created_at, updated_at) VALUES
('a0020000-0000-4000-8000-000000000001', 'a0010000-0000-4000-8000-000000000002', 'Budi Santoso',   'SPV-001', 'Teknologi Informasi', 'IT Manager',     '081234567891', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0020000-0000-4000-8000-000000000002', 'a0010000-0000-4000-8000-000000000003', 'Dewi Lestari',   'SPV-002', 'Sumber Daya Manusia', 'HR Coordinator', '081234567892', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0020000-0000-4000-8000-000000000003', 'a0010000-0000-4000-8000-000000000004', 'Siti Rahmawati', 'SPV-003', 'Teknologi Informasi', 'Lead Developer', '081234567892', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0020000-0000-4000-8000-000000000004', 'a0010000-0000-4000-8000-000000000005', 'Siti Rahmawati', 'SPV-004', 'Teknologi Informasi', 'Lead Developer', '081234567892', '2026-06-25 10:00:00', '2026-06-25 10:00:00');

-- ============================================================
-- 6. INTERN PROFILES (7 baris)
-- ============================================================
TRUNCATE TABLE intern_profiles;
INSERT INTO intern_profiles (id, user_id, full_name, phone, address, institution_name, institution_type, major, student_id, created_at, updated_at) VALUES
('a0030000-0000-4000-8000-000000000001', 'a0010000-0000-4000-8000-000000000011', 'Ahmad Rizki',    '081234567893', 'Jl. Merdeka No. 1, Sukabumi',       'Universitas Nusantara',          'university', 'Teknik Informatika',      'STU-2024-001',  '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0030000-0000-4000-8000-000000000002', 'a0010000-0000-4000-8000-000000000012', 'Siti Nurhaliza', '081234567894', 'Jl. Pelajar No. 5, Sukabumi',        'Politeknik Negeri',              'university', 'Manajemen Bisnis',        'STU-2024-002',  '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0030000-0000-4000-8000-000000000003', 'a0010000-0000-4000-8000-000000000013', 'Doni Prasetyo',  '081234567895', 'Jl. Raya Cisaat No. 10, Sukabumi',   'SMK Negeri 1 Sukabumi',          'vocational', 'Rekayasa Perangkat Lunak', 'STU-2024-003',  '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0030000-0000-4000-8000-000000000004', 'a0010000-0000-4000-8000-000000000021', 'Rina Amelia',    '085678901234', 'Jl. Raya Cicurug No. 7, Sukabumi',   'Universitas Telkom',             'university', 'Teknik Informatika',      'STU-TEST-001',  '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0030000-0000-4000-8000-000000000005', 'a0010000-0000-4000-8000-000000000022', 'Dimas Prasetyo', '087654321098', 'Jl. Merdeka No. 15, Bandung',         'Politeknik Negeri Bandung',      'vocational', 'D4 Teknik Informatika',    'STU-DUMMY-002', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0030000-0000-4000-8000-000000000006', 'a0010000-0000-4000-8000-000000000023', 'Aulia Rahman',   '081298765432', 'Jl. Dipatiukur No. 22, Bandung',     'Universitas Padjadjaran',        'university', 'Ilmu Komputer',           'STU-PENDING-003', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0030000-0000-4000-8000-000000000007', 'a0010000-0000-4000-8000-000000000024', 'Bella Safira',   '081377788899', 'Jl. Margonda Raya No. 50, Depok',    'Universitas Indonesia',          'university', 'Sistem Informasi',        'STU-PENDING-004', '2026-06-25 10:00:00', '2026-06-25 10:00:00');

-- ============================================================
-- 7. VACANCIES (6 baris)
-- ============================================================
TRUNCATE TABLE vacancies;
INSERT INTO vacancies (id, created_by, title, division, description, qualifications, quota, start_date, end_date, application_deadline, status, created_at, updated_at) VALUES
('a0040000-0000-4000-8000-000000000001', 'a0010000-0000-4000-8000-000000000001',
 'Software Developer Intern', 'IT Development',
 'Membantu tim pengembangan dalam pembuatan aplikasi internal.',
 'Mahasiswa S1 Teknik Informatika, menguasai PHP/Laravel, Git.',
 3, '2026-07-25', '2026-10-25', '2026-07-16', 'open',   '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0040000-0000-4000-8000-000000000002', 'a0010000-0000-4000-8000-000000000001',
 'Network Engineer Intern', 'IT Infrastructure',
 'Membantu monitoring dan pemeliharaan infrastruktur jaringan.',
 'Mahasiswa D3/S1 Teknik Jaringan, familiar dengan Cisco.',
 2, '2026-07-25', '2026-10-25', '2026-07-16', 'open',   '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0040000-0000-4000-8000-000000000003', 'a0010000-0000-4000-8000-000000000001',
 'UI/UX Designer Intern', 'Digital Experience',
 'Merancang antarmuka pengguna yang intuitif dan menarik.',
 'Mahasiswa D3/S1 Desain/SI, menguasai Figma, memiliki portfolio.',
 2, '2026-07-25', '2026-10-25', '2026-07-16', 'open',   '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0040000-0000-4000-8000-000000000004', 'a0010000-0000-4000-8000-000000000001',
 'Data Analyst Intern', 'Business Intelligence',
 'Membantu pengolahan dan visualisasi data bisnis.',
 'Mahasiswa S1 Statistika/SI, menguasai SQL & Excel.',
 2, '2026-04-25', '2026-08-25', '2026-05-25', 'closed', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0040000-0000-4000-8000-000000000005', 'a0010000-0000-4000-8000-000000000001',
 'Backend Developer Intern', 'Teknologi Informasi',
 'Magang sebagai Backend Developer untuk mengembangkan dan memelihara API layanan internal perusahaan.',
 'Mahasiswa aktif jurusan Teknik Informatika/Sistem Informasi, memahami PHP & MySQL, bersedia WFO.',
 2, '2026-01-25', '2027-01-25', '2026-02-25', 'open',   '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0040000-0000-4000-8000-000000000006', 'a0010000-0000-4000-8000-000000000001',
 'Frontend Developer Intern', 'Teknologi Informasi',
 'Magang sebagai Frontend Developer untuk mengembangkan antarmuka pengguna aplikasi internal perusahaan.',
 'Mahasiswa aktif jurusan Ilmu Komputer/Sistem Informasi, memahami HTML/CSS/JS & Vue.js/React, bersedia WFO.',
 2, '2026-01-25', '2027-01-25', '2026-02-25', 'open',   '2026-06-25 10:00:00', '2026-06-25 10:00:00');

-- ============================================================
-- 8. APPLICATIONS (5 baris)
-- ============================================================
TRUNCATE TABLE applications;
INSERT INTO applications (id, intern_id, vacancy_id, status, admin_notes, applied_at, created_at, updated_at) VALUES
('a0050000-0000-4000-8000-000000000001', 'a0010000-0000-4000-8000-000000000021', 'a0040000-0000-4000-8000-000000000001',
 'accepted', 'Diterima untuk periode magang Jan-Mar 2026', '2026-01-25 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0050000-0000-4000-8000-000000000002', 'a0010000-0000-4000-8000-000000000022', 'a0040000-0000-4000-8000-000000000005',
 'accepted', 'Diterima untuk periode magang Feb-Apr 2026', '2026-01-25 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0050000-0000-4000-8000-000000000003', 'a0010000-0000-4000-8000-000000000023', 'a0040000-0000-4000-8000-000000000006',
 'accepted', 'Diterima untuk periode magang Mar-Mei 2026', '2026-02-25 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0050000-0000-4000-8000-000000000004', 'a0010000-0000-4000-8000-000000000024', 'a0040000-0000-4000-8000-000000000003',
 'accepted', 'Diterima untuk periode magang Apr-Jun 2026', '2026-03-25 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0050000-0000-4000-8000-000000000005', 'a0010000-0000-4000-8000-000000000011', 'a0040000-0000-4000-8000-000000000001',
 'submitted', NULL,                                            '2026-06-25 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00');

-- ============================================================
-- 9. INTERNSHIPS (4 baris)
-- ============================================================
TRUNCATE TABLE internships;
INSERT INTO internships (id, application_id, intern_id, supervisor_id, vacancy_id, actual_start_date, actual_end_date, status, created_at, updated_at) VALUES
('a0060000-0000-4000-8000-000000000001', 'a0050000-0000-4000-8000-000000000001',
 'a0010000-0000-4000-8000-000000000021', 'a0010000-0000-4000-8000-000000000002', 'a0040000-0000-4000-8000-000000000001',
 '2026-01-06', '2026-03-31', 'completed', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0060000-0000-4000-8000-000000000002', 'a0050000-0000-4000-8000-000000000002',
 'a0010000-0000-4000-8000-000000000022', 'a0010000-0000-4000-8000-000000000004', 'a0040000-0000-4000-8000-000000000005',
 '2026-02-03', '2026-04-30', 'completed', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0060000-0000-4000-8000-000000000003', 'a0050000-0000-4000-8000-000000000003',
 'a0010000-0000-4000-8000-000000000023', 'a0010000-0000-4000-8000-000000000005', 'a0040000-0000-4000-8000-000000000006',
 '2026-03-02', '2026-05-29', 'completed', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0060000-0000-4000-8000-000000000004', 'a0050000-0000-4000-8000-000000000004',
 'a0010000-0000-4000-8000-000000000024', 'a0010000-0000-4000-8000-000000000002', 'a0040000-0000-4000-8000-000000000003',
 '2026-04-06', '2026-06-30', 'completed', '2026-06-25 10:00:00', '2026-06-25 10:00:00');

-- ============================================================
-- 10. LOGBOOKS (21 baris)
-- ============================================================
TRUNCATE TABLE logbooks;
INSERT INTO logbooks (id, internship_id, intern_id, activity_date, activities, output, validation_status, supervisor_notes, reviewed_at, created_at, updated_at) VALUES

-- Rina Amelia (test-cert) — 5 entries
('a00a0000-0000-4000-8000-000000000001', 'a0060000-0000-4000-8000-000000000001', 'a0010000-0000-4000-8000-000000000021',
 '2026-01-07', 'Mempelajari struktur kode dan dokumentasi project LMS yang sudah ada',
 'Catatan pemahaman arsitektur sistem', 'approved', 'Pekerjaan baik, lanjutkan.', '2026-02-20 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a00a0000-0000-4000-8000-000000000002', 'a0060000-0000-4000-8000-000000000001', 'a0010000-0000-4000-8000-000000000021',
 '2026-01-14', 'Mengembangkan fitur CRUD untuk modul materi pembelajaran',
 'Fitur CRUD materi pembelajaran selesai', 'approved', 'Pekerjaan baik, lanjutkan.', '2026-03-02 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a00a0000-0000-4000-8000-000000000003', 'a0060000-0000-4000-8000-000000000001', 'a0010000-0000-4000-8000-000000000021',
 '2026-01-21', 'Integrasi upload file pada modul materi pembelajaran',
 'Fitur upload file PDF dan gambar berfungsi', 'approved', 'Pekerjaan baik, lanjutkan.', '2026-03-12 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a00a0000-0000-4000-8000-000000000004', 'a0060000-0000-4000-8000-000000000001', 'a0010000-0000-4000-8000-000000000021',
 '2026-02-04', 'Membuat halaman dashboard admin dengan grafik statistik',
 'Dashboard admin dengan Chart.js', 'approved', 'Pekerjaan baik, lanjutkan.', '2026-03-22 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a00a0000-0000-4000-8000-000000000005', 'a0060000-0000-4000-8000-000000000001', 'a0010000-0000-4000-8000-000000000021',
 '2026-02-18', 'Testing dan perbaikan bug pada modul manajemen pengguna',
 'Testing selesai, 5 bug terfix', 'approved', 'Pekerjaan baik, lanjutkan.', '2026-04-02 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),

-- Dimas Prasetyo (dummy-lulus) — 6 entries
('a00a0000-0000-4000-8000-000000000011', 'a0060000-0000-4000-8000-000000000002', 'a0010000-0000-4000-8000-000000000022',
 '2026-02-04', 'Mempelajari dokumentasi API dan standar coding yang digunakan perusahaan',
 'Catatan pemahaman arsitektur API', 'approved', 'Hasil kerja bagus, sesuai ekspektasi.', '2026-03-04 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a00a0000-0000-4000-8000-000000000012', 'a0060000-0000-4000-8000-000000000002', 'a0010000-0000-4000-8000-000000000022',
 '2026-02-18', 'Membuat endpoint CRUD untuk modul master data pengguna',
 'Endpoint REST API master data selesai', 'approved', 'Hasil kerja bagus, sesuai ekspektasi.', '2026-03-14 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a00a0000-0000-4000-8000-000000000013', 'a0060000-0000-4000-8000-000000000002', 'a0010000-0000-4000-8000-000000000022',
 '2026-03-04', 'Implementasi autentikasi JWT dan middleware authorization',
 'Middleware auth berfungsi dengan role-based access', 'approved', 'Hasil kerja bagus, sesuai ekspektasi.', '2026-03-24 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a00a0000-0000-4000-8000-000000000014', 'a0060000-0000-4000-8000-000000000002', 'a0010000-0000-4000-8000-000000000022',
 '2026-03-18', 'Mengembangkan fitur export laporan dalam format Excel dan PDF',
 'Fitur export laporan menggunakan Laravel Excel dan DomPDF', 'approved', 'Hasil kerja bagus, sesuai ekspektasi.', '2026-04-04 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a00a0000-0000-4000-8000-000000000015', 'a0060000-0000-4000-8000-000000000002', 'a0010000-0000-4000-8000-000000000022',
 '2026-04-01', 'Menulis unit test dan integration test untuk modul yang telah dikembangkan',
 '40+ test case berjalan sukses', 'approved', 'Hasil kerja bagus, sesuai ekspektasi.', '2026-04-14 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a00a0000-0000-4000-8000-000000000016', 'a0060000-0000-4000-8000-000000000002', 'a0010000-0000-4000-8000-000000000022',
 '2026-04-15', 'Optimasi query database dan refactoring kode untuk meningkatkan performa',
 'Rata-rata response time turun 40%', 'approved', 'Hasil kerja bagus, sesuai ekspektasi.', '2026-04-24 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),

-- Aulia Rahman (siap-terbit) — 5 entries
('a00a0000-0000-4000-8000-000000000021', 'a0060000-0000-4000-8000-000000000003', 'a0010000-0000-4000-8000-000000000023',
 '2026-03-03', 'Mempelajari desain sistem dan komponen UI yang sudah ada di repository',
 'Catatan pemahaman struktur komponen', 'approved', 'Kinerja baik, terus tingkatkan kualitas kode.', '2026-03-13 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a00a0000-0000-4000-8000-000000000022', 'a0060000-0000-4000-8000-000000000003', 'a0010000-0000-4000-8000-000000000023',
 '2026-03-17', 'Membuat komponen dashboard interaktif menggunakan Vue.js',
 'Komponen dashboard dengan grafik real-time', 'approved', 'Kinerja baik, terus tingkatkan kualitas kode.', '2026-03-23 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a00a0000-0000-4000-8000-000000000023', 'a0060000-0000-4000-8000-000000000003', 'a0010000-0000-4000-8000-000000000023',
 '2026-04-07', 'Integrasi API dengan frontend menggunakan Axios',
 'Seluruh halaman terhubung dengan backend API', 'approved', 'Kinerja baik, terus tingkatkan kualitas kode.', '2026-04-02 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a00a0000-0000-4000-8000-000000000024', 'a0060000-0000-4000-8000-000000000003', 'a0010000-0000-4000-8000-000000000023',
 '2026-04-21', 'Implementasi responsive design untuk tampilan mobile',
 'Semua halaman responsif di perangkat mobile', 'approved', 'Kinerja baik, terus tingkatkan kualitas kode.', '2026-04-12 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a00a0000-0000-4000-8000-000000000025', 'a0060000-0000-4000-8000-000000000003', 'a0010000-0000-4000-8000-000000000023',
 '2026-05-05', 'Testing UI/UX dan perbaikan bug pada modul pengguna',
 '10 bug terfix, testing cross-browser selesai', 'approved', 'Kinerja baik, terus tingkatkan kualitas kode.', '2026-04-22 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),

-- Bella Safira (nilai-siap) — 5 entries
('a00a0000-0000-4000-8000-000000000031', 'a0060000-0000-4000-8000-000000000004', 'a0010000-0000-4000-8000-000000000024',
 '2026-04-07', 'Mempelajari design system dan komponen UI yang sudah ada',
 'Dokumentasi design system dipahami', 'approved', 'Hasil kerja sangat baik, desainnya rapi.', '2026-05-01 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a00a0000-0000-4000-8000-000000000032', 'a0060000-0000-4000-8000-000000000004', 'a0010000-0000-4000-8000-000000000024',
 '2026-04-21', 'Membuat wireframe dan mockup halaman dashboard admin',
 'Wireframe 8 halaman selesai di Figma', 'approved', 'Hasil kerja sangat baik, desainnya rapi.', '2026-05-09 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a00a0000-0000-4000-8000-000000000033', 'a0060000-0000-4000-8000-000000000004', 'a0010000-0000-4000-8000-000000000024',
 '2026-05-05', 'Melakukan user research dan usability testing',
 'Laporan hasil testing & rekomendasi perbaikan', 'approved', 'Hasil kerja sangat baik, desainnya rapi.', '2026-05-17 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a00a0000-0000-4000-8000-000000000034', 'a0060000-0000-4000-8000-000000000004', 'a0010000-0000-4000-8000-000000000024',
 '2026-05-19', 'Mendesain komponen reusable design system',
 '30+ komponen siap pakai di design system', 'approved', 'Hasil kerja sangat baik, desainnya rapi.', '2026-05-25 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a00a0000-0000-4000-8000-000000000035', 'a0060000-0000-4000-8000-000000000004', 'a0010000-0000-4000-8000-000000000024',
 '2026-06-02', 'Menyusun prototype interaktif untuk presentasi ke stakeholder',
 'Prototype final telah di-approve stakeholder', 'approved', 'Hasil kerja sangat baik, desainnya rapi.', '2026-06-02 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00');

-- ============================================================
-- 11. FINAL REPORTS (4 baris)
-- ============================================================
TRUNCATE TABLE final_reports;
INSERT INTO final_reports (id, internship_id, intern_id, title, file_url, file_size_kb, submitted_at, supervisor_approval, approved_at, created_at, updated_at) VALUES
('a0070000-0000-4000-8000-000000000001', 'a0060000-0000-4000-8000-000000000001',
 'a0010000-0000-4000-8000-000000000021',
 'Laporan Akhir Magang: Pengembangan LMS di Telkom Sukabumi',
 'uploads/reports/sample-report.pdf', 2048, '2026-03-25 10:00:00', 'approved', '2026-03-28 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0070000-0000-4000-8000-000000000002', 'a0060000-0000-4000-8000-000000000002',
 'a0010000-0000-4000-8000-000000000022',
 'Laporan Akhir Magang: Pengembangan Backend API untuk Sistem Informasi Manajemen',
 'uploads/reports/dummy-report.pdf', 1536, '2026-04-25 10:00:00', 'approved', '2026-04-28 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0070000-0000-4000-8000-000000000003', 'a0060000-0000-4000-8000-000000000003',
 'a0010000-0000-4000-8000-000000000023',
 'Laporan Akhir Magang: Pengembangan Frontend Dashboard Manajemen di Telkom Sukabumi',
 'uploads/reports/pending-report.pdf', 1800, '2026-05-25 10:00:00', 'approved', '2026-05-28 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0070000-0000-4000-8000-000000000004', 'a0060000-0000-4000-8000-000000000004',
 'a0010000-0000-4000-8000-000000000024',
 'Laporan Akhir Magang: Perancangan Design System dan UI Dashboard Manajemen',
 'uploads/reports/uiux-report.pdf', 2200, '2026-06-25 10:00:00', 'approved', '2026-06-28 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00');

-- ============================================================
-- 12. EVALUATIONS (4 baris)
-- ============================================================
TRUNCATE TABLE evaluations;
INSERT INTO evaluations (id, internship_id, supervisor_id, soft_skill_score, hard_skill_score, attendance_score, attitude_score, final_score, grade, remarks, evaluated_at, created_at, updated_at) VALUES
('a0080000-0000-4000-8000-000000000001', 'a0060000-0000-4000-8000-000000000001',
 'a0010000-0000-4000-8000-000000000002', 88, 90, 85, 92, 88.90, 'A',
 'Peserta magang menunjukkan performa sangat baik. Kemampuan teknis di atas ekspektasi, sikap profesional, dan kehadiran sempurna.',
 '2026-03-30 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0080000-0000-4000-8000-000000000002', 'a0060000-0000-4000-8000-000000000002',
 'a0010000-0000-4000-8000-000000000004', 82, 85, 90, 88, 85.80, 'A',
 'Peserta magang memiliki kemampuan teknis yang baik, cepat belajar, dan disiplin. Perlu sedikit peningkatan dalam komunikasi tim.',
 '2026-04-30 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0080000-0000-4000-8000-000000000003', 'a0060000-0000-4000-8000-000000000003',
 'a0010000-0000-4000-8000-000000000005', 78, 80, 92, 85, 82.90, 'B',
 'Peserta magang memiliki kemampuan frontend yang baik, kreatif dalam mendesain UI, dan disiplin dalam kehadiran. Sedikit perlu peningkatan dalam komunikasi tertulis.',
 '2026-05-29 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0080000-0000-4000-8000-000000000004', 'a0060000-0000-4000-8000-000000000004',
 'a0010000-0000-4000-8000-000000000002', 90, 88, 95, 92, 90.70, 'A',
 'Peserta magang memiliki kemampuan desain yang sangat baik, kreatif, disiplin, dan mampu berkomunikasi dengan baik dengan tim. Hasil kerjanya sangat memuaskan.',
 '2026-06-30 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00');

-- ============================================================
-- 13. CERTIFICATES (2 baris)
-- ============================================================
TRUNCATE TABLE certificates;
INSERT INTO certificates (id, internship_id, intern_id, certificate_number, issued_by, final_score, grade, qr_code_token, qr_code_url, certificate_file_url, issued_at, created_at, updated_at) VALUES
('a0090000-0000-4000-8000-000000000001', 'a0060000-0000-4000-8000-000000000001',
 'a0010000-0000-4000-8000-000000000021', 'CERT-2026-00001',
 'a0010000-0000-4000-8000-000000000001', 88.90, 'A',
 'QR-CERT-TEST-001',
 'https://telkom-magang.nfy.fyi/verify/QR-CERT-TEST-001',
 'certificates/CERT-2026-00001.pdf',
 '2026-03-31 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00'),
('a0090000-0000-4000-8000-000000000002', 'a0060000-0000-4000-8000-000000000002',
 'a0010000-0000-4000-8000-000000000022', 'CERT-2026-00002',
 'a0010000-0000-4000-8000-000000000001', 85.80, 'A',
 'QR-DUMMY-LULUS-002',
 'https://telkom-magang.nfy.fyi/verify/QR-DUMMY-LULUS-002',
 'certificates/CERT-2026-00002.pdf',
 '2026-05-01 10:00:00', '2026-06-25 10:00:00', '2026-06-25 10:00:00');

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- SELESAI
-- ============================================================
-- Total: 13 tabel, ~115 baris data
-- Password semua user: password
-- ============================================================
