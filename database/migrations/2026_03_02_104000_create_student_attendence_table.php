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
        Schema::create('student_attendence', function (Blueprint $table) {
            $table->id();
            $table->string('roll_no');
            $table->string('name');
            $table->string('month');
            $table->string('day_1')->nullable();
            $table->string('day_2')->nullable();
            $table->string('day_3')->nullable();
            $table->string('day_4')->nullable();
            $table->string('day_5')->nullable();
            $table->string('day_6')->nullable();
            $table->string('day_7')->nullable();
            $table->string('day_8')->nullable();
            $table->string('day_9')->nullable();
            $table->string('day_10')->nullable();
            $table->string('day_11')->nullable();
            $table->string('day_12')->nullable();
            $table->string('day_13')->nullable();
            $table->string('day_14')->nullable();
            $table->string('day_15')->nullable();
            $table->string('day_16')->nullable();
            $table->string('day_17')->nullable();
            $table->string('day_18')->nullable();
            $table->string('day_19')->nullable();
            $table->string('day_20')->nullable();
            $table->string('day_21')->nullable();
            $table->string('day_22')->nullable();
            $table->string('day_23')->nullable();
            $table->string('day_24')->nullable();
            $table->string('day_25')->nullable();
            $table->string('day_26')->nullable();
            $table->string('day_27')->nullable();
            $table->string('day_28')->nullable();
            $table->string('day_29')->nullable();
            $table->string('day_30')->nullable();
            $table->string('day_31')->nullable();
            $table->integer('total_p')->default(0);
            $table->integer('total_a')->default(0);
            $table->decimal('percentage', 5, 2)->default(0);
            $table->timestamps();
            
            $table->unique(['roll_no', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_attendence');
    }
};
