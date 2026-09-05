<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_tracking_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_tracking_id')->constrained('daily_tracking')->cascadeOnDelete();
            $table->enum('type', ['hifz', 'muraja']);
            $table->unsignedBigInteger('surah_id');
            $table->integer('from_ayah');
            $table->integer('to_ayah');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_tracking_details');
    }
};
