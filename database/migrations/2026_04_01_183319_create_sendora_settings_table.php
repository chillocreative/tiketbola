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
        Schema::create('sendora_settings', function (Blueprint $table) {
            $table->id();
            $table->string('api_url')->default('https://sendora.id/api/v1');
            $table->text('api_token')->nullable();
            $table->string('sender_number')->nullable();
            $table->boolean('is_active')->default(false);
            $table->integer('timeout')->default(30);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sendora_settings');
    }
};
