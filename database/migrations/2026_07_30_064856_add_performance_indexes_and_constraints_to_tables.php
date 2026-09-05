<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daily_tracking', function (Blueprint $table) {
            $table->unique(['student_id', 'date'], 'student_date_unique');
            $table->index(['date', 'is_present'], 'date_presence_index');
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->index(['student_id', 'exam_date'], 'student_exam_date_index');
        });

        Schema::table('daily_tracking_details', function (Blueprint $table) {
            $table->index(['daily_tracking_id', 'type'], 'tracking_type_index');
        });

        Schema::table('halaqa_user', function (Blueprint $table) {
            $table->unique(['halaqa_id', 'user_id'], 'halaqa_user_unique');
        });

        Schema::table('surahs', function (Blueprint $table) {
            $table->unique('number', 'surahs_number_unique');
        });

        Schema::table('daily_tracking_details', function (Blueprint $table) {
            $table->foreign('surah_id')->references('id')->on('surahs')->onDelete('cascade');
            $table->foreign('to_surah_id')->references('id')->on('surahs')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('daily_tracking', function (Blueprint $table) {
            $table->dropUnique('student_date_unique');
            $table->dropIndex('date_presence_index');
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->dropIndex('student_exam_date_index');
        });

        Schema::table('daily_tracking_details', function (Blueprint $table) {
            $table->dropIndex('tracking_type_index');
            $table->dropForeign(['surah_id']);
            $table->dropForeign(['to_surah_id']);
        });

        Schema::table('halaqa_user', function (Blueprint $table) {
            $table->dropUnique('halaqa_user_unique');
        });

        Schema::table('surahs', function (Blueprint $table) {
            $table->dropUnique('surahs_number_unique');
        });
    }
};
