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
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->string('class_name');
            $table->string('day'); // monday, tuesday, etc.
            $table->integer('period_number'); // 1, 2, 3, 4, 5
            $table->foreignId('subject_id')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->timestamps();
            
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('set null');
            $table->index(['class_name', 'day', 'period_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timetables');
    }
};
