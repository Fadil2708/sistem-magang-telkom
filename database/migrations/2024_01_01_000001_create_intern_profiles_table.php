<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intern_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->unique();
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->string('full_name', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->date('date_of_birth')->nullable();

            $table->string('institution_name', 255)->nullable();
            $table->enum('institution_type', ['university', 'vocational', 'highschool'])->nullable();
            $table->string('major', 255)->nullable();
            $table->string('student_id', 100)->nullable();

            $table->string('photo_url', 500)->nullable();
            $table->string('cv_url', 500)->nullable();
            $table->string('cover_letter_url', 500)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intern_profiles');
    }
};
