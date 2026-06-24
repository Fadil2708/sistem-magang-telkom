<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SiteSettingSeeder::class,
            FaqSeeder::class,
            UserSeeder::class,
            VacancySeeder::class,
            CertificateTestSeeder::class,
            DummyCompletedInternSeeder::class,
            PendingCertificateInternSeeder::class,
            PendingInternSeeder::class,
        ]);
    }
}
