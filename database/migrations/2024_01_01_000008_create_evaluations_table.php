<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('internship_id')->unique();
            $table->foreign('internship_id')
                  ->references('id')->on('internships')
                  ->onDelete('cascade');

            $table->uuid('supervisor_id');
            $table->foreign('supervisor_id')
                  ->references('id')->on('users')
                  ->onDelete('restrict');

            $table->decimal('soft_skill_score', 5, 2)->default(0);
            $table->decimal('hard_skill_score', 5, 2)->default(0);
            $table->decimal('attendance_score', 5, 2)->default(0);
            $table->decimal('attitude_score', 5, 2)->default(0);

            $table->decimal('final_score', 5, 2)->default(0);

            $table->enum('grade', ['A', 'B', 'C', 'D'])->nullable();
            $table->text('remarks')->nullable();

            $table->timestamp('evaluated_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
