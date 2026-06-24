<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('internship_id')->unique();
            $table->foreign('internship_id')
                  ->references('id')->on('internships')
                  ->onDelete('restrict');

            $table->uuid('intern_id');
            $table->foreign('intern_id')
                  ->references('id')->on('users')
                  ->onDelete('restrict');

            $table->string('certificate_number', 100)->unique();

            $table->uuid('issued_by');
            $table->foreign('issued_by')
                  ->references('id')->on('users')
                  ->onDelete('restrict');

            $table->decimal('final_score', 5, 2);
            $table->enum('grade', ['A', 'B', 'C', 'D']);

            $table->string('qr_code_token', 255)->unique();
            $table->string('qr_code_url', 500)->nullable();
            $table->string('certificate_file_url', 500)->nullable();

            $table->timestamp('issued_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
