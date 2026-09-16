<?php

namespace App\Http\Controllers;

use App\Models\Expert;
use App\Models\Project;
use App\Models\ServiceCategory;

class ServiceController extends Controller
{
    public function index()
    {
        return view('pages.services.index', [
            'categories' => ServiceCategory::active()->with('services')->orderBy('sort_order')->get(),
        ]);
    }

    public function show(ServiceCategory $serviceCategory)
    {
        abort_unless($serviceCategory->is_active, 404);

        $serviceCategory->load('services');

        return view('pages.services.show', [
            'category' => $serviceCategory,
            'siblings' => ServiceCategory::active()->with('services')->whereKeyNot($serviceCategory->id)->orderBy('sort_order')->get(),
            'experts' => Expert::with('category')->active()->where('service_category_id', $serviceCategory->id)->orderBy('sort_order')->take(3)->get(),
            'projects' => Project::with('category')->where('service_category_id', $serviceCategory->id)->orderBy('sort_order')->take(3)->get(),
            'expertCount' => Expert::active()->where('service_category_id', $serviceCategory->id)->count(),
            'projectCount' => Project::where('service_category_id', $serviceCategory->id)->count(),
        ]);
    }
}
