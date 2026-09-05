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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('grade');
            $table->string('address');
            $table->string('student_id_number')->unique();
            $table->date('birth_date');
            $table->string('father_full_name');
            $table->string('father_id_number')->unique();
            $table->string('guardian_phone');
            $table->string('current_juz');
            $table->foreignId('halaqa_id')->constrained('halaqa')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
