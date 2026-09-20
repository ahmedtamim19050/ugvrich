<?php

namespace App\Http\Controllers;

use App\Models\CoreArea;
use App\Models\Expert;
use App\Models\Partner;
use App\Models\Project;
use App\Models\Publication;
use App\Models\ServiceCategory;
use App\Models\Stat;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home', [
            'stats' => Stat::active()->orderBy('sort_order')->get(),
            'projects' => Project::with('innovationArea')
                ->featured()
                ->ofType('innovation')
                ->orderBy('sort_order')
                ->get(),
        ]);
    }

    public function about()
    {
        return view('pages.about', [
            'coreAreas' => CoreArea::active()->orderBy('sort_order')->get(),
            'stats' => Stat::active()->orderBy('sort_order')->get(),
            'partners' => Partner::active()->orderBy('sort_order')->get(),
            'experts' => Expert::with('category')->active()->where('is_featured', true)->orderBy('sort_order')->take(6)->get(),
        ]);
    }

    public function research()
    {
        // Research areas are read off the faculty's own expertise tags, so the
        // list stays true to who actually works here.
        $researchAreas = Expert::active()
            ->get(['expertise'])
            ->flatMap(fn ($expert) => $expert->expertise ?? [])
            ->map(fn ($tag) => trim($tag))
            ->filter()
            ->countBy()
            ->sortDesc();

        return view('pages.research', [
            'researchAreas' => $researchAreas,
            'researchers' => Expert::active()->with('category')->orderByDesc('is_featured')->orderBy('sort_order')->get(),
            'ongoing' => Project::with(['category', 'innovationArea'])
                ->where('type', 'research')
                ->where('status', '!=', 'completed')
                ->orderByDesc('is_featured')
                ->orderBy('sort_order')
                ->get(),
        ]);
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function startup()
    {
        return view('pages.startup');
    }

    /** The consultancy request form: its own page, its own submissions. */
    public function requestConsultancy()
    {
        return view('pages.consultancy-request', [
            'categories' => ServiceCategory::active()->with('services')->orderBy('sort_order')->get(),
        ]);
    }
}
