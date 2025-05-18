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
        Schema::table('vouchers', function (Blueprint $table) {
            $table->decimal('discount_percentage', 5, 2)->after('code');
            // Drop the amount column if it exists
            if (Schema::hasColumn('vouchers', 'amount')) {
                $table->dropColumn('amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn('discount_percentage');
            // Add back the amount column if it was dropped
            $table->decimal('amount', 10, 2)->after('code');
        });
    }
}; 