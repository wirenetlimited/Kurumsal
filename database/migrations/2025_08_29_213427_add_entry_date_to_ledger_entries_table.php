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
            $table->date('entry_date')->nullable()->after('customer_id');
            $table->decimal('debit', 15, 2)->nullable()->after('entry_date');
            $table->decimal('credit', 15, 2)->nullable()->after('debit');
            $table->text('notes')->nullable()->after('credit');
            $table->string('related_type')->nullable()->after('notes');
            $table->unsignedBigInteger('related_id')->nullable()->after('related_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ledger_entries', function (Blueprint $table) {
            $table->dropColumn(['entry_date', 'debit', 'credit', 'notes', 'related_type', 'related_id']);
        });
    }
};
