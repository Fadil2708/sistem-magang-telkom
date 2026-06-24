<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::upsert([
            ['key' => 'announcement_text', 'value' => 'Pendaftaran Magang Gelombang 2 telah dibuka!'],
            ['key' => 'announcement_deadline', 'value' => '15 Juli 2026'],
            ['key' => 'announcement_enabled', 'value' => '1'],
        ], 'key');
    }
}
