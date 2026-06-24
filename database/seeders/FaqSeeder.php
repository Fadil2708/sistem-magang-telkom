<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Siapa saja yang bisa mendaftar program magang?',
                'answer' => 'Program magang terbuka untuk mahasiswa aktif minimal semester 4 dari berbagai jurusan yang relevan. Siswa SMK kelas 11-12 juga dapat mendaftar untuk program PKL. Pastikan kamu memiliki surat rekomendasi dari institusi pendidikan.',
                'sort_order' => 1,
            ],
            [
                'question' => 'Berapa lama durasi program magang?',
                'answer' => 'Durasi magang bervariasi antara 2 hingga 6 bulan, tergantung pada kebijakan institusi pendidikan dan kebutuhan divisi. Program PKL umumnya berlangsung 3 bulan. Jadwal dapat disesuaikan dengan kalender akademik.',
                'sort_order' => 2,
            ],
            [
                'question' => 'Apakah ada sertifikat setelah menyelesaikan magang?',
                'answer' => 'Ya, setiap peserta yang menyelesaikan program magang akan mendapatkan sertifikat digital resmi dari Telkom Sukabumi yang dilengkapi QR code. Sertifikat dapat diverifikasi secara publik melalui platform ini.',
                'sort_order' => 3,
            ],
            [
                'question' => 'Bagaimana cara memantau status pendaftaran saya?',
                'answer' => 'Setelah mendaftar, kamu dapat login ke dashboard untuk memantau status lamaran secara real-time. Status akan diperbarui oleh tim admin di setiap tahap seleksi. Pastikan email yang didaftarkan aktif untuk menerima notifikasi.',
                'sort_order' => 4,
            ],
            [
                'question' => 'Apakah program magang ini berbayar?',
                'answer' => 'Program magang di Telkom Sukabumi tidak dipungut biaya pendaftaran. Informasi mengenai tunjangan atau insentif akan dijelaskan lebih lanjut pada saat proses seleksi dan tergantung pada kebijakan masing-masing divisi.',
                'sort_order' => 5,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
