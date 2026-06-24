<?php

namespace App\Console\Commands;

use App\Models\Vacancy;
use Illuminate\Console\Command;

class CloseExpiredVacanciesCommand extends Command
{
    protected $signature = 'vacancies:close-expired';
    protected $description = 'Menutup lowongan yang sudah melewati end_date';

    public function handle(): int
    {
        $count = Vacancy::autoCloseExpired();

        $this->info("{$count} lowongan berhasil ditutup.");

        return Command::SUCCESS;
    }
}
