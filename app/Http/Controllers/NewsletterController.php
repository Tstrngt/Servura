<?php

namespace App\Http\Controllers;

use App\Models\BillingSetting;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        if (! BillingSetting::boolean('newsletter_enabled')) {
            abort(404);
        }

        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
        ]);

        $subscriber = NewsletterSubscriber::where('email', $validated['email'])->first();

        if ($subscriber) {
            $subscriber->update([
                'name' => $validated['name'] ?? $subscriber->name,
                'unsubscribed_at' => null,
            ]);
        } else {
            NewsletterSubscriber::create($validated);
        }

        return back()->with('newsletter_success', 'Bedankt voor je aanmelding voor de nieuwsbrief.');
    }

    public function unsubscribe(string $token)
    {
        $subscriber = NewsletterSubscriber::where('token', $token)->firstOrFail();
        $subscriber->update(['unsubscribed_at' => now()]);

        return redirect()->route('home')->with('success', 'Je bent afgemeld voor de nieuwsbrief.');
    }
}
