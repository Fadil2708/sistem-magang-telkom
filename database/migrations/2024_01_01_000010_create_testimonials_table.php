<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('intern_id');
            $table->foreign('intern_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->uuid('internship_id');
            $table->foreign('internship_id')
                  ->references('id')->on('internships')
                  ->onDelete('cascade');

            $table->tinyInteger('rating')->unsigned();
            $table->text('content');

            $table->boolean('is_published')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
