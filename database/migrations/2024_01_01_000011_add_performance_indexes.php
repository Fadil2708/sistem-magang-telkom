<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->index('status', 'idx_applications_status');
            $table->index('applied_at', 'idx_applications_applied_at');
        });

        Schema::table('internships', function (Blueprint $table) {
            $table->index('status', 'idx_internships_status');
            $table->index(['supervisor_id', 'status'], 'idx_internships_supervisor_status');
        });

        Schema::table('logbooks', function (Blueprint $table) {
            $table->index(['internship_id', 'activity_date'], 'idx_logbooks_internship_date');
            $table->index('validation_status', 'idx_logbooks_validation_status');
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->index('qr_code_token', 'idx_certificates_qr_token');
            $table->index('certificate_number', 'idx_certificates_number');
        });

        Schema::table('vacancies', function (Blueprint $table) {
            $table->index('status', 'idx_vacancies_status');
            $table->index('application_deadline', 'idx_vacancies_deadline');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex('idx_applications_status');
            $table->dropIndex('idx_applications_applied_at');
        });
        Schema::table('internships', function (Blueprint $table) {
            $table->dropIndex('idx_internships_status');
            $table->dropIndex('idx_internships_supervisor_status');
        });
        Schema::table('logbooks', function (Blueprint $table) {
            $table->dropIndex('idx_logbooks_internship_date');
            $table->dropIndex('idx_logbooks_validation_status');
        });
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropIndex('idx_certificates_qr_token');
            $table->dropIndex('idx_certificates_number');
        });
        Schema::table('vacancies', function (Blueprint $table) {
            $table->dropIndex('idx_vacancies_status');
            $table->dropIndex('idx_vacancies_deadline');
        });
    }
};
