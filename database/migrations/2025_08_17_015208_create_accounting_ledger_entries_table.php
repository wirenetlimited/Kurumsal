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
        Schema::create('accounting_ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('payment_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('type', ['debit', 'credit', 'reverse']);
            $table->decimal('amount', 12, 2);
            $table->timestamps();

            // Unique indexes for idempotency
            $table->unique(['invoice_id', 'type'], 'unique_debit_per_invoice');
            $table->unique(['payment_id', 'type'], 'unique_credit_per_payment');
            
            // Indexes for performance
            $table->index(['customer_id', 'type']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_ledger_entries');
    }
};
