<?php

namespace App\Http\Controllers;

use App\Models\CoreArea;
use App\Models\Expert;
use App\Models\Faq;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Project;
use App\Models\Publication;
use App\Models\ServiceCategory;
use App\Models\Stat;
use App\Models\Testimonial;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home', [
            'coreAreas' => CoreArea::active()->orderBy('sort_order')->get(),
            'categories' => ServiceCategory::active()->with('services')->orderBy('sort_order')->get(),
            'stats' => Stat::active()->orderBy('sort_order')->get(),
            'projects' => Project::with('category')->featured()->orderBy('sort_order')->take(3)->get(),
            'experts' => Expert::with('category')->active()->where('is_featured', true)->orderBy('sort_order')->take(6)->get(),
            'testimonials' => Testimonial::active()->orderBy('sort_order')->get(),
            'faqs' => Faq::active()->orderBy('sort_order')->take(6)->get(),
            'posts' => Post::published()->orderByDesc('published_at')->take(3)->get(),
            'partners' => Partner::active()->orderBy('sort_order')->get(),
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
        return view('pages.research', [
            'coreAreas' => CoreArea::active()->whereIn('slug', ['research', 'innovation'])->orderBy('sort_order')->get(),
            'publications' => Publication::active()->where('kind', 'publication')->orderByDesc('year')->get(),
            'fundedProjects' => Publication::active()->where('kind', 'funded-project')->orderByDesc('year')->get(),
            'projects' => Project::with('category')->orderByDesc('year')->take(3)->get(),
            'stats' => Stat::active()->orderBy('sort_order')->get(),
        ]);
    }

    public function contact()
    {
        return view('pages.contact', [
            'categories' => ServiceCategory::active()->orderBy('sort_order')->get(),
        ]);
    }
}
