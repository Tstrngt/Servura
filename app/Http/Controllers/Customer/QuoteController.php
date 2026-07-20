<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Services\MolliePaymentService;
use App\Services\QuoteService;
use Illuminate\Support\Facades\Auth;

class QuoteController extends Controller
{
    public function index()
    {
        $quotes = Quote::where('user_id', Auth::id())
            ->whereIn('status', ['verzonden', 'geaccepteerd', 'afgewezen'])
            ->latest('quote_date')
            ->get();

        return view('customer.quotes.index', compact('quotes'));
    }

    public function show(Quote $quote)
    {
        if ($quote->user_id !== Auth::id()) {
            abort(403);
        }

        $quote->load('lines.service');
        return view('customer.quotes.show', compact('quote'));
    }

    /**
     * Customer accepts the quote: convert to invoice and redirect to payment.
     */
    public function accept(Quote $quote, QuoteService $quoteService, MolliePaymentService $mollieService)
    {
        if ($quote->user_id !== Auth::id()) {
            abort(403);
        }

        if ($quote->status !== 'verzonden') {
            return redirect()->route('customer.quotes.show', $quote)
                ->with('error', 'Deze offerte kan niet meer geaccepteerd worden.');
        }

        // Accept quote → creates invoice
        $invoice = $quoteService->accept($quote);

        // Create Mollie payment for the invoice
        $paymentUrl = $mollieService->createPayment($invoice);

        if ($paymentUrl) {
            return redirect($paymentUrl);
        }

        return redirect()->route('customer.invoices.show', $invoice)
            ->with('success', 'Offerte geaccepteerd. Factuur is aangemaakt.');
    }

    /**
     * Customer rejects the quote.
     */
    public function reject(Quote $quote, QuoteService $quoteService)
    {
        if ($quote->user_id !== Auth::id()) {
            abort(403);
        }

        if ($quote->status !== 'verzonden') {
            return redirect()->route('customer.quotes.show', $quote)
                ->with('error', 'Deze offerte kan niet meer afgewezen worden.');
        }

        $quoteService->reject($quote);

        return redirect()->route('customer.quotes.index')
            ->with('success', 'Offerte is afgewezen.');
    }
}
