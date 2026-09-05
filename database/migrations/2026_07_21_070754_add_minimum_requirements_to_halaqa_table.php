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
        Schema::table('halaqa', function (Blueprint $table) {
            $table->integer('min_hifz_pages')->default(1);
            $table->integer('min_muraja_pages')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('halaqa', function (Blueprint $table) {
            //
        });
    }
};
