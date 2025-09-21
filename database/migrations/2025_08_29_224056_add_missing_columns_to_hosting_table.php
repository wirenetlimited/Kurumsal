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
        Schema::table('hosting', function (Blueprint $table) {
            $table->string('plan_name')->nullable()->after('hosting_plan');
            $table->string('server_name')->nullable()->after('plan_name');
            $table->string('cpanel_username')->nullable()->after('server_name');
            $table->string('panel_ref')->nullable()->after('cpanel_username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hosting', function (Blueprint $table) {
            $table->dropColumn(['plan_name', 'server_name', 'cpanel_username', 'panel_ref']);
        });
    }
};
