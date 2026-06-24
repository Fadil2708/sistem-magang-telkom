<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intern_profile_skill', function (Blueprint $table) {
            $table->uuid('intern_profile_id');
            $table->foreign('intern_profile_id')->references('id')->on('intern_profiles')->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->primary(['intern_profile_id', 'skill_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intern_profile_skill');
    }
};
