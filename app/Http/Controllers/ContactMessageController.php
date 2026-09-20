<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

/**
 * General enquiries from the contact page. Consultancy work goes through
 * ConsultancyRequestController instead — that form asks for much more.
 */
class ContactMessageController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email:rfc', 'max:180'],
            'phone' => ['nullable', 'string', 'max:40'],
            'organization' => ['nullable', 'string', 'max:150'],
            'subject' => ['nullable', 'string', 'max:180'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
            'website' => ['nullable', 'size:0'], // honeypot
        ], [
            'message.min' => 'Please write a little more so we can help (at least 10 characters).',
            'website.size' => 'Submission rejected.',
        ]);

        unset($data['website']);

        ContactMessage::create($data);

        return back()->with('contact_sent', true)->withFragment('message-form');
    }
}
