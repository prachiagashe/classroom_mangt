<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->string('course_name')->nullable()->after('class_name');
            $table->string('program_type')->nullable()->after('course_name');
        });

        // Migrate existing data
        $subjects = DB::table('subjects')->get();
        foreach ($subjects as $subject) {
            $courseName = $subject->course_type ?: 'REGULAR';
            $programType = 'Regular';

            if ($subject->course_type && str_contains($subject->course_type, 'Repeater')) {
                $courseName = trim(str_replace('Repeater', '', $subject->course_type));
                $programType = 'Repeater';
            }

            DB::table('subjects')->where('id', $subject->id)->update([
                'course_name' => $courseName,
                'program_type' => $programType
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn(['course_name', 'program_type']);
        });
    }
};
