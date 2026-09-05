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
        Schema::create('daily_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->date('date');
            $table->unsignedBigInteger('hifz_surah')->nullable();
            $table->integer('hifz_from_ayah')->nullable();
            $table->integer('hifz_to_ayah')->nullable();
            $table->unsignedBigInteger('muraja_surah')->nullable();
            $table->integer('muraja_from_ayah')->nullable();
            $table->integer('muraja_to_ayah')->nullable();
            $table->string('rating')->nullable();
            $table->boolean('is_present')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_tracking');
    }
};
