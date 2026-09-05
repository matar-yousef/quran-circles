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
        Schema::table('daily_tracking', function (Blueprint $table) {
            $table->decimal('hifz_pages', 8, 2)->change();
            $table->decimal('muraja_pages', 8, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_tracking', function (Blueprint $table) {
            $table->integer('hifz_pages')->change();
            $table->integer('muraja_pages')->change();
        });
    }
};
