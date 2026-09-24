<?php

namespace App\Http\Controllers;

use App\Models\CustomerService;
use App\Models\Order;
use App\Models\Service;
use App\Models\ServicePrice;
use App\Models\User;
use App\Services\InvoiceService;
use App\Services\MolliePaymentService;
use App\Services\TaxService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public function show(Service $service, TaxService $taxService)
    {
        abort_unless($service->is_active, 404);
        abort_if(Auth::check() && Auth::user()->canAccessAdmin(), 403);

        $service->load(['category', 'prices' => fn ($query) => $query->where('is_enabled', true)]);
        abort_if($service->prices->isEmpty(), 404);

        $countries = TaxService::COUNTRIES;
        $countryRates = $taxService->ratesForCheckout();

        return view('checkout', compact('service', 'countries', 'countryRates'));
    }

    public function store(
        Request $request,
        Service $service,
        InvoiceService $invoiceService,
        MolliePaymentService $molliePaymentService,
        TaxService $taxService
    ) {
        abort_unless($service->is_active, 404);
        abort_if(Auth::check() && Auth::user()->canAccessAdmin(), 403);

        $rules = [
            'service_price_id' => [
                'required',
                Rule::exists('service_prices', 'id')->where(fn ($query) => $query
                    ->where('service_id', $service->id)
                    ->where('is_enabled', true)),
            ],
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'street' => 'required|string|max:255',
            'house_number' => 'required|string|max:30',
            'postal_code' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'country' => ['required', 'string', 'size:2', Rule::in(array_keys(TaxService::COUNTRIES))],
            'kvk_number' => 'nullable|string|max:30',
            'vat_number' => 'nullable|string|max:30',
            'payment_method' => 'required|in:auto_debit,payment_link',
            'terms' => 'accepted',
        ];

        if ($service->fulfillment_type === 'directadmin') {
            $rules['domain'] = ['required', 'string', 'max:253', 'regex:/^(?!-)(?:[a-z0-9-]{1,63}\.)+[a-z]{2,63}$/i'];
        }

        if (!Auth::check()) {
            $rules['email'] = 'required|email|max:255|unique:users,email';
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $validated = $request->validate($rules);
        $price = ServicePrice::whereKey($validated['service_price_id'])
            ->where('service_id', $service->id)
            ->where('is_enabled', true)
            ->firstOrFail();
        $tax = $taxService->calculate((float) $price->price, $validated['country']);

        [$order, $user, $isNewUser] = DB::transaction(function () use ($validated, $service, $price, $tax, $invoiceService) {
            $user = Auth::user();
            $userData = collect($validated)->only([
                'name', 'company', 'phone', 'street', 'house_number', 'postal_code', 'city',
                'country', 'kvk_number', 'vat_number',
            ])->all();

            $isNewUser = false;
            if (!$user) {
                $user = User::create($userData + [
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'role' => 'customer',
                    'is_active' => true,
                    'email_verification_token' => \Illuminate\Support\Str::random(48),
                ]);
                $isNewUser = true;
            } else {
                $user->update($userData);
            }

            $customerService = CustomerService::create([
                'user_id' => $user->id,
                'service_id' => $service->id,
                'service_price_id' => $price->id,
                'domain' => isset($validated['domain']) ? strtolower($validated['domain']) : null,
                'provisioning_status' => $service->fulfillment_type === 'directadmin' ? 'pending_payment' : 'not_required',
                'status' => 'suspended',
                'suspension_reason' => 'pending_payment',
                'price' => $price->price,
                'price_type' => $price->billing_cycle,
                'billing_cycle' => $price->billing_cycle,
                'start_date' => now(),
                'auto_renew' => $price->billing_cycle !== 'one_time',
                'payment_method' => $validated['payment_method'],
                'notes' => 'Aangemaakt via online bestelling; wacht op betaling.',
            ]);

            $invoice = $invoiceService->createFromCustomerService($customerService, $user->id, $tax['rate']);
            $subtotal = (float) $price->price;
            $vatAmount = $tax['amount'];
            $order = Order::create([
                'order_number' => Order::generateNumber(),
                'user_id' => $user->id,
                'service_id' => $service->id,
                'service_price_id' => $price->id,
                'customer_service_id' => $customerService->id,
                'invoice_id' => $invoice->id,
                'billing_cycle' => $price->billing_cycle,
                'fulfillment_type' => $service->fulfillment_type,
                'subtotal' => $subtotal,
                'vat_percentage' => $tax['rate'],
                'billing_country' => $validated['country'],
                'vat_amount' => $vatAmount,
                'total' => $tax['total'],
                'status' => 'pending_payment',
            ]);

            return [$order, $user, $isNewUser];
        });

        $notification = app(\App\Services\CustomerNotificationService::class);
        if ($isNewUser) {
            $notification->accountCreated($user, $validated['password']);
        }
        $notification->orderPlaced($user, $order);

        if (!Auth::check()) {
            Auth::login($user);
            $request->session()->regenerate();
        }

        try {
            return redirect($molliePaymentService->createPayment(
                $order->invoice,
                $validated['payment_method'] === 'auto_debit' && $price->billing_cycle !== 'one_time'
            ));
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()->route('customer.invoices.show', $order->invoice)
                ->with('error', 'De bestelling is opgeslagen, maar de betaalpagina kon niet worden geopend. Probeer de betaling opnieuw vanuit de factuur.');
        }
    }
}
