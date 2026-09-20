<?php

namespace App\Http\Controllers;

use App\Models\InnovationArea;
use App\Models\Project;
use Illuminate\Http\Request;

class InnovationController extends Controller
{
    public function index(Request $request)
    {
        $areas = InnovationArea::active()
            ->withCount(['projects' => fn ($q) => $q->where('type', 'innovation')])
            ->orderBy('sort_order')
            ->get();

        $area = $areas->firstWhere('slug', $request->string('area')->toString());

        $projects = Project::with('innovationArea')
            ->where('type', 'innovation')
            ->when($area, fn ($q) => $q->where('innovation_area_id', $area->id))
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->get();

        return view('pages.innovation.index', [
            'areas' => $areas,
            'activeArea' => $area,
            'projects' => $projects,
        ]);
    }
}
