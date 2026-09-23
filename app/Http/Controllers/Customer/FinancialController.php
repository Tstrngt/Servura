<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\PaymentBatch;
use App\Models\Quote;
use App\Models\Transaction;
use App\Services\MolliePaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinancialController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'customer']);
    }

    public function index(Request $request)
    {
        $userId = Auth::id();
        $tab = $request->string('tab', 'open')->toString();
        $openInvoices = Invoice::where('user_id', $userId)
            ->whereIn('status', ['verzonden', 'openstaand', 'vervallen'])
            ->latest('invoice_date')
            ->get();
        $paidInvoices = Invoice::where('user_id', $userId)
            ->where('status', 'betaald')
            ->latest('paid_at')
            ->paginate(10, ['*'], 'invoices_page');
        $quotes = Quote::where('user_id', $userId)
            ->whereIn('status', ['verzonden', 'geaccepteerd', 'afgewezen'])
            ->latest('quote_date')
            ->paginate(10, ['*'], 'quotes_page');
        $transactions = Transaction::where('user_id', $userId)
            ->where('type', 'inkomst')
            ->latest('transaction_date')
            ->paginate(10, ['*'], 'transactions_page');

        return view('customer.financial.index', compact(
            'tab',
            'openInvoices',
            'paidInvoices',
            'quotes',
            'transactions'
        ));
    }

    public function payBatch(Request $request, MolliePaymentService $paymentService)
    {
        $validated = $request->validate([
            'invoice_ids' => ['required', 'array', 'min:1'],
            'invoice_ids.*' => ['required', 'integer', 'distinct'],
        ], [
            'invoice_ids.required' => 'Selecteer minimaal één factuur.',
            'invoice_ids.min' => 'Selecteer minimaal één factuur.',
        ]);

        try {
            $batch = $paymentService->createBatchPayment(Auth::user(), $validated['invoice_ids']);
        } catch (\InvalidArgumentException $exception) {
            return back()->with('error', $exception->getMessage());
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with('error', 'De betaalpagina kon niet worden geopend. Probeer het later opnieuw.');
        }

        return redirect($batch->checkout_url);
    }

    public function paymentReturn(PaymentBatch $paymentBatch)
    {
        abort_unless($paymentBatch->user_id === Auth::id(), 403);
        $paymentBatch->refresh();

        if ($paymentBatch->status === 'paid') {
            return redirect()->route('customer.financial.index', ['tab' => 'paid'])
                ->with('success', 'De geselecteerde facturen zijn betaald.');
        }

        return redirect()->route('customer.financial.index')
            ->with('info', 'De betaling wordt verwerkt. Dit kan enkele minuten duren.');
    }
}
