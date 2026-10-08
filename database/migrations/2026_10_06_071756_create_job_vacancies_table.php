<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('company');
            $table->string('company_alumni_info');
            $table->string('job_type'); // Full Time, Magang / Internship, etc.
            $table->string('type_badge_class')->default('bg-emerald-100 text-emerald-800');
            $table->string('posted_time_info');
            $table->text('description');
            $table->string('cta_label')->default('Lamar via Jalur Rekomendasi IKA');
            $table->string('cta_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_vacancies');
    }
};
