<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internships', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('application_id')->unique();
            $table->foreign('application_id')
                  ->references('id')->on('applications')
                  ->onDelete('restrict');

            $table->uuid('intern_id');
            $table->foreign('intern_id')
                  ->references('id')->on('users')
                  ->onDelete('restrict');

            $table->uuid('supervisor_id')->nullable();
            $table->foreign('supervisor_id')
                  ->references('id')->on('users')
                  ->onDelete('restrict');

            $table->uuid('vacancy_id');
            $table->foreign('vacancy_id')
                  ->references('id')->on('vacancies')
                  ->onDelete('restrict');

            $table->date('actual_start_date')->nullable();
            $table->date('actual_end_date')->nullable();

            $table->enum('status', ['active', 'completed', 'terminated'])->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internships');
    }
};
