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
            $table->string('slug')->unique();
            $table->text('description');
            $table->longText('body')->nullable();
            $table->string('icon')->nullable();
            $table->string('icon_bg_class')->default('bg-secondary/15 text-secondary');
            $table->string('progress_label')->nullable();
            $table->string('progress_status')->nullable();
            $table->integer('progress_percent')->default(100);
            $table->string('bar_color_class')->default('bg-secondary');
            $table->string('achievement_text')->nullable();
            $table->decimal('target_amount', 15, 2)->nullable();
            $table->decimal('collected_amount', 15, 2)->default(0);
            $table->string('status')->default('active')->index(); // upcoming, active, completed
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('image_url')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'start_date']);
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
