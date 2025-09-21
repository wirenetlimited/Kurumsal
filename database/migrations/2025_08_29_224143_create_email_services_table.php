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
        Schema::create('email_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->string('email_provider')->nullable();
            $table->string('email_plan')->nullable();
            $table->integer('mailbox_count')->nullable();
            $table->integer('storage_limit')->nullable();
            $table->string('smtp_server')->nullable();
            $table->string('imap_server')->nullable();
            $table->string('pop3_server')->nullable();
            $table->string('webmail_url')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_services');
    }
};
