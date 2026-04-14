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
            $table->string('requirement_slot')->nullable();
            $table->foreignId('requirement_classroom_id')->nullable()->constrained('classrooms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['requirement_classroom_id']);
            $table->dropColumn(['requirement_slot', 'requirement_classroom_id']);
        });
    }
};
