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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumnus_id')->nullable()->constrained('alumni')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category')->index();
            $table->string('owner_info')->nullable();
            $table->text('description');
            $table->string('action_type')->default('whatsapp'); // whatsapp, phone, download, link
            $table->string('action_label')->default('Order via WhatsApp');
            $table->string('action_link')->nullable();
            $table->text('image_url')->nullable();
            $table->string('status')->default('pending')->index(); // draft, pending, published, rejected
            $table->string('address')->nullable();
            $table->string('city')->default('Balikpapan');
            $table->string('phone')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('website_url')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
