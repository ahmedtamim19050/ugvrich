<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email:rfc', 'max:180'],
        ]);

        Subscriber::updateOrCreate(['email' => $data['email']], ['is_active' => true]);

        return redirect()->to(url()->previous().'#newsletter')->with('subscribed', 'You are subscribed. Thank you.');
    }
}
