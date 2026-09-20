<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('email', 180);
            $table->string('phone', 40)->nullable();
            $table->string('organization', 150)->nullable();
            $table->string('subject', 180)->nullable();
            $table->text('message');
            $table->string('status', 20)->default('new');
            $table->text('admin_notes')->nullable();
            $table->timestamp('handled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
