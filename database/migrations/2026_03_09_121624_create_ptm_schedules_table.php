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
        Schema::create('ptm_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('class_name');
            $table->string('course_type')->nullable();
            $table->date('meeting_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('teacher_name');
            $table->enum('meeting_mode', ['online', 'offline']);
            $table->string('meeting_link')->nullable();
            $table->string('meeting_location')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ptm_schedules');
    }
};
