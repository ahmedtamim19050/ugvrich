<?php

namespace App\Http\Controllers;

use App\Models\ConsultancyRequest;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ConsultancyRequestController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'organization' => ['nullable', 'string', 'max:150'],
            'designation' => ['nullable', 'string', 'max:150'],
            'email' => ['required', 'email:rfc', 'max:180'],
            'phone' => ['nullable', 'string', 'max:40'],
            'service_category_id' => ['nullable', Rule::exists(ServiceCategory::class, 'id')],
            'area_of_interest' => ['nullable', 'string', 'max:180'],
            'requirement' => ['required', 'string', 'min:20', 'max:5000'],
            'document' => ['nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,png,jpg,jpeg'],
            'website' => ['nullable', 'size:0'], // honeypot
        ], [
            'requirement.min' => 'Please describe your requirement in a little more detail (at least 20 characters).',
            'website.size' => 'Submission rejected.',
        ]);

        unset($data['website']);

        if ($request->hasFile('document')) {
            $data['document'] = $request->file('document')->store('consultancy-requests', 'public');
        }

        $consultancy = ConsultancyRequest::create($data);

        return redirect()
            ->route('consultancy.thanks')
            ->with('consultancy_request', [
                'reference' => 'RICH-'.str_pad((string) $consultancy->id, 5, '0', STR_PAD_LEFT),
                'name' => $consultancy->name,
                'email' => $consultancy->email,
                'area' => $consultancy->service_category_id
                    ? ServiceCategory::find($consultancy->service_category_id)?->name
                    : null,
            ]);
    }

    public function thanks()
    {
        // Only reachable straight after a submission; otherwise send people to the form.
        if (! session()->has('consultancy_request')) {
            return redirect()->route('contact');
        }

        return view('pages.consultancy-thanks', [
            'submission' => session('consultancy_request'),
        ]);
    }
}
