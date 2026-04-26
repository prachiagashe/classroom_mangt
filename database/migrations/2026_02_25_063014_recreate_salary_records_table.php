<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop existing table
        Schema::dropIfExists('salary_records');
        
        // Recreate table with employee_code as foreign key
        Schema::create('salary_records', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code', 255);
            $table->integer('month'); // 1-12 for salary period month
            $table->integer('year'); // salary period year
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('deduction_amount', 10, 2)->nullable();
            $table->decimal('bonus_amount', 10, 2)->nullable();
            $table->decimal('net_salary', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->enum('payment_status', ['pending', 'partial', 'paid'])->default('pending');
            $table->date('payment_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('employee_code')
                  ->references('employee_code')
                  ->on('employees')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            
            // Unique constraint to prevent duplicate records
            $table->unique(['employee_code', 'month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_records');
    }
};
