<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite'ta morphs kolonlarını direkt nullable yapma kısıtı olabilir.
        // Güvenli yol: tabloyu geçici olarak yeniden oluştur.
        if (Schema::hasTable('ledger_entries')) {
            Schema::create('ledger_entries_tmp', function (Blueprint $table) {
                $table->id();
                $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
                $table->string('related_type')->nullable();
                $table->unsignedBigInteger('related_id')->nullable();
                $table->date('entry_date');
                $table->decimal('debit', 12, 2)->default(0);
                $table->decimal('credit', 12, 2)->default(0);
                $table->decimal('balance_after', 12, 2)->default(0);
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->index(['related_type','related_id']);
            });

            // Veriyi kopyala
            DB::statement('INSERT INTO ledger_entries_tmp (id, customer_id, related_type, related_id, entry_date, debit, credit, balance_after, notes, created_at, updated_at)
                           SELECT id, customer_id, related_type, related_id, entry_date, debit, credit, balance_after, notes, created_at, updated_at FROM ledger_entries');

            Schema::drop('ledger_entries');
            Schema::rename('ledger_entries_tmp', 'ledger_entries');
        }
    }

    public function down(): void
    {
        // Geri alma: NOT NULL hale döndürmek veri kaybına yol açabilir.
        // Güvenli olması için sadece nullable alanları boş string/0 ile doldurup tabloyu eski şemaya geri al.
        if (Schema::hasTable('ledger_entries')) {
            // Null değerleri doldur
            DB::table('ledger_entries')->whereNull('related_type')->update(['related_type' => '']);
            DB::table('ledger_entries')->whereNull('related_id')->update(['related_id' => 0]);

            Schema::create('ledger_entries_tmp', function (Blueprint $table) {
                $table->id();
                $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
                $table->string('related_type');
                $table->unsignedBigInteger('related_id');
                $table->date('entry_date');
                $table->decimal('debit', 12, 2)->default(0);
                $table->decimal('credit', 12, 2)->default(0);
                $table->decimal('balance_after', 12, 2)->default(0);
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->index(['related_type','related_id']);
            });

            DB::statement('INSERT INTO ledger_entries_tmp (id, customer_id, related_type, related_id, entry_date, debit, credit, balance_after, notes, created_at, updated_at)
                           SELECT id, customer_id, related_type, related_id, entry_date, debit, credit, balance_after, notes, created_at, updated_at FROM ledger_entries');

            Schema::drop('ledger_entries');
            Schema::rename('ledger_entries_tmp', 'ledger_entries');
        }
    }
};


