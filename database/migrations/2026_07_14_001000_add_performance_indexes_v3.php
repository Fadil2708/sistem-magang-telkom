<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {
            $table->index('intern_id', 'idx_logbooks_intern_id');
            $table->index(['internship_id', 'validation_status'], 'idx_logbooks_internship_validation');
        });

        Schema::table('final_reports', function (Blueprint $table) {
            $table->index('internship_id', 'idx_final_reports_internship_id');
        });

        Schema::table('evaluations', function (Blueprint $table) {
            $table->index('internship_id', 'idx_evaluations_internship_id');
        });
    }

    public function down(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {
            $table->dropIndex('idx_logbooks_intern_id');
            $table->dropIndex('idx_logbooks_internship_validation');
        });

        Schema::table('final_reports', function (Blueprint $table) {
            $table->dropIndex('idx_final_reports_internship_id');
        });

        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropIndex('idx_evaluations_internship_id');
        });
    }
};
