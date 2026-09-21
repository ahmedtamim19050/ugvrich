<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('department', 10)->nullable();
            $table->string('leader_name')->nullable();
            $table->string('leader_email')->nullable();
            $table->string('leader_phone', 40)->nullable();
            $table->json('members')->nullable();
            $table->foreignId('supervisor_id')->nullable()->constrained('experts')->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_teams');
    }
};
