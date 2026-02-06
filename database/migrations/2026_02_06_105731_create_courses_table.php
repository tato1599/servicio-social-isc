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
        Schema::create('courses', function (Blueprint $table) {
    $table->id();
    $table->foreignId('subject_id')->constrained('subjects');
    $table->foreignId('teacher_id')->constrained('teachers');
    $table->string('group_code'); // 'A'
    $table->string('period'); // 'Aug-Dec 2026'
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
