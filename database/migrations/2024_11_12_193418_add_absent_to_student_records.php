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
        Schema::table('student_records', function (Blueprint $table) {
            $table->dateTime('absence_datetime')->after('attendance_out_datetime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_records', function (Blueprint $table) {
            $table->dropColumn('absence_datetime');
        });
    }
};
