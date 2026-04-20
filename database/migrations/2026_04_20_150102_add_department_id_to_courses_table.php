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
            $table->foreignId('department_id')->nullable()->after('teacher_id')->constrained('departments')->nullOnDelete();
        });

        // Populate existing courses based on their subject's department using PostgreSQL syntax
        \Illuminate\Support\Facades\DB::statement('
            UPDATE courses 
            SET department_id = subjects.department_id 
            FROM subjects 
            WHERE courses.subject_id = subjects.id
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
    }
};
