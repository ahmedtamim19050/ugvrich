<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('idea_submissions', function (Blueprint $table) {
            $table->id();

            // Who is submitting
            $table->string('name', 150);
            $table->string('email', 180);
            $table->string('phone', 40)->nullable();
            $table->string('role', 20)->default('student');   // student, faculty, staff, alumni, external
            $table->string('department', 10)->nullable();     // config('rich.departments')
            $table->string('programme', 150)->nullable();     // programme or designation

            // The idea
            $table->string('title', 200);
            $table->text('problem');
            $table->text('solution');
            $table->text('beneficiaries')->nullable();
            $table->text('resources_needed')->nullable();
            $table->string('team_size', 40)->nullable();
            $table->string('document')->nullable();

            // Handling — mirrors the startup journey in config('rich.startup_stages')
            $table->string('stage', 30)->default('idea');
            $table->string('status', 20)->default('new');
            $table->text('admin_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('idea_submissions');
    }
};
