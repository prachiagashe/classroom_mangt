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
            // Remove unique constraint from sr_no if it exists and make it nullable
            $table->integer('sr_no')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calling_data', function (Blueprint $table) {
            // Revert changes if needed
            $table->integer('sr_no')->nullable(false)->change();
        });
    }
};
