<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE applications MODIFY COLUMN status ENUM(
            'submitted',
            'under_review',
            'interview_scheduled',
            'accepted',
            'rejected',
            'cancelled'
        ) NOT NULL DEFAULT 'submitted'");
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE applications MODIFY COLUMN status ENUM(
            'submitted',
            'under_review',
            'interview_scheduled',
            'accepted',
            'rejected'
        ) NOT NULL DEFAULT 'submitted'");
    }
};
