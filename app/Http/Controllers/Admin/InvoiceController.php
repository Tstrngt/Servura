<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\User;
use App\Models\TransactionLog;
use App\Services\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function create()
    {
        $customers = User::customers()->orderBy('name')->get();
        return view('admin.financial.invoices-create', compact('customers'));
    }

    public function store(Request $request, InvoiceService $invoiceService)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
            'lines' => 'required|array|min:1',
            'lines.*.description' => 'required|string|max:255',
            'lines.*.quantity' => 'required|integer|min:1',
            'lines.*.unit_price' => 'required|numeric|min:0',
        ]);

        $invoice = $invoiceService->createManual(
            User::findOrFail($request->user_id),
            $request->lines,
            $request->notes,
            auth()->id()
        );

        return redirect()->route('admin.financial.invoices.show', $invoice)
            ->with('success', "Factuur {$invoice->invoice_number} aangemaakt.");
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['user', 'lines', 'transactions']);
        return view('admin.financial.invoices-show', compact('invoice'));
    }

    public function markSent(Invoice $invoice)
    {
        $invoice->update([
            'status' => 'verzonden',
            'sent_at' => now(),
        ]);

        TransactionLog::create([
            'user_id' => $invoice->user_id,
            'loggable_type' => Invoice::class,
            'loggable_id' => $invoice->id,
            'action' => 'verzonden',
            'description' => "Factuur {$invoice->invoice_number} gemarkeerd als verzonden",
            'performed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Factuur is gemarkeerd als verzonden.');
    }

    public function markPaid(Invoice $invoice)
    {
        $invoice->update([
            'status' => 'betaald',
            'paid_at' => now(),
        ]);

        TransactionLog::create([
            'user_id' => $invoice->user_id,
            'loggable_type' => Invoice::class,
            'loggable_id' => $invoice->id,
            'action' => 'betaald',
            'description' => "Factuur {$invoice->invoice_number} handmatig gemarkeerd als betaald",
            'performed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Factuur is gemarkeerd als betaald.');
    }
}
