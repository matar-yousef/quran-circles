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
        Schema::table('daily_tracking_details', function (Blueprint $table) {
            // إضافة حقل سورة النهاية ليقبل القيم الفارغة ويكون مربوطاً بجدول السور
            $table->unsignedBigInteger('to_surah_id')->nullable()->after('from_ayah');

            // (اختياري ولكن يُفضل) إضافة مفتاح أجنبي للربط الصحيح
            // $table->foreign('to_surah_id')->references('id')->on('surahs')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_tracking_details', function (Blueprint $table) {
            $table->dropColumn('to_surah_id');
        });
    }
};
