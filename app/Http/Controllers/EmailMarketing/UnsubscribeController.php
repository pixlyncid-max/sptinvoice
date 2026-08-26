<?php

namespace App\Http\Controllers\EmailMarketing;

use App\Http\Controllers\Controller;
use App\Models\EmailContact;
use Illuminate\Http\Request;

class UnsubscribeController extends Controller
{
    public function unsubscribe(string $token)
    {
        $contact = EmailContact::where('unsubscribe_token', $token)->first();

        if ($contact) {
            $contact->update(['is_subscribed' => false]);
        }

        return view('email-marketing.unsubscribe.success', compact('contact', 'token'));
    }

    public function resubscribe(string $token)
    {
        $contact = EmailContact::where('unsubscribe_token', $token)->first();

        if ($contact) {
            $contact->update(['is_subscribed' => true]);
            return redirect()->route('email-marketing.unsubscribe', ['token' => $token])
                ->with('resubscribed', true);
        }

        return redirect()->route('email-marketing.unsubscribe', ['token' => $token]);
    }
}
