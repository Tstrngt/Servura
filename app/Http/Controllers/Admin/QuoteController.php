<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Models\Service;
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
            'lines.*.service_id' => 'nullable|exists:services,id',
            'notes' => 'nullable|string',
            'valid_days' => 'nullable|integer|min:1|max:90',
        ]);

        $customer = User::findOrFail($request->user_id);
        $quote = $quoteService->create(
            $customer,
            $request->lines,
            $request->notes,
            $request->valid_days ?? 30,
            auth()->id()
        );

        return redirect()
            ->route('admin.financial.quotes.show', $quote)
            ->with('success', "Offerte {$quote->quote_number} is aangemaakt.");
    }

    public function show(Quote $quote)
    {
        $quote->load(['user', 'lines.service']);
        return view('admin.financial.quotes-show', compact('quote'));
    }

    public function markSent(Quote $quote, QuoteService $quoteService)
    {
        $quoteService->markSent($quote, auth()->id());

        return redirect()
            ->route('admin.financial.quotes.show', $quote)
            ->with('success', 'Offerte is verzonden.');
    }
}
