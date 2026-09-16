<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $categories = ServiceCategory::active()->orderBy('sort_order')->get();

        $projects = Project::with('category')
            ->when($request->string('area')->toString(), function ($q, $slug) {
                $q->whereHas('category', fn ($c) => $c->where('slug', $slug));
            })
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->paginate(9)
            ->withQueryString();

        return view('pages.projects.index', compact('projects', 'categories'));
    }

    public function show(Project $project)
    {
        $project->load('category');

        return view('pages.projects.show', [
            'project' => $project,
            'related' => Project::with('category')
                ->whereKeyNot($project->id)
                ->when($project->service_category_id, fn ($q) => $q->where('service_category_id', $project->service_category_id))
                ->orderBy('sort_order')
                ->take(3)
                ->get(),
        ]);
    }
}
