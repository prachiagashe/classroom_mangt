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
        Schema::create('calling_data', function (Blueprint $table) {
            $table->id();
            $table->integer('sr_no');
            $table->string('school_name');
            $table->string('student_name');
            $table->string('mobile_no');
            $table->enum('response', ['positive', 'negative'])->nullable();
            $table->enum('call_status', ['done', 'not_received'])->nullable();
            $table->boolean('visit_branch')->default(false);
            $table->boolean('follow_up')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calling_data');
    }
};
