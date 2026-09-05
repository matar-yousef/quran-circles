<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daily_tracking', function (Blueprint $table) {
            $table->dropColumn([
                'hifz_surah',
                'hifz_from_ayah',
                'hifz_to_ayah',
                'muraja_surah',
                'muraja_from_ayah',
                'muraja_to_ayah',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('daily_tracking', function (Blueprint $table) {
            $table->unsignedBigInteger('hifz_surah')->nullable();
            $table->integer('hifz_from_ayah')->nullable();
            $table->integer('hifz_to_ayah')->nullable();
            $table->unsignedBigInteger('muraja_surah')->nullable();
            $table->integer('muraja_from_ayah')->nullable();
            $table->integer('muraja_to_ayah')->nullable();
        });
    }
};
