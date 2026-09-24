<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The site is read in Bangla by default and in English under /en, so every
 * field a visitor reads gets a Bangla twin beside it: `title` and `title_bn`.
 * Where a Bangla value is missing the English one still shows, so nothing
 * disappears while the translations are being written.
 */
return new class extends Migration
{
    /** table => [column => column type] */
    private array $columns = [
        'projects' => [
            'title' => 'string', 'summary' => 'text', 'description' => 'text', 'outcome' => 'text',
            'client' => 'string', 'duration' => 'string', 'lead_name' => 'string',
            'problem' => 'text', 'solution' => 'text', 'research_summary' => 'text',
            'patent_details' => 'text', 'commercial_potential' => 'text',
            'technologies' => 'json', 'team_members' => 'json',
        ],
        'service_categories' => ['name' => 'string', 'tagline' => 'string', 'description' => 'text'],
        'services' => ['name' => 'string', 'description' => 'text'],
        'core_areas' => ['title' => 'string', 'tagline' => 'string', 'description' => 'text', 'items' => 'json'],
        'experts' => [
            'name' => 'string', 'designation' => 'string', 'department' => 'string',
            'expertise' => 'json', 'research_interests' => 'text', 'bio' => 'text',
        ],
        'posts' => ['title' => 'string', 'excerpt' => 'text', 'body' => 'text', 'category' => 'string', 'location' => 'string', 'author' => 'string'],
        'publications' => ['title' => 'string', 'authors' => 'string', 'venue' => 'string', 'abstract' => 'text'],
        'innovation_areas' => ['name' => 'string', 'description' => 'text', 'focus' => 'json'],
        'facilities' => ['name' => 'string', 'description' => 'text', 'location' => 'string', 'equipment' => 'json', 'services' => 'json'],
        'testimonials' => ['name' => 'string', 'designation' => 'string', 'organization' => 'string', 'quote' => 'text'],
        'faqs' => ['question' => 'string', 'answer' => 'text'],
        'stats' => ['label' => 'string', 'suffix' => 'string'],
        'settings' => ['value' => 'text'],
        'student_teams' => ['name' => 'string', 'notes' => 'text'],
    ];

    public function up(): void
    {
        foreach ($this->columns as $table => $columns) {
            Schema::table($table, function (Blueprint $blueprint) use ($columns) {
                foreach ($columns as $column => $type) {
                    $blueprint->{$type}($column.'_bn')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->columns as $table => $columns) {
            Schema::table($table, function (Blueprint $blueprint) use ($columns) {
                $blueprint->dropColumn(array_map(fn ($column) => $column.'_bn', array_keys($columns)));
            });
        }
    }
};
