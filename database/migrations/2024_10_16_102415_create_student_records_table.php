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
        Schema::create('student_records', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_id');
            $table->dateTime('attendance_datetime')->nullable();
            $table->dateTime('expenses_datetime')->nullable();
            $table->dateTime('exam_result_datetime')->nullable();
            $table->decimal('expenses_value', 10, 2)->default(0);
            $table->text('exam_result')->nullable();
            $table->string('phone_number');
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_records');
    }
};
