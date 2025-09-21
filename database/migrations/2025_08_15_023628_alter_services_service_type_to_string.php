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
        // 1) Geçici kolonu ekle (yoksa)
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'service_type_tmp')) {
                $table->string('service_type_tmp')->nullable()->after('provider_id');
            }
        });

        // 2) Mevcut verileri tmp kolona taşı (kaynak olarak önce eski enum kolonu, yoksa zaten tmp vardır)
        if (Schema::hasColumn('services', 'service_type')) {
            \DB::table('services')->orderBy('id')->select('id', 'service_type')->chunkById(500, function ($rows) {
                foreach ($rows as $row) {
                    \DB::table('services')->where('id', $row->id)->update([
                        'service_type_tmp' => (string) $row->service_type,
                    ]);
                }
            });
        }

        // 3) Eski enum kolonu düş (varsa)
        Schema::table('services', function (Blueprint $table) {
            if (Schema::hasColumn('services', 'service_type')) {
                $table->dropColumn('service_type');
            }
        });

        // 4) Yeni string kolonu ekle (yoksa)
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'service_type')) {
                $table->string('service_type')->nullable()->after('provider_id');
            }
        });

        // 5) Veriyi tmp -> service_type'a geri taşı (tmp varsa)
        if (Schema::hasColumn('services', 'service_type_tmp')) {
            \DB::table('services')->orderBy('id')->select('id', 'service_type_tmp')->chunkById(500, function ($rows) {
                foreach ($rows as $row) {
                    \DB::table('services')->where('id', $row->id)->update([
                        'service_type' => $row->service_type_tmp,
                    ]);
                }
            });
        }

        // 6) Geçici kolonu kaldır
        Schema::table('services', function (Blueprint $table) {
            if (Schema::hasColumn('services', 'service_type_tmp')) {
                $table->dropColumn('service_type_tmp');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Geri dönüş operasyonu uygulanmıyor. İhtiyaç olursa ayrı migration ile enum'a dönüş yapılmalıdır.
    }
};
