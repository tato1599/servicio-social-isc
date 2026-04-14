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
        Schema::table('courses', function (Blueprint $table) {
            $table->string('study_plan')->nullable();
            $table->integer('students_count')->default(0);
            $table->integer('students_count_adjusted')->default(0);
            $table->integer('groups_count')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['study_plan', 'students_count', 'students_count_adjusted', 'groups_count']);
        });
    }
};
