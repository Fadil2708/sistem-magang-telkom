<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('intern_id');
            $table->foreign('intern_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->uuid('vacancy_id');
            $table->foreign('vacancy_id')
                  ->references('id')->on('vacancies')
                  ->onDelete('cascade');

            $table->enum('status', [
                'submitted',
                'under_review',
                'interview_scheduled',
                'accepted',
                'rejected',
                'cancelled',
            ])->default('submitted');

            $table->timestamp('interview_date')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('admin_notes')->nullable();

            $table->timestamp('applied_at')->useCurrent();
            $table->timestamps();

            $table->unique(['intern_id', 'vacancy_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
