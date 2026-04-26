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
            $table->string('payment_mode')->nullable()->after('fee_status');
            $table->string('installment_type')->nullable()->after('payment_mode');
            $table->integer('installment_count')->nullable()->after('installment_type');
            $table->decimal('installment_amount', 10, 2)->nullable()->after('installment_count');
            $table->date('installment_start_date')->nullable()->after('installment_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            $table->dropColumn([
                'payment_mode',
                'installment_type', 
                'installment_count',
                'installment_amount',
                'installment_start_date'
            ]);
        });
    }
};
