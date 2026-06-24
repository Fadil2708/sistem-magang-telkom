<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            ['name' => 'PHP',              'category' => 'Programming'],
            ['name' => 'JavaScript',        'category' => 'Programming'],
            ['name' => 'Python',            'category' => 'Programming'],
            ['name' => 'Java',              'category' => 'Programming'],
            ['name' => 'HTML & CSS',        'category' => 'Programming'],
            ['name' => 'SQL',               'category' => 'Programming'],
            ['name' => 'Laravel',           'category' => 'Programming'],
            ['name' => 'React',             'category' => 'Programming'],
            ['name' => 'Node.js',           'category' => 'Programming'],
            ['name' => 'Flutter',           'category' => 'Programming'],
            ['name' => 'Dart',              'category' => 'Programming'],
            ['name' => 'TypeScript',        'category' => 'Programming'],
            ['name' => 'Tailwind CSS',      'category' => 'Programming'],
            ['name' => 'Git',               'category' => 'Programming'],

            ['name' => 'Adobe Photoshop',   'category' => 'Design'],
            ['name' => 'Adobe Illustrator', 'category' => 'Design'],
            ['name' => 'Figma',             'category' => 'Design'],
            ['name' => 'Canva',             'category' => 'Design'],
            ['name' => 'UI / UX Design',    'category' => 'Design'],

            ['name' => 'Microsoft Excel',   'category' => 'Office'],
            ['name' => 'Microsoft Word',    'category' => 'Office'],
            ['name' => 'Microsoft PowerPoint','category' => 'Office'],
            ['name' => 'Google Docs',       'category' => 'Office'],
            ['name' => 'Google Sheets',     'category' => 'Office'],

            ['name' => 'Cisco',             'category' => 'Networking'],
            ['name' => 'MikroTik',          'category' => 'Networking'],
            ['name' => 'Network Configuration','category' => 'Networking'],
            ['name' => 'TCP / IP',          'category' => 'Networking'],

            ['name' => 'Komunikasi',        'category' => 'Soft Skills'],
            ['name' => 'Teamwork',          'category' => 'Soft Skills'],
            ['name' => 'Kepemimpinan',      'category' => 'Soft Skills'],
            ['name' => 'Manajemen Waktu',   'category' => 'Soft Skills'],
            ['name' => 'Problem Solving',   'category' => 'Soft Skills'],

            ['name' => 'Video Editing',     'category' => 'Multimedia'],
            ['name' => 'Photography',       'category' => 'Multimedia'],
            ['name' => 'Content Writing',   'category' => 'Multimedia'],
            ['name' => 'Social Media Management','category' => 'Multimedia'],

            ['name' => 'Digital Marketing', 'category' => 'Lainnya'],
            ['name' => 'Data Analysis',     'category' => 'Lainnya'],
            ['name' => 'Public Speaking',   'category' => 'Lainnya'],
        ];

        foreach ($skills as $skill) {
            Skill::firstOrCreate(
                ['name' => $skill['name']],
                ['category' => $skill['category']]
            );
        }
    }
}
