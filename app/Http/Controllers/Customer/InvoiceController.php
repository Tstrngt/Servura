<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\MolliePaymentService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('customer');
    }

    public function index()
    {
        $invoices = Invoice::where('user_id', Auth::id())
            ->whereIn('status', ['verzonden', 'in_behandeling', 'betaald', 'vervallen'])
            ->latest('invoice_date')
            ->paginate(15);

        return view('customer.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        if ($invoice->user_id !== Auth::id()) {
            abort(403);
        }

        $invoice->load('lines');

        return view('customer.invoices.show', compact('invoice'));
    }

    public function download(Invoice $invoice)
    {
        if ($invoice->user_id !== Auth::id()) {
            abort(403);
        }

        $invoice->load(['lines', 'user']);

        return Pdf::loadView('pdf.invoice', compact('invoice'))
            ->setPaper('a4')
            ->download($invoice->invoice_number.'.pdf');
    }

    public function pay(Invoice $invoice, MolliePaymentService $mollieService)
    {
        if ($invoice->user_id !== Auth::id()) {
            abort(403);
        }

        if ($invoice->status === 'betaald') {
            return back()->with('info', 'Deze factuur is al betaald.');
        }

        if ((float) $invoice->total <= 0) {
            $invoice->update(['status' => 'betaald', 'paid_at' => now()]);
            $mollieService->finalizePaidInvoice($invoice);

            return back()->with('success', 'Deze factuur had geen openstaand bedrag en is afgesloten.');
        }

        try {
            $checkoutUrl = $mollieService->createPayment($invoice);
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with('error', 'De betaalpagina kon niet worden geopend: '.$exception->getMessage());
        }

        return redirect($checkoutUrl);
    }

    public function paymentReturn(Invoice $invoice)
    {
        if ($invoice->user_id !== Auth::id()) {
            abort(403);
        }

        $invoice->refresh();

        if ($invoice->status === 'betaald') {
            return redirect()->route('customer.invoices.show', $invoice)
                ->with('success', 'Betaling succesvol ontvangen!');
        }

        return redirect()->route('customer.invoices.show', $invoice)
            ->with('info', 'Uw betaling wordt verwerkt. Dit kan enkele minuten duren.');
    }
}
