<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacancies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('created_by');
            $table->foreign('created_by')
                  ->references('id')->on('users')
                  ->onDelete('restrict');

            $table->string('title', 255);
            $table->string('division', 255)->nullable();
            $table->text('description');
            $table->text('qualifications');
            $table->unsignedInteger('quota');

            $table->date('start_date');
            $table->date('end_date');
            $table->date('application_deadline');

            $table->enum('status', ['draft', 'open', 'closed'])->default('draft');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacancies');
    }
};
