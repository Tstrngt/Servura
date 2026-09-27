<?php

namespace App\Http\Controllers;

use App\Models\AbuseReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LegalController extends Controller
{
    public function terms()
    {
        return view('legal.terms');
    }

    public function privacy()
    {
        return view('legal.privacy');
    }

    public function cookies()
    {
        return view('legal.cookies');
    }

    public function hosting()
    {
        return view('legal.hosting');
    }

    public function acceptableUse()
    {
        return view('legal.acceptable-use');
    }

    public function dpa()
    {
        return view('legal.dpa');
    }

    public function abuse()
    {
        return view('legal.abuse');
    }

    public function storeAbuse(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'domain' => 'nullable|string|max:255',
            'url' => 'required|url|max:500',
            'category' => 'required|string|in:phishing,spam,malware,fraud,illegal,copyright,privacy,other',
            'description' => 'required|string|max:5000',
            'reason' => 'required|string|max:2000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,txt,md|max:10240',
            'truth_declaration' => 'accepted',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('abuse-attachments', 'private');
        }

        $report = AbuseReport::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'domain' => $validated['domain'] ?? null,
            'url' => $validated['url'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'reason' => $validated['reason'],
            'attachment_path' => $attachmentPath,
            'status' => 'new',
        ]);

        $abuseEmail = config('company.abuse_email');
        if ($abuseEmail && filter_var($abuseEmail, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($abuseEmail)->send(new \App\Mail\AbuseReportReceived($report));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return redirect()->route('legal.abuse')
            ->with('success', 'Uw melding is ontvangen. Wij nemen deze zo snel mogelijk in behandeling.');
    }
}
