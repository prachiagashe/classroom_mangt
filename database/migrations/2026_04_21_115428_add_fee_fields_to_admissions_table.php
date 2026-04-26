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
        Schema::table('admissions', function (Blueprint $table) {
            $table->decimal('total_fees', 15, 2)->default(0)->after('remarks');
            $table->decimal('discount_fees', 15, 2)->default(0)->after('total_fees');
            $table->decimal('final_fees', 15, 2)->default(0)->after('discount_fees');
        });

        // Populate final_fees from existing total_fee for legacy data
        DB::table('admissions')->update([
            'final_fees' => DB::raw('total_fee'),
            'total_fees' => DB::raw('total_fee'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            $table->dropColumn(['total_fees', 'discount_fees', 'final_fees']);
        });
    }
};
