<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing theme colors to valid values
        DB::table('users')
            ->where('theme_color', 'emerald')
            ->update(['theme_color' => 'green']);
            
        DB::table('users')
            ->where('theme_color', 'peter-river')
            ->update(['theme_color' => 'blue']);
            
        // Set any other invalid colors to blue
        DB::table('users')
            ->whereNotIn('theme_color', ['blue', 'green', 'purple', 'orange'])
            ->update(['theme_color' => 'blue']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to old values
        DB::table('users')
            ->where('theme_color', 'green')
            ->update(['theme_color' => 'emerald']);
            
        DB::table('users')
            ->where('theme_color', 'blue')
            ->where('id', '!=', 3) // Keep user 3 as blue
            ->update(['theme_color' => 'peter-river']);
    }
};
