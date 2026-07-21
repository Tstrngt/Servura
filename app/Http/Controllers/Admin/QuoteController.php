<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Models\Service;
use App\Models\TransactionLog;
use App\Models\User;
use App\Services\QuoteService;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function create()
    {
        $customers = User::customers()->orderBy('name')->get();
        $services = Service::where('is_active', true)->orderBy('title')->get();

        return view('admin.financial.quotes-create', compact('customers', 'services'));
    }

    public function store(Request $request, QuoteService $quoteService)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'lines' => 'required|array|min:1',
            'lines.*.description' => 'required|string',
            'lines.*.quantity' => 'required|integer|min:1',
            'lines.*.unit_price' => 'required|numeric|min:0',
            'lines.*.discount' => 'nullable|numeric|min:0',
            'lines.*.service_id' => 'nullable|exists:services,id',
            'proposal' => 'nullable|string',
            'notes' => 'nullable|string',
            'client_notes' => 'nullable|string',
            'internal_notes' => 'nullable|string',
            'valid_days' => 'nullable|integer|min:1|max:365',
        ]);

        $customer = User::findOrFail($request->user_id);
        $quote = $quoteService->create(
            $customer,
            $request->only(['proposal', 'notes', 'client_notes', 'internal_notes', 'valid_days']),
            $request->lines,
            auth()->id()
        );

        return redirect()
            ->route('admin.financial.quotes.show', $quote)
            ->with('success', "Offerte {$quote->quote_number} is aangemaakt.");
    }

    public function show(Quote $quote)
    {
        $quote->load(['user', 'lines.service', 'convertedInvoice']);
        return view('admin.financial.quotes-show', compact('quote'));
    }

    public function edit(Quote $quote)
    {
        $quote->load('lines.service');
        $customers = User::customers()->orderBy('name')->get();
        $services = Service::where('is_active', true)->orderBy('title')->get();

        return view('admin.financial.quotes-edit', compact('quote', 'customers', 'services'));
    }

    public function update(Request $request, Quote $quote, QuoteService $quoteService)
    {
        $request->validate([
            'lines' => 'required|array|min:1',
            'lines.*.description' => 'required|string',
            'lines.*.quantity' => 'required|integer|min:1',
            'lines.*.unit_price' => 'required|numeric|min:0',
            'lines.*.discount' => 'nullable|numeric|min:0',
            'lines.*.service_id' => 'nullable|exists:services,id',
            'proposal' => 'nullable|string',
            'notes' => 'nullable|string',
            'client_notes' => 'nullable|string',
            'internal_notes' => 'nullable|string',
            'valid_days' => 'nullable|integer|min:1|max:365',
        ]);

        $quoteService->update(
            $quote,
            $request->only(['proposal', 'notes', 'client_notes', 'internal_notes', 'valid_days']),
            $request->lines,
            auth()->id()
        );

        return redirect()
            ->route('admin.financial.quotes.show', $quote)
            ->with('success', 'Offerte is bijgewerkt.');
    }

    public function destroy(Quote $quote)
    {
        $quote->update(['status' => 'verlopen']);

        TransactionLog::create([
            'user_id' => $quote->user_id,
            'loggable_type' => Quote::class,
            'loggable_id' => $quote->id,
            'action' => 'vervallen',
            'description' => "Offerte {$quote->quote_number} verwijderd (vervallen)",
            'performed_by' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.financial.quotes')
            ->with('success', "Offerte {$quote->quote_number} is verwijderd.");
    }

    public function updateStatus(Request $request, Quote $quote, QuoteService $quoteService)
    {
        $request->validate([
            'status' => 'required|in:concept,verzonden,in_afwachting,geaccepteerd,geweigerd,verlopen',
        ]);

        $newStatus = $request->status;

        if ($newStatus === 'verzonden' && !$quote->sent_at) {
            $quoteService->markSent($quote, auth()->id());
        } else {
            $quote->update(['status' => $newStatus]);

            TransactionLog::create([
                'user_id' => $quote->user_id,
                'loggable_type' => Quote::class,
                'loggable_id' => $quote->id,
                'action' => 'status_gewijzigd',
                'description' => "Offerte {$quote->quote_number} status gewijzigd naar " . (Quote::STATUSES[$newStatus] ?? $newStatus),
                'performed_by' => auth()->id(),
            ]);
        }

        return redirect()
            ->route('admin.financial.quotes.show', $quote)
            ->with('success', 'Status is bijgewerkt.');
    }
}
