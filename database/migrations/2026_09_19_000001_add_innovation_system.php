<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('innovation_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('department', 10)->nullable();
            $table->text('description')->nullable();
            $table->json('focus')->nullable();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->string('code', 40)->nullable()->unique()->after('id');
            $table->string('type', 20)->default('consultancy')->after('slug');
            $table->string('department', 10)->nullable()->after('type');
            $table->foreignId('innovation_area_id')->nullable()->after('service_category_id')->constrained()->nullOnDelete();

            // Team & management
            $table->string('lead_name')->nullable();
            $table->json('team_members')->nullable();
            $table->decimal('budget', 14, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('deadline')->nullable();
            $table->unsignedTinyInteger('progress')->default(0);
            $table->string('stage', 30)->nullable();
            $table->string('patent_status', 30)->default('none');
            $table->string('commercialization_status', 30)->default('none');

            // Innovation story
            $table->text('problem')->nullable();
            $table->text('solution')->nullable();
            $table->json('technologies')->nullable();
            $table->text('research_summary')->nullable();
            $table->text('patent_details')->nullable();
            $table->text('commercial_potential')->nullable();
            $table->string('video_url')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropConstrainedForeignId('innovation_area_id');
            $table->dropUnique(['code']);
            $table->dropColumn([
                'code', 'type', 'department', 'lead_name', 'team_members', 'budget', 'start_date', 'deadline',
                'progress', 'stage', 'patent_status', 'commercialization_status', 'problem', 'solution',
                'technologies', 'research_summary', 'patent_details', 'commercial_potential', 'video_url',
            ]);
        });

        Schema::dropIfExists('innovation_areas');
    }
};
