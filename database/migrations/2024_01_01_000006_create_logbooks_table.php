<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logbooks', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('internship_id');
            $table->foreign('internship_id')
                  ->references('id')->on('internships')
                  ->onDelete('cascade');

            $table->uuid('intern_id');
            $table->foreign('intern_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->date('activity_date');
            $table->text('activities');
            $table->text('output');

            $table->enum('validation_status', [
                'draft',
                'submitted',
                'approved',
                'revision_requested',
            ])->default('draft');

            $table->text('supervisor_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logbooks');
    }
};
