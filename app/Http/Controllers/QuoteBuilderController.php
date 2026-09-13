<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuoteBuilderController extends Controller
{
    public function index(Request $request)
    {
        return view('quote.builder');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'goal' => 'required|string|max:255',
            'pages' => 'required|string|max:255',
            'visitors' => 'required|string|max:255',
            'design' => 'required|string|max:255',
            'current_website' => 'nullable|string|max:255',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
            'content' => 'nullable|array',
            'content.*' => 'string|max:255',
            'timeline' => 'required|string|max:255',
            'budget' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:5000',
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
        ], [
            'goal.required' => 'Geef aan wat het doel van uw website is.',
            'pages.required' => 'Geef het verwachte aantal pagina\'s op.',
            'visitors.required' => 'Geef het verwachte aantal bezoekers op.',
            'design.required' => 'Geef aan wat uw wensen zijn voor ontwerp en huisstijl.',
            'timeline.required' => 'Geef een gewenste oplevering op.',
            'name.required' => 'Naam is verplicht.',
            'email.required' => 'E-mailadres is verplicht.',
            'email.email' => 'Voer een geldig e-mailadres in.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('quote.builder')
                ->withErrors($validator)
                ->withInput();
        }

        $features = $request->input('features', []);
        $content = $request->input('content', []);

        $messageText = "Offerte-aanvraag via de offerte-samensteller.\n\n";
        $messageText .= "Doel website: {$request->input('goal')}\n";
        $messageText .= "Aantal pagina's: {$request->input('pages')}\n";
        $messageText .= "Verwachte bezoekers per maand: {$request->input('visitors')}\n";
        $messageText .= "Ontwerp/huisstijl: {$request->input('design')}\n";
        $messageText .= "Huidige website: " . ($request->input('current_website') ?: 'Niet opgegeven') . "\n";
        $messageText .= "Gewenste functionaliteiten: " . (count($features) ? implode(', ', $features) : 'Geen') . "\n";
        $messageText .= "Content wensen: " . (count($content) ? implode(', ', $content) : 'Zelf aanleveren') . "\n";
        $messageText .= "Gewenste oplevering: {$request->input('timeline')}\n";
        $messageText .= "Budgetindicatie: " . ($request->input('budget') ?: 'Niet opgegeven') . "\n\n";
        $messageText .= "Extra informatie:\n" . ($request->input('notes') ?: '-');

        ContactMessage::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'company' => $request->input('company'),
            'phone' => $request->input('phone'),
            'subject' => 'Offerte aanvraag',
            'message' => $messageText,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'is_spam' => false,
        ]);

        return redirect()->route('quote.builder', ['success' => 1]);
    }
}
