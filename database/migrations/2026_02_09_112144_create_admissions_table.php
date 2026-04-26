<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('admissions', function (Blueprint $table) {
        $table->id();
        $table->string('student_name');
        $table->string('parent_name');
        $table->string('contact');
        $table->string('email')->nullable();
        $table->string('class');
        $table->string('roll_number')->unique();
        $table->date('admission_date');
        $table->enum('fee_status', ['paid','pending','overdue'])->default('pending');
        $table->decimal('total_fee', 10, 2);
        $table->decimal('paid_amount', 10, 2)->default(0);
        $table->text('address')->nullable();
        $table->date('date_of_birth')->nullable();
        $table->string('blood_group')->nullable();
        $table->string('previous_school')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};
