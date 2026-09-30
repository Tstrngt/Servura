<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Services\MolliePaymentService;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'customer']);
    }

    /**
     * Show the bank-transfer instructions inline instead of redirecting to Mollie.
     */
    public function bankTransfer(string $paymentId, MolliePaymentService $mollieService)
    {
        $payment = $mollieService->fetchPayment($paymentId);
        $invoice = Invoice::findOrFail($payment->metadata->invoice_id ?? abort(404));

        if ($invoice->user_id !== Auth::id()) {
            abort(403);
        }

        $details = $payment->details ?? null;

        return view('payment.bank-transfer', compact('invoice', 'payment', 'details'));
    }
}
