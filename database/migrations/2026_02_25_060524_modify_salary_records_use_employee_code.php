<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salary_records', function (Blueprint $table) {
            // Drop foreign key and employee_id column defensively
            if (Schema::hasColumn('salary_records', 'employee_id')) {
                try {
                    $table->dropForeign(['employee_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist or have a different name
                }
                $table->dropColumn('employee_id');
            }
            
            // Add employee_code column if it doesn't exist
            if (!Schema::hasColumn('salary_records', 'employee_code')) {
                $table->string('employee_code', 255)->after('id');
                
                // Add foreign key constraint
                $table->foreign('employee_code')
                      ->references('employee_code')
                      ->on('employees')
                      ->onDelete('cascade')
                      ->onUpdate('cascade');
                
                // Update unique constraint
                $table->unique(['employee_code', 'month', 'year']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('salary_records', function (Blueprint $table) {
            $table->dropForeign(['employee_code']);
            $table->dropColumn('employee_code');
            
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->unique(['employee_id', 'month', 'year']);
        });
    }
};
