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
        Schema::table('calling_data', function (Blueprint $table) {
            $table->dateTime('follow_up_date')->nullable()->after('follow_up');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calling_data', function (Blueprint $table) {
            $table->dropColumn('follow_up_date');
        });
    }
};
