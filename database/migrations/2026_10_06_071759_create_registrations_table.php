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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('level'); // sma, smp, sd, tk
            $table->string('graduation_year');
            $table->string('phone_whatsapp');
            $table->string('email');
            $table->string('profession')->nullable();
            $table->string('institution')->nullable();
            $table->string('domicile')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('pending'); // pending, verified, rejected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
