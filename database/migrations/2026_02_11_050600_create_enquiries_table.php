<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();

            $table->string('enquiry_no')->nullable();
            $table->string('branch_code')->nullable();
            $table->date('date')->nullable();
            

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('surname');

            $table->string('status')->default('new');
            $table->string('class');
            $table->string('school_time')->nullable();
            $table->string('last_year_percentage')->nullable();
            $table->string('school_name')->nullable();

            $table->string('medium')->nullable();
            $table->date('dob')->nullable();
            $table->string('father_occupation')->nullable();

            $table->string('parent_mobile');
            $table->string('student_mobile')->nullable();
            $table->string('whatsapp')->nullable();

            $table->text('address')->nullable();

            $table->json('foundation')->nullable();
            $table->json('course')->nullable();

            $table->string('sibling1')->nullable();
            $table->string('sibling2')->nullable();

            $table->string('reference1')->nullable();
            $table->string('reference2')->nullable();

            $table->json('source')->nullable();
            $table->string('remarks')->nullable();

            $table->text('parent_feedback')->nullable();

            $table->decimal('total_fees',10,2)->nullable();
            $table->decimal('discount_fees',10,2)->nullable();
            $table->decimal('final_fees',10,2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};
