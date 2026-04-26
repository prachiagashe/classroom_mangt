<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salary_records', function (Blueprint $table) {
            // Add employee_code column
            $table->string('employee_code', 255)->after('id');
            
            // Add foreign key constraint
            $table->foreign('employee_code')
                  ->references('employee_code')
                  ->on('employees')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            
            // Add unique constraint
            $table->unique(['employee_code', 'month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::table('salary_records', function (Blueprint $table) {
            $table->dropForeign(['employee_code']);
            $table->dropColumn('employee_code');
        });
    }
};
