<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('department', 10)->nullable();   // config('rich.departments')
            $table->string('location', 150)->nullable();
            $table->text('description')->nullable();
            $table->json('equipment')->nullable();          // headline equipment, one per line
            $table->json('services')->nullable();           // what it can be booked for
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_bookable')->default(true);  // open to external clients
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
