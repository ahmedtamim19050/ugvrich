<?php

namespace App\Http\Controllers;

use App\Models\IdeaSubmission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Ideas submitted through the Startup & Incubation page. They enter the
 * journey at its first stage and are triaged from the admin panel.
 */
class IdeaSubmissionController extends Controller
{
    public function create()
    {
        return view('pages.submit-idea');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email:rfc', 'max:180'],
            'phone' => ['nullable', 'string', 'max:40'],
            'role' => ['required', Rule::in(array_keys(config('rich.idea_roles')))],
            'department' => ['nullable', Rule::in(array_keys(config('rich.departments')))],
            'programme' => ['nullable', 'string', 'max:150'],
            'title' => ['required', 'string', 'max:200'],
            'problem' => ['required', 'string', 'min:20', 'max:5000'],
            'solution' => ['required', 'string', 'min:20', 'max:5000'],
            'beneficiaries' => ['nullable', 'string', 'max:2000'],
            'resources_needed' => ['nullable', 'string', 'max:2000'],
            'team_size' => ['nullable', 'string', 'max:40'],
            'document' => ['nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx,ppt,pptx,zip,png,jpg,jpeg'],
            'website' => ['nullable', 'size:0'], // honeypot
        ], [
            'problem.min' => 'Please describe the problem in a little more detail (at least 20 characters).',
            'solution.min' => 'Please describe your solution in a little more detail (at least 20 characters).',
            'website.size' => 'Submission rejected.',
        ]);

        unset($data['website']);

        if ($request->hasFile('document')) {
            $data['document'] = $request->file('document')->store('idea-submissions', 'public');
        }

        $idea = IdeaSubmission::create($data);

        return redirect()
            ->route('ideas.thanks')
            ->with('idea_submission', [
                'reference' => 'IDEA-'.str_pad((string) $idea->id, 5, '0', STR_PAD_LEFT),
                'name' => $idea->name,
                'title' => $idea->title,
            ]);
    }

    public function thanks()
    {
        if (! session()->has('idea_submission')) {
            return redirect()->route('startup');
        }

        return view('pages.idea-thanks', [
            'submission' => session('idea_submission'),
        ]);
    }
}
