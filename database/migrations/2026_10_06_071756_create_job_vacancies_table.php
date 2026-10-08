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
            $table->foreignId('alumnus_id')->nullable()->constrained('alumni')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('company');
            $table->string('company_alumni_info')->nullable();
            $table->string('alumni_info')->nullable();
            $table->string('job_type')->default('Full Time'); // Full Time, Magang / Internship, etc.
            $table->string('type_badge_class')->default('bg-emerald-100 text-emerald-800');
            $table->string('location')->default('Balikpapan');
            $table->string('salary_range')->nullable();
            $table->string('posted_time_info')->nullable();
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->date('deadline')->nullable();
            $table->string('cta_label')->default('Lamar via Jalur Rekomendasi IKA');
            $table->string('cta_link')->nullable();
            $table->string('apply_url')->nullable();
            $table->string('status')->default('pending')->index(); // draft, pending, active, expired, closed
            $table->timestamps();
            $table->softDeletes();

            $table->index(['job_type', 'status', 'deadline']);
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
