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
        Schema::table('calling_data', function (Blueprint $table) {
            // Fix call_status column to accommodate "Not Received" value
            // Use nullable to avoid truncation issues during migration
            $table->string('call_status', 50)->nullable()->change();
        });
        
        // Update any existing problematic data
        DB::table('calling_data')
            ->where('call_status', 'like', '%not_received%')
            ->update(['call_status' => 'Not Received']);
            
        DB::table('calling_data')
            ->where('call_status', 'like', '%done%')
            ->update(['call_status' => 'Done']);
            
        DB::table('calling_data')
            ->where('call_status', 'like', '%pending%')
            ->update(['call_status' => 'Pending']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calling_data', function (Blueprint $table) {
            // Revert to original size
            $table->string('call_status', 20)->nullable()->change();
        });
    }
};
