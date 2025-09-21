<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('surname')->nullable()->after('name');
            $table->string('phone_mobile')->nullable()->after('phone');
            $table->string('website')->nullable()->after('email');

            $table->string('city')->nullable()->after('address');
            $table->string('district')->nullable()->after('city');
            $table->string('zip')->nullable()->after('district');
            $table->string('country')->nullable()->after('zip');

            $table->text('invoice_address')->nullable()->after('country');
            $table->string('invoice_city')->nullable()->after('invoice_address');
            $table->string('invoice_district')->nullable()->after('invoice_city');
            $table->string('invoice_zip')->nullable()->after('invoice_district');
            $table->string('invoice_country')->nullable()->after('invoice_zip');

            $table->text('notes')->nullable()->after('invoice_country');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'surname', 'phone_mobile', 'website',
                'city', 'district', 'zip', 'country',
                'invoice_address', 'invoice_city', 'invoice_district', 'invoice_zip', 'invoice_country',
                'notes',
            ]);
        });
    }
};


