<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\MolliePaymentService;
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

        // Mark related notifications as read when the customer views the invoice.
        Auth::user()->notifications()
            ->unread()
            ->where('link', 'like', '%'.parse_url(route('customer.invoices.show', $invoice), PHP_URL_PATH).'%')
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return view('customer.invoices.show', compact('invoice'));
    }

    public function download(Invoice $invoice, \App\Services\InvoiceDocumentService $documents)
    {
        if ($invoice->user_id !== Auth::id()) {
            abort(403);
        }

        return $documents->pdf($invoice)->download($invoice->invoice_number.'.pdf');
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

        $status = match (true) {
            $invoice->status === 'betaald' => 'success',
            in_array($invoice->status, ['verzonden', 'openstaand', 'in_behandeling'], true) => 'processing',
            default => 'failed',
        };

        return view('payment.return', compact('invoice', 'status'));
    }
}
