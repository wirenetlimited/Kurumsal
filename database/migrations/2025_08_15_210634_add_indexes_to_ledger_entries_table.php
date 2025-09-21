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
        Schema::table('ledger_entries', function (Blueprint $table) {
            // Customer ID index for balance calculations
            $table->index('customer_id');
            
            // Composite index for customer_id + entry_date for chronological queries
            $table->index(['customer_id', 'entry_date']);
            
            // Index for debit/credit columns used in SUM operations
            $table->index(['customer_id', 'debit']);
            $table->index(['customer_id', 'credit']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ledger_entries', function (Blueprint $table) {
            $table->dropIndex(['customer_id']);
            $table->dropIndex(['customer_id', 'entry_date']);
            $table->dropIndex(['customer_id', 'debit']);
            $table->dropIndex(['customer_id', 'credit']);
        });
    }
};
