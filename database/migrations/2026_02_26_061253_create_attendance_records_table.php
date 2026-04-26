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
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code');
            $table->date('attendance_date');
            $table->enum('status', ['present', 'absent', 'leave']);
            $table->timestamps();
            
            // Add unique constraint for employee_code + attendance_date
            $table->unique(['employee_code', 'attendance_date']);
            
            // Add foreign key constraint
            $table->foreign('employee_code')->references('employee_code')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
