<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Transaction;
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
        $invoice->load(['user', 'lines', 'transactions', 'quote']);
        $logs = TransactionLog::where('loggable_type', Invoice::class)
            ->where('loggable_id', $invoice->id)
            ->latest()
            ->get();
        return view('admin.financial.invoices-show', compact('invoice', 'logs'));
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load('lines');
        $customers = User::customers()->orderBy('name')->get();
        return view('admin.financial.invoices-edit', compact('invoice', 'customers'));
    }

    public function update(Request $request, Invoice $invoice, InvoiceService $invoiceService)
    {
        $request->validate([
            'notes' => 'nullable|string',
            'internal_notes' => 'nullable|string',
            'lines' => 'required|array|min:1',
            'lines.*.description' => 'required|string|max:255',
            'lines.*.quantity' => 'required|integer|min:1',
            'lines.*.unit_price' => 'required|numeric|min:0',
        ]);

        // Delete old lines and re-create
        $invoice->lines()->delete();
        foreach ($request->lines as $i => $line) {
            $total = ($line['quantity'] ?? 1) * $line['unit_price'];
            $invoice->lines()->create([
                'description' => $line['description'],
                'quantity' => $line['quantity'] ?? 1,
                'unit_price' => $line['unit_price'],
                'total' => $total,
                'sort_order' => $i,
            ]);
        }
        $invoice->recalculate();
        $invoice->update([
            'notes' => $request->notes,
            'internal_notes' => $request->internal_notes,
        ]);

        TransactionLog::create([
            'user_id' => $invoice->user_id,
            'loggable_type' => Invoice::class,
            'loggable_id' => $invoice->id,
            'action' => 'bijgewerkt',
            'description' => "Factuur {$invoice->invoice_number} bijgewerkt",
            'performed_by' => auth()->id(),
        ]);

        return redirect()->route('admin.financial.invoices.show', $invoice)
            ->with('success', 'Factuur is bijgewerkt.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoiceNumber = $invoice->invoice_number;
        $invoice->delete();

        return redirect()->route('admin.financial.invoices')
            ->with('success', "Factuur {$invoiceNumber} is verwijderd.");
    }

    public function updateStatus(Request $request, Invoice $invoice)
    {
        $request->validate([
            'status' => 'required|in:concept,openstaand,te_laat,betaald,geannuleerd,gecrediteerd,in_behandeling',
        ]);

        $oldStatus = $invoice->status;
        $invoice->update([
            'status' => $request->status,
            'paid_at' => $request->status === 'betaald' ? ($invoice->paid_at ?? now()) : $invoice->paid_at,
            'sent_at' => $request->status === 'openstaand' ? ($invoice->sent_at ?? now()) : $invoice->sent_at,
        ]);

        TransactionLog::create([
            'user_id' => $invoice->user_id,
            'loggable_type' => Invoice::class,
            'loggable_id' => $invoice->id,
            'action' => 'status_gewijzigd',
            'description' => "Factuur {$invoice->invoice_number} status gewijzigd van " . (Invoice::STATUSES[$oldStatus] ?? $oldStatus) . " naar " . (Invoice::STATUSES[$request->status] ?? $request->status),
            'performed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Status is bijgewerkt.');
    }

    public function storeNote(Request $request, Invoice $invoice)
    {
        $request->validate(['internal_notes' => 'required|string']);

        $existing = $invoice->internal_notes;
        $newNote = '[' . now()->format('d-m-Y H:i') . ' - ' . auth()->user()->name . "]\n" . $request->internal_notes;
        $invoice->update([
            'internal_notes' => $existing ? $existing . "\n\n" . $newNote : $newNote,
        ]);

        return back()->with('success', 'Notitie is toegevoegd.');
    }

    public function storePayment(Request $request, Invoice $invoice)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:bank,ideal,contant,overig',
            'description' => 'nullable|string',
        ]);

        Transaction::create([
            'transaction_number' => Transaction::generateNumber(),
            'user_id' => $invoice->user_id,
            'invoice_id' => $invoice->id,
            'amount' => $request->amount,
            'type' => 'inkomst',
            'payment_method' => $request->payment_method,
            'status' => 'voltooid',
            'description' => $request->description ?? "Betaling factuur {$invoice->invoice_number}",
            'transaction_date' => now(),
        ]);

        // Auto-mark as paid if total payments >= invoice total
        $totalPaid = $invoice->transactions()->where('status', 'voltooid')->sum('amount');
        if ($totalPaid >= $invoice->total) {
            $invoice->update(['status' => 'betaald', 'paid_at' => now()]);
        }

        TransactionLog::create([
            'user_id' => $invoice->user_id,
            'loggable_type' => Invoice::class,
            'loggable_id' => $invoice->id,
            'action' => 'betaling_toegevoegd',
            'description' => "Handmatige betaling van €" . number_format($request->amount, 2, ',', '.') . " toegevoegd aan factuur {$invoice->invoice_number}",
            'performed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Betaling is toegevoegd.');
    }

    public function markSent(Invoice $invoice)
    {
        $invoice->update([
            'status' => 'openstaand',
            'sent_at' => now(),
        ]);

        TransactionLog::create([
            'user_id' => $invoice->user_id,
            'loggable_type' => Invoice::class,
            'loggable_id' => $invoice->id,
            'action' => 'verzonden',
            'description' => "Factuur {$invoice->invoice_number} gemarkeerd als openstaand",
            'performed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Factuur is gemarkeerd als openstaand.');
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
