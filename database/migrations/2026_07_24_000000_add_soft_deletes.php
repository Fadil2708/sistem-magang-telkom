<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', fn(Blueprint $table) => $table->softDeletes());
        Schema::table('vacancies', fn(Blueprint $table) => $table->softDeletes());
        Schema::table('applications', fn(Blueprint $table) => $table->softDeletes());
        Schema::table('internships', fn(Blueprint $table) => $table->softDeletes());
        Schema::table('faqs', fn(Blueprint $table) => $table->softDeletes());
        Schema::table('testimonials', fn(Blueprint $table) => $table->softDeletes());
    }

    public function down(): void
    {
        Schema::table('users', fn(Blueprint $table) => $table->dropSoftDeletes());
        Schema::table('vacancies', fn(Blueprint $table) => $table->dropSoftDeletes());
        Schema::table('applications', fn(Blueprint $table) => $table->dropSoftDeletes());
        Schema::table('internships', fn(Blueprint $table) => $table->dropSoftDeletes());
        Schema::table('faqs', fn(Blueprint $table) => $table->dropSoftDeletes());
        Schema::table('testimonials', fn(Blueprint $table) => $table->dropSoftDeletes());
    }
};