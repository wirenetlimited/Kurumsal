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
        Schema::table('services', function (Blueprint $table) {
            // Customer ID index for customer-based queries
            $table->index('customer_id');
            
            // Status index for active service filtering
            $table->index('status');
            
            // Composite index for customer_id + status (most common query pattern)
            $table->index(['customer_id', 'status']);
            
            // Payment type index for payment type filtering
            $table->index('payment_type');
            
            // Start date index for date-based calculations
            $table->index('start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropIndex(['customer_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['customer_id', 'status']);
            $table->dropIndex(['payment_type']);
            $table->dropIndex(['start_date']);
        });
    }
};
