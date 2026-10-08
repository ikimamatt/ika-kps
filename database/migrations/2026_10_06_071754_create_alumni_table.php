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
        Schema::create('alumni', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('level')->default('sma'); // sma, smp, sd, tk
            $table->string('class_year'); // e.g. '04, '11, '17
            $table->string('full_year')->nullable(); // e.g. 2004, 2011, 2017
            $table->string('profession')->nullable();
            $table->text('summary')->nullable();
            $table->json('tags')->nullable();
            $table->string('badge')->nullable();
            $table->string('location')->nullable();
            $table->text('avatar_url')->nullable();
            $table->boolean('is_verified')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumni');
    }
};
