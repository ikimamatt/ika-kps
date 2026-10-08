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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('icon');
            $table->string('icon_bg_class')->default('bg-secondary/15 text-secondary');
            $table->string('progress_label');
            $table->string('progress_status');
            $table->integer('progress_percent')->default(100);
            $table->string('bar_color_class')->default('bg-secondary');
            $table->string('achievement_text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
