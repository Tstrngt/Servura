<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Invoice;
use App\Models\InvoiceDesignVersion;
use App\Services\InvoiceDocumentService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InvoiceDesignController extends Controller
{
    public const SCENARIOS = [
        'actual' => 'FAC-2026-0001 (indien beschikbaar)',
        'open' => 'Openstaande factuur', 'paid' => 'Betaalde factuur', 'partial' => 'Gedeeltelijk betaald',
        'multiple' => 'Meerdere diensten', 'discount' => 'Factuur met korting', 'business' => 'Zakelijke klant',
        'multipage' => 'Meerdere pagina’s', 'credit' => 'Creditfactuur',
    ];

    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function edit(InvoiceDocumentService $documents)
    {
        $this->authorize('invoice.settings.edit');
        $draft = InvoiceDesignVersion::draft();
        $versions = InvoiceDesignVersion::where('status', '!=', 'draft')->latest('published_at')->get();
        $exampleInvoice = Invoice::where('invoice_number', 'FAC-2026-0001')->first();

        return view('admin.settings.invoice-design', [
            'draft' => $draft, 'settings' => array_merge($documents->defaultSettings(), $draft->settings),
            'versions' => $versions, 'scenarios' => self::SCENARIOS, 'exampleInvoice' => $exampleInvoice,
        ]);
    }

    public function update(Request $request)
    {
        $this->authorize('invoice.settings.edit');
        $validated = $request->validate([
            'logo_variant' => ['required', Rule::in(['dark', 'light'])],
            'primary_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'accent_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'iban' => ['nullable', 'string', 'max:34'], 'account_holder' => ['nullable', 'string', 'max:255'],
            'footer_text' => ['required', 'string', 'max:500'], 'payment_text' => ['required', 'string', 'max:500'],
        ]);
        $draft = InvoiceDesignVersion::draft();
        $before = $draft->settings;
        $draft->update(['settings' => $validated, 'created_by' => auth()->id()]);
        AuditLog::record('invoice_design_draft_updated', $draft, $before, $validated);

        return back()->with('success', 'Conceptontwerp opgeslagen. Het is nog niet live.');
    }

    public function preview(Request $request, InvoiceDocumentService $documents)
    {
        $this->authorize('invoices.download');
        $scenario = array_key_exists($request->string('scenario')->toString(), self::SCENARIOS) ? $request->string('scenario')->toString() : 'open';
        $invoice = $scenario === 'actual'
            ? Invoice::with(['lines', 'user', 'transactions'])->where('invoice_number', 'FAC-2026-0001')->first()
            : null;
        $invoice ??= $documents->preview($scenario === 'actual' ? 'open' : $scenario);
        $pdf = $documents->pdf($invoice, true);

        return $request->boolean('download')
            ? $pdf->download("factuur-preview-{$scenario}.pdf")
            : $pdf->stream("factuur-preview-{$scenario}.pdf");
    }

    public function publish(InvoiceDocumentService $documents)
    {
        $this->authorize('invoice.design.publish');
        $documents->validateIssuer();
        $draft = InvoiceDesignVersion::draft();
        InvoiceDesignVersion::published()->update(['status' => 'archived']);
        $draft->update(['status' => 'published', 'published_by' => auth()->id(), 'published_at' => now()]);
        AuditLog::record('invoice_design_published', $draft, null, ['version' => $draft->version]);

        return back()->with('success', "Factuurontwerp versie {$draft->version} is gepubliceerd voor nieuw uitgegeven facturen.");
    }

    public function restore(InvoiceDesignVersion $version)
    {
        $this->authorize('invoice.design.publish');
        abort_if($version->status === 'draft', 422);
        InvoiceDesignVersion::published()->update(['status' => 'archived']);
        $restored = InvoiceDesignVersion::create([
            'version' => ((int) InvoiceDesignVersion::max('version')) + 1, 'status' => 'published', 'settings' => $version->settings,
            'created_by' => auth()->id(), 'published_by' => auth()->id(), 'published_at' => now(),
        ]);
        AuditLog::record('invoice_design_restored', $restored, null, ['restored_from' => $version->version]);

        return back()->with('success', "Ontwerp versie {$version->version} is hersteld als versie {$restored->version}.");
    }
}
