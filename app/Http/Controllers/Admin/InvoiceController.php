<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\TransactionLog;
use App\Models\User;
use App\Services\InvoiceService;
use App\Services\MolliePaymentService;
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
        $this->authorize('invoices.drafts.manage');
        $customers = User::customers()->orderBy('name')->get();

        return view('admin.financial.invoices-create', compact('customers'));
    }

    public function store(Request $request, InvoiceService $invoiceService)
    {
        $this->authorize('invoices.drafts.manage');
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
            'lines' => 'required|array|min:1',
            'lines.*.description' => 'required|string|max:255',
            'lines.*.quantity' => 'required|integer|min:1',
            'lines.*.unit_price' => 'required|numeric|min:0',
            'lines.*.vat_percentage' => 'nullable|numeric|min:0|max:100',
            'lines.*.discount_amount' => 'nullable|numeric|min:0',
            'lines.*.period_start' => 'nullable|date',
            'lines.*.period_end' => 'nullable|date|after_or_equal:lines.*.period_start',
            'lines.*.service_reference' => 'nullable|string|max:255',
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

    public function download(Invoice $invoice, \App\Services\InvoiceDocumentService $documents)
    {
        $this->authorize('invoices.download');

        return $documents->pdf($invoice)->download($invoice->invoice_number.'.pdf');
    }

    public function show(Invoice $invoice)
    {
        $this->authorize('invoices.view');
        $invoice->load(['user', 'lines', 'transactions', 'quote']);
        $logs = TransactionLog::where('loggable_type', Invoice::class)
            ->where('loggable_id', $invoice->id)
            ->latest()
            ->get();

        return view('admin.financial.invoices-show', compact('invoice', 'logs'));
    }

    public function edit(Invoice $invoice)
    {
        $this->authorize('invoices.drafts.manage');
        abort_unless($invoice->status === 'concept', 422, 'Alleen conceptfacturen kunnen worden bewerkt. Maak voor een uitgegeven factuur een creditfactuur.');
        $invoice->load('lines');
        $customers = User::customers()->orderBy('name')->get();

        return view('admin.financial.invoices-edit', compact('invoice', 'customers'));
    }

    public function update(Request $request, Invoice $invoice, InvoiceService $invoiceService)
    {
        $this->authorize('invoices.drafts.manage');
        abort_unless($invoice->status === 'concept', 422, 'Alleen conceptfacturen kunnen worden bewerkt.');
        $request->validate([
            'notes' => 'nullable|string',
            'internal_notes' => 'nullable|string',
            'lines' => 'required|array|min:1',
            'lines.*.description' => 'required|string|max:255',
            'lines.*.quantity' => 'required|integer|min:1',
            'lines.*.unit_price' => $invoice->document_type === 'credit' ? 'required|numeric|max:0' : 'required|numeric|min:0',
            'lines.*.vat_percentage' => 'nullable|numeric|min:0|max:100',
            'lines.*.discount_amount' => $invoice->document_type === 'credit' ? 'nullable|numeric|max:0' : 'nullable|numeric|min:0',
            'lines.*.period_start' => 'nullable|date',
            'lines.*.period_end' => 'nullable|date|after_or_equal:lines.*.period_start',
            'lines.*.service_reference' => 'nullable|string|max:255',
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
                'vat_percentage' => $line['vat_percentage'] ?? $invoice->vat_percentage,
                'discount_amount' => $line['discount_amount'] ?? 0,
                'period_start' => $line['period_start'] ?? null,
                'period_end' => $line['period_end'] ?? null,
                'service_reference' => $line['service_reference'] ?? null,
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
        $this->authorize('invoices.drafts.manage');
        abort_unless($invoice->status === 'concept', 422, 'Uitgegeven facturen kunnen niet worden verwijderd. Gebruik een creditfactuur.');
        $invoiceNumber = $invoice->invoice_number;
        $invoice->delete();

        return redirect()->route('admin.financial.invoices')
            ->with('success', "Factuur {$invoiceNumber} is verwijderd.");
    }

    public function updateStatus(Request $request, Invoice $invoice, MolliePaymentService $payments)
    {
        $this->authorize(in_array($request->input('status'), ['betaald', 'in_behandeling'], true) ? 'payments.manage' : 'invoices.send');
        $request->validate([
            'status' => 'required|in:concept,verzonden,openstaand,vervallen,te_laat,betaald,geannuleerd,gecrediteerd,in_behandeling',
        ]);

        $oldStatus = $invoice->status;
        if (in_array($request->status, ['openstaand', 'verzonden'], true) && ! in_array($oldStatus, ['openstaand', 'verzonden', 'betaald'], true)) {
            app(\App\Services\InvoiceDocumentService::class)->issue($invoice);
        }
        $invoice->update([
            'status' => $request->status,
            'paid_at' => $request->status === 'betaald' ? ($invoice->paid_at ?? now()) : $invoice->paid_at,
            'sent_at' => $request->status === 'openstaand' ? ($invoice->sent_at ?? now()) : $invoice->sent_at,
        ]);
        if ($request->status === 'betaald') {
            $payments->finalizePaidInvoice($invoice);
        }

        if (in_array($request->status, ['openstaand', 'verzonden'], true) && ! in_array($oldStatus, ['openstaand', 'verzonden', 'betaald'], true)) {
            app(\App\Services\CustomerNotificationService::class)->invoiceReady($invoice->user, $invoice);
        }

        TransactionLog::create([
            'user_id' => $invoice->user_id,
            'loggable_type' => Invoice::class,
            'loggable_id' => $invoice->id,
            'action' => 'status_gewijzigd',
            'description' => "Factuur {$invoice->invoice_number} status gewijzigd van ".(Invoice::STATUSES[$oldStatus] ?? $oldStatus).' naar '.(Invoice::STATUSES[$request->status] ?? $request->status),
            'performed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Status is bijgewerkt.');
    }

    public function storeNote(Request $request, Invoice $invoice)
    {
        $this->authorize('invoices.view');
        $request->validate(['internal_notes' => 'required|string']);

        $existing = $invoice->internal_notes;
        $newNote = '['.now()->format('d-m-Y H:i').' - '.auth()->user()->name."]\n".$request->internal_notes;
        $invoice->update([
            'internal_notes' => $existing ? $existing."\n\n".$newNote : $newNote,
        ]);

        return back()->with('success', 'Notitie is toegevoegd.');
    }

    public function storePayment(Request $request, Invoice $invoice, MolliePaymentService $payments)
    {
        $this->authorize('payments.manage');
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:bank,ideal,contant,overig',
            'description' => 'nullable|string',
        ]);

        if ((float) $request->amount > $invoice->outstanding_amount) {
            return back()->withErrors(['amount' => 'Het bedrag mag niet hoger zijn dan het openstaande bedrag van €'.number_format($invoice->outstanding_amount, 2, ',', '.').'.']);
        }

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
            $payments->finalizePaidInvoice($invoice);
        }

        TransactionLog::create([
            'user_id' => $invoice->user_id,
            'loggable_type' => Invoice::class,
            'loggable_id' => $invoice->id,
            'action' => 'betaling_toegevoegd',
            'description' => 'Handmatige betaling van €'.number_format($request->amount, 2, ',', '.')." toegevoegd aan factuur {$invoice->invoice_number}",
            'performed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Betaling is toegevoegd.');
    }

    public function markSent(Invoice $invoice)
    {
        $this->authorize('invoices.send');
        app(\App\Services\InvoiceDocumentService::class)->issue($invoice);
        $invoice->update([
            'status' => 'openstaand',
            'sent_at' => now(),
        ]);

        app(\App\Services\CustomerNotificationService::class)->invoiceReady($invoice->user, $invoice);

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

    public function createCredit(Invoice $invoice)
    {
        $this->authorize('invoices.credit.create');
        abort_if($invoice->status === 'concept' || $invoice->document_type === 'credit', 422, 'Deze factuur kan niet worden gecrediteerd.');
        $invoice->load(['lines', 'user']);

        $credit = \Illuminate\Support\Facades\DB::transaction(function () use ($invoice) {
            $credit = Invoice::create([
                'invoice_number' => Invoice::generateCreditNumber(), 'document_type' => 'credit', 'credited_invoice_id' => $invoice->id,
                'user_id' => $invoice->user_id, 'invoice_date' => now(), 'due_date' => now(), 'vat_percentage' => $invoice->vat_percentage,
                'status' => 'concept', 'notes' => "Credit voor {$invoice->invoice_number}",
            ]);
            foreach ($invoice->lines as $i => $line) {
                $credit->lines()->create([
                    'description' => 'Credit: '.$line->description, 'quantity' => $line->quantity, 'unit_price' => -abs((float) $line->unit_price),
                    'total' => -abs((float) $line->total), 'vat_percentage' => $line->vat_percentage, 'discount_amount' => -abs((float) $line->discount_amount),
                    'period_start' => $line->period_start, 'period_end' => $line->period_end, 'service_reference' => $line->service_reference,
                    'customer_service_id' => $line->customer_service_id, 'sort_order' => $i,
                ]);
            }
            $credit->recalculate();
            return $credit;
        });
        \App\Models\AuditLog::record('credit_invoice_created', $credit, null, ['credited_invoice' => $invoice->invoice_number]);

        return redirect()->route('admin.financial.invoices.show', $credit)->with('success', 'Conceptcreditfactuur aangemaakt. Controleer deze voordat je hem uitgeeft.');
    }

    public function markPaid(Invoice $invoice, MolliePaymentService $payments)
    {
        $this->authorize('payments.manage');
        $invoice->update([
            'status' => 'betaald',
            'paid_at' => now(),
        ]);
        $payments->finalizePaidInvoice($invoice);

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
