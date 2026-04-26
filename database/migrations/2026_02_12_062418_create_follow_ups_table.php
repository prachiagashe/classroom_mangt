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
        Schema::create('follow_ups', function (Blueprint $table) {

            $table->id();

            // Relation with enquiries table
            $table->foreignId('enquiry_id')
                  ->constrained('enquiries')
                  ->onDelete('cascade');

            // Snapshot of student full name
            $table->string('student_name');

            // Follow-up details
            $table->date('followup_date');
            $table->time('followup_time');
            $table->string('type'); // Call / Meeting / WhatsApp etc.

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follow_ups');
    }
};
