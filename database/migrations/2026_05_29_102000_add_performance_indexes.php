<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registration_invites', function (Blueprint $table) {
            $table->foreign('created_by')
                  ->references('id')->on('users')
                  ->onDelete('restrict');
            $table->index('created_by');
        });

        Schema::table('final_reports', function (Blueprint $table) {
            $table->index('supervisor_approval', 'idx_final_reports_approval');
        });

        Schema::table('evaluations', function (Blueprint $table) {
            $table->index('grade', 'idx_evaluations_grade');
            $table->index('evaluated_at', 'idx_evaluations_evaluated_at');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->index('is_published', 'idx_testimonials_published');
        });
    }

    public function down(): void
    {
        Schema::table('registration_invites', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropIndex(['created_by']);
        });

        Schema::table('final_reports', function (Blueprint $table) {
            $table->dropIndex('idx_final_reports_approval');
        });

        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropIndex('idx_evaluations_grade');
            $table->dropIndex('idx_evaluations_evaluated_at');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropIndex('idx_testimonials_published');
        });
    }
};
