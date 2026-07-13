<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        // Honeypot check - if the honeypot field is filled, it's likely a bot
        if ($request->filled('website')) {
            return redirect()->route('contact')
                ->with('success', 'Bedankt voor uw bericht. We nemen zo snel mogelijk contact met u op.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:2000',
        ], [
            'name.required' => 'Naam is verplicht',
            'email.required' => 'E-mailadres is verplicht',
            'email.email' => 'Voer een geldig e-mailadres in',
            'subject.required' => 'Onderwerp is verplicht',
            'message.required' => 'Bericht is verplicht',
            'message.min' => 'Het bericht moet minimaal 10 tekens bevatten',
            'message.max' => 'Het bericht mag maximaal 2000 tekens bevatten',
        ]);

        if ($validator->fails()) {
            return redirect()->route('contact')
                ->withErrors($validator)
                ->withInput();
        }

        // Additional spam detection
        $message = $request->input('message');
        $isSpam = $this->detectSpam($message, $request->ip());

        ContactMessage::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'company' => $request->input('company'),
            'phone' => $request->input('phone'),
            'subject' => $request->input('subject'),
            'message' => $message,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'is_spam' => $isSpam,
        ]);

        return redirect()->route('contact')
            ->with('success', 'Bedankt voor uw bericht. We nemen zo snel mogelijk contact met u op.');
    }

    private function detectSpam(string $message, ?string $ipAddress): bool
    {
        // Check for common spam keywords
        $spamKeywords = [
            'click here', 'free money', 'guarantee', 'winner', 'congratulations',
            'lottery', 'prize', 'award', 'claim now', 'limited offer', 'act now',
            'viagra', 'cialis', 'casino', 'poker', 'betting', 'loan', 'debt',
            'weight loss', 'make money', 'work from home', 'instant', 'miracle'
        ];

        $messageLower = strtolower($message);
        foreach ($spamKeywords as $keyword) {
            if (str_contains($messageLower, $keyword)) {
                return true;
            }
        }

        // Check for excessive links
        $linkCount = substr_count($messageLower, 'http');
        if ($linkCount > 2) {
            return true;
        }

        // Check for excessive capitalization
        $capitalRatio = (preg_match_all('/[A-Z]/', $message) / strlen($message)) * 100;
        if ($capitalRatio > 50) {
            return true;
        }

        // Check for repeated characters
        if (preg_match('/(.)\1{4,}/', $message)) {
            return true;
        }

        return false;
    }
}
