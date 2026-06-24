<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('final_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('internship_id')->unique();
            $table->foreign('internship_id')
                  ->references('id')->on('internships')
                  ->onDelete('cascade');

            $table->uuid('intern_id');
            $table->foreign('intern_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->string('title', 500);
            $table->string('file_url', 500);
            $table->unsignedInteger('file_size_kb')->nullable();

            $table->timestamp('submitted_at')->nullable();

            $table->enum('supervisor_approval', ['pending', 'approved', 'rejected'])
                  ->default('pending');

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('final_reports');
    }
};
