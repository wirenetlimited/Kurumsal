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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('group')->nullable()->after('key')->index();
            $table->string('label')->nullable()->after('type');
            $table->text('description')->nullable()->after('label');
            $table->json('options')->nullable()->after('description');
            $table->boolean('is_public')->default(false)->after('options');
            $table->integer('sort_order')->default(0)->after('is_public');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['group', 'label', 'description', 'options', 'is_public', 'sort_order']);
        });
    }
};
