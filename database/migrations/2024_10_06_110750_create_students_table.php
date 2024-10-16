<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('country_code', 10)->default('+20');
            $table->integer('age')->nullable();
            $table->string('student_code')->default(0);
            $table->integer('class')->nullable();
            $table->date('join_date');
            $table->decimal('fees', 10, 2)->default(0);
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('students');
    }
};
