<?php

namespace App\Http\Controllers;

use App\Models\CustomerService;
use App\Models\DomainRegistration;
use App\Models\DomainTld;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Service;
use App\Models\ServicePrice;
use App\Models\User;
use App\Services\CheckoutCart;
use App\Services\Domains\DomainProviderFactory;
use App\Services\InvoiceService;
use App\Services\MolliePaymentService;
use App\Services\TaxService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function loginPrompt(Service $service)
    {
        session(['url.intended' => route('checkout.show', $service)]);

        return redirect()->route('login')
            ->with('success', 'Log in om verder te gaan met uw bestelling.');
    }

    public function show(Service $service, TaxService $taxService, Request $request)
    {
        abort_unless($service->is_active, 404);
        abort_if(Auth::check() && Auth::user()->canAccessAdmin(), 403);

        $countries = TaxService::COUNTRIES;
        $countryRates = $taxService->ratesForCheckout();

        $prefill = $request->only(['domain', 'tld', 'price']);
        if ($service->fulfillment_type === 'domain' && $request->filled('domain')) {
            $prefill['domain'] = $request->input('domain');
            $prefill['tld'] = $request->input('tld');
            $prefill['price'] = $request->input('price');
        }

        $cart = CheckoutCart::initialize($service, $prefill, $request);
        $resolved = $this->resolveCart($cart);
        $hostingServices = Service::where('fulfillment_type', 'directadmin')
            ->where('is_active', true)
            ->ordered()
            ->get();

        return view('checkout', compact('service', 'countries', 'countryRates', 'cart', 'resolved', 'hostingServices'));
    }

    public function addHosting(Request $request)
    {
        $validated = $request->validate([
            'service_slug' => 'required|exists:services,slug',
            'service_price_id' => 'nullable|exists:service_prices,id',
        ]);

        $service = Service::where('slug', $validated['service_slug'])->firstOrFail();
        $price = $validated['service_price_id'] ? ServicePrice::find($validated['service_price_id']) : $service->prices->first();

        CheckoutCart::addService($service, $price, $request);

        return back();
    }

    public function removeItem(Request $request)
    {
        $validated = $request->validate(['index' => 'required|integer']);
        CheckoutCart::removeItem($validated['index'], $request);

        return back();
    }

    public function setDomain(Request $request)
    {
        $validated = $request->validate([
            'index' => 'required|integer',
            'domain_mode' => 'required|in:register,existing,transfer',
            'domain' => 'required_unless:domain_mode,register|string|max:253|nullable',
            'auth_code' => 'nullable|string|max:255',
        ]);

        CheckoutCart::updateDomainForHostingItem(
            $validated['index'],
            $validated['domain_mode'],
            $validated['domain'] ?? null,
            $validated['auth_code'] ?? null,
            $request
        );

        return back();
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

        $cart = $this->buildCartFromRequest($service, $request);
        $resolved = $this->resolveCart($cart);

        $this->checkExistingCustomer($request);
        $this->validateOrder($request, $resolved);

        if (! empty($resolved['errors'])) {
            throw ValidationException::withMessages($resolved['errors']);
        }

        $validated = $request->all();
        $tax = $taxService->calculate((float) $resolved['subtotal'], $request->input('country'));

        [$order, $user, $isNewUser] = DB::transaction(function () use ($validated, $cart, $resolved, $tax, $invoiceService) {
            $user = Auth::user();
            $userData = collect($validated)->only([
                'name', 'company', 'phone', 'street', 'house_number', 'postal_code', 'city',
                'country', 'kvk_number', 'vat_number',
            ])->all();

            $isNewUser = false;
            if (! $user) {
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

            $customerServices = [];
            $domainItemForHosting = null;

            foreach ($resolved['items'] as $i => $item) {
                $customerService = $this->createCustomerService($user, $item);
                $customerServices[] = $customerService;

                if ($item['service']->fulfillment_type === 'domain') {
                    $domainItemForHosting = $customerService;
                }

                // If a hosting package includes a new domain registration or transfer,
                // create a separate customer service for the domain operation.
                if (
                    $item['service']->fulfillment_type === 'directadmin'
                    && in_array($item['domain_mode'] ?? null, ['register', 'transfer'], true)
                    && $item['domain']
                    && ! empty($item['domain_price_model'])
                ) {
                    $domainCustomerService = $this->createDomainCustomerService(
                        $user,
                        $item['domain'],
                        $item['tld'] ?? $this->extractTld($item['domain']),
                        $item['domain_mode'],
                        $item['domain_price_model'],
                        $item['auth_code'] ?? null
                    );
                    $customerServices[] = $domainCustomerService;

                    if (! $domainItemForHosting) {
                        $domainItemForHosting = $domainCustomerService;
                    }
                }
            }

            // Link any hosting service to the domain registered/transferred in the same order.
            foreach ($customerServices as $customerService) {
                if (
                    $customerService->service->fulfillment_type === 'directadmin'
                    && $domainItemForHosting
                    && ! $customerService->domain
                ) {
                    $customerService->update(['domain' => $domainItemForHosting->domain]);
                }
            }

            $invoice = $invoiceService->createForCustomerServices($user, $customerServices, $user->id, $tax['rate']);

            $subtotal = (float) $resolved['subtotal'];
            $primaryCustomerService = $customerServices[0];
            $order = Order::create([
                'order_number' => Order::generateNumber(),
                'user_id' => $user->id,
                'service_id' => $primaryCustomerService->service_id,
                'service_price_id' => $primaryCustomerService->service_price_id,
                'customer_service_id' => $primaryCustomerService->id,
                'invoice_id' => $invoice->id,
                'billing_country' => $validated['country'],
                'billing_cycle' => $primaryCustomerService->billing_cycle,
                'fulfillment_type' => $primaryCustomerService->service->fulfillment_type,
                'vat_percentage' => $tax['rate'],
                'subtotal' => $subtotal,
                'vat_amount' => $tax['amount'],
                'total' => $tax['total'],
                'status' => 'pending_payment',
                'terms_accepted_at' => now(),
                'terms_version' => config('legal.versions.terms.version'),
                'hosting_terms_version' => $this->requiresHostingTerms($customerServices)
                    ? config('legal.versions.hosting_terms.version')
                    : null,
            ]);

            foreach ($customerServices as $i => $customerService) {
                OrderLine::create([
                    'order_id' => $order->id,
                    'service_id' => $customerService->service_id,
                    'service_price_id' => $customerService->service_price_id,
                    'customer_service_id' => $customerService->id,
                    'description' => $this->orderLineDescription($customerService),
                    'quantity' => 1,
                    'unit_price' => $customerService->price,
                    'total' => $customerService->price,
                    'sort_order' => $i,
                ]);
            }

            return [$order, $user, $isNewUser];
        });

        CheckoutCart::clear($request);

        $notification = app(\App\Services\CustomerNotificationService::class);
        if ($isNewUser) {
            $notification->accountCreated($user);
        }
        $notification->orderPlaced($user, $order);

        if (! Auth::check()) {
            Auth::login($user);
            $request->session()->regenerate();
        }

        $establishMandate = $request->input('payment_method') === 'auto_debit' && ($order->billing_cycle ?? null) !== 'one_time';

        try {
            return redirect($molliePaymentService->createPayment($order->invoice, $establishMandate));
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()->route('customer.invoices.show', $order->invoice)
                ->with('error', 'De bestelling is opgeslagen, maar de betaalpagina kon niet worden geopend. Probeer de betaling opnieuw vanuit de factuur.');
        }
    }

    private function buildCartFromRequest(Service $service, Request $request): array
    {
        $cart = CheckoutCart::get($request);

        if (! empty($cart)) {
            return $cart;
        }

        // Fallback for direct form posts / legacy tests.
        $item = [
            'service_id' => $service->id,
            'service_price_id' => $request->input('service_price_id'),
            'mode' => $service->fulfillment_type === 'domain' ? 'register' : 'none',
        ];

        if ($service->fulfillment_type === 'domain' && $request->filled('domain_name')) {
            $item['domain'] = strtolower($request->input('domain_name')).strtolower($request->input('domain_tld', ''));
            $item['tld'] = $request->input('domain_tld');
        }

        if ($service->fulfillment_type === 'directadmin') {
            $item['domain_mode'] = in_array($request->input('domain_mode'), ['register', 'transfer', 'existing'], true)
                ? $request->input('domain_mode')
                : 'existing';
            $item['domain'] = $request->input('domain');
            $item['auth_code'] = $request->input('auth_code');
        }

        return [$item];
    }

    private function resolveCart(array $cart): array
    {
        $items = [];
        $errors = [];
        $subtotal = 0;

        foreach ($cart as $index => $item) {
            $service = Service::find($item['service_id'] ?? null);
            if (! $service) {
                continue;
            }

            $resolvedItem = [
                'index' => $index,
                'service' => $service,
                'mode' => $item['mode'] ?? 'none',
                'domain_mode' => $item['domain_mode'] ?? null,
                'domain' => $item['domain'] ?? null,
                'auth_code' => $item['auth_code'] ?? null,
                'price' => 0,
                'price_model' => null,
                'tld' => $item['tld'] ?? null,
            ];

            if ($service->fulfillment_type === 'domain') {
                $tld = $resolvedItem['tld'] ?? $this->extractTld($item['domain'] ?? '');
                $cycle = ($item['mode'] ?? 'register') === 'transfer' ? 'one_time' : 'yearly';
                $priceModel = ServicePrice::where('service_id', $service->id)
                    ->where('tld', $tld)
                    ->where('billing_cycle', $cycle)
                    ->where('is_enabled', true)
                    ->first();

                if (! $priceModel) {
                    $errors["cart.{$index}"] = 'De gekozen extensie en tarief zijn niet beschikbaar.';
                } else {
                    $resolvedItem['price_model'] = $priceModel;
                    $resolvedItem['price'] = (float) $priceModel->price;
                    $resolvedItem['tld'] = $tld;
                    $subtotal += $resolvedItem['price'];
                }
            } else {
                $priceModel = $item['service_price_id']
                    ? $service->prices->firstWhere('id', $item['service_price_id'])
                    : $service->prices->firstWhere('is_enabled', true);

                if ($priceModel) {
                    $resolvedItem['price_model'] = $priceModel;
                    $resolvedItem['price'] = (float) $priceModel->price;
                    $subtotal += $resolvedItem['price'];
                } else {
                    $errors["cart.{$index}"] = 'Het gekozen tarief is niet beschikbaar.';
                }

                // Domain attached to a hosting package.
                if (! empty($item['domain_mode']) && in_array($item['domain_mode'], ['register', 'transfer'], true)) {
                    $domainService = Service::where('fulfillment_type', 'domain')->first();
                    $tld = '.'.$this->extractTld($item['domain'] ?? '');
                    $cycle = $item['domain_mode'] === 'transfer' ? 'one_time' : 'yearly';
                    $domainPriceModel = $domainService
                        ? ServicePrice::where('service_id', $domainService->id)
                            ->where('tld', $tld)
                            ->where('billing_cycle', $cycle)
                            ->where('is_enabled', true)
                            ->first()
                        : null;

                    if ($domainPriceModel) {
                        $resolvedItem['domain_price_model'] = $domainPriceModel;
                        $resolvedItem['domain_price'] = (float) $domainPriceModel->price;
                        $resolvedItem['tld'] = $tld;
                        $subtotal += $resolvedItem['domain_price'];
                    }
                }
            }

            $items[] = $resolvedItem;
        }

        return [
            'items' => $items,
            'errors' => $errors,
            'subtotal' => $subtotal,
        ];
    }

    private function validateOrder(Request $request, array $resolved): void
    {
        $rules = [
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

        if (! Auth::check()) {
            $rules['email'] = 'required|email|max:255|unique:users,email';
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $validated = $request->validate($rules);

        $errors = [];
        foreach ($resolved['items'] as $index => $item) {
            $domain = $item['domain'] ?? null;

            if ($item['service']->fulfillment_type === 'domain' && ! $this->validDomain($domain)) {
                $errors["cart.{$index}"] = 'Voer een geldige domeinnaam in.';
            }

            if ($item['service']->fulfillment_type === 'directadmin') {
                $mode = $item['domain_mode'] ?? 'existing';

                if ($mode === 'existing' && ! $this->validDomain($domain)) {
                    $errors["cart.{$index}.domain"] = 'Voer een geldig bestaand domein in.';
                }

                if (($mode === 'register' || $mode === 'transfer') && ! $this->validDomain($domain)) {
                    $errors["cart.{$index}.domain"] = 'Voer een geldig domein in.';
                }

                if ($mode === 'transfer' && blank($item['auth_code'])) {
                    $errors["cart.{$index}.auth_code"] = 'Een verhuiscode is verplicht voor een domeinverhuizing.';
                }
            }

            // Server-side availability and price check for registrations / transfers.
            if ($item['service']->fulfillment_type === 'domain' && $domain) {
                $tld = $item['tld'];
                $domainTld = DomainTld::where('extension', $tld)->where('is_active', true)->first();

                if (! $domainTld) {
                    $errors["cart.{$index}"] = 'De gekozen extensie is niet actief.';
                    continue;
                }

                $expectedPrice = $item['mode'] === 'transfer'
                    ? (float) $domainTld->transfer_price
                    : (float) $domainTld->registration_price;

                if (abs($expectedPrice - $item['price']) > 0.01) {
                    $errors["cart.{$index}"] = 'De prijs is inmiddels gewijzigd. Vernieuw de pagina.';
                }
            }
        }

        if (! empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }

    private function validDomain(?string $domain): bool
    {
        if (! $domain) {
            return false;
        }

        return (bool) preg_match('/^(?!-)(?:[a-z0-9-]{1,63}\.)+[a-z]{2,63}$/i', $domain);
    }

    private function createCustomerService(User $user, array $item): CustomerService
    {
        $service = $item['service'];
        $priceModel = $item['price_model'];
        $domain = $item['domain'] ?? null;

        $customerService = CustomerService::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'service_price_id' => $priceModel?->id,
            'domain' => $domain,
            'provisioning_status' => in_array($service->fulfillment_type, ['directadmin', 'domain'], true) ? 'pending_payment' : 'not_required',
            'status' => 'suspended',
            'suspension_reason' => 'pending_payment',
            'price' => $item['price'],
            'price_type' => $priceModel?->billing_cycle ?? $service->price_type,
            'billing_cycle' => $priceModel?->billing_cycle ?? $service->price_type,
            'start_date' => now(),
            'auto_renew' => ($priceModel?->billing_cycle ?? $service->price_type) !== 'one_time',
            'payment_method' => request('payment_method', 'payment_link'),
            'notes' => 'Aangemaakt via online bestelling; wacht op betaling.',
        ]);

        if ($service->fulfillment_type === 'domain') {
            DomainRegistration::create([
                'user_id' => $user->id,
                'customer_service_id' => $customerService->id,
                'type' => $item['mode'] === 'transfer' ? DomainRegistration::TYPE_TRANSFER : DomainRegistration::TYPE_REGISTRATION,
                'domain_name' => $domain,
                'tld' => $item['tld'],
                'status' => $item['mode'] === 'transfer'
                    ? DomainRegistration::STATUS_TRANSFER_PENDING
                    : DomainRegistration::STATUS_AWAITING_PAYMENT,
                'provider' => 'transip',
                'registration_price' => $item['mode'] === 'register' ? $item['price'] : null,
                'transfer_price' => $item['mode'] === 'transfer' ? $item['price'] : null,
                'renewal_price' => $this->resolveRenewalPrice($domain),
                'auto_renew' => true,
                'auth_code' => $item['auth_code'] ?? null,
            ]);
        }

        return $customerService;
    }

    private function createDomainCustomerService(User $user, string $domain, string $tld, string $mode, ServicePrice $priceModel, ?string $authCode = null): CustomerService
    {
        $domainService = Service::where('fulfillment_type', 'domain')->firstOrFail();

        $customerService = CustomerService::create([
            'user_id' => $user->id,
            'service_id' => $domainService->id,
            'service_price_id' => $priceModel->id,
            'domain' => $domain,
            'provisioning_status' => 'pending_payment',
            'status' => 'suspended',
            'suspension_reason' => 'pending_payment',
            'price' => (float) $priceModel->price,
            'price_type' => $priceModel->billing_cycle,
            'billing_cycle' => $priceModel->billing_cycle,
            'start_date' => now(),
            'auto_renew' => $priceModel->billing_cycle !== 'one_time',
            'payment_method' => request('payment_method', 'payment_link'),
            'notes' => 'Aangemaakt via online bestelling; wacht op betaling.',
        ]);

        DomainRegistration::create([
            'user_id' => $user->id,
            'customer_service_id' => $customerService->id,
            'type' => $mode === 'transfer' ? DomainRegistration::TYPE_TRANSFER : DomainRegistration::TYPE_REGISTRATION,
            'domain_name' => $domain,
            'tld' => $tld,
            'status' => $mode === 'transfer'
                ? DomainRegistration::STATUS_TRANSFER_PENDING
                : DomainRegistration::STATUS_AWAITING_PAYMENT,
            'provider' => 'transip',
            'registration_price' => $mode === 'register' ? $customerService->price : null,
            'transfer_price' => $mode === 'transfer' ? $customerService->price : null,
            'renewal_price' => $this->resolveRenewalPrice($domain),
            'auto_renew' => true,
            'auth_code' => $authCode,
        ]);

        return $customerService;
    }

    private function checkExistingCustomer(Request $request): void
    {
        if (Auth::check()) {
            return;
        }

        $email = $request->input('email');
        if (! $email) {
            return;
        }

        $existingUser = User::where('email', $email)->first();

        if ($existingUser && $existingUser->isCustomer()) {
            $request->session()->put('url.intended', $request->fullUrl());

            abort(
                redirect()->route('login')
                    ->with('success', 'U heeft al een account. Log in om verder te gaan met uw bestelling.')
            );
        }

        if ($existingUser && ! $existingUser->isCustomer()) {
            throw ValidationException::withMessages(['email' => 'Dit e-mailadres is gekoppeld aan een intern account. Gebruik een ander e-mailadres.']);
        }
    }

    private function resolveRenewalPrice(?string $domain): ?float
    {
        if (! $domain) {
            return null;
        }

        $parts = explode('.', $domain, 2);
        $tld = $parts[1] ?? null;
        if (! $tld) {
            return null;
        }

        $domainTld = DomainTld::where('extension', '.'.$tld)->first();

        return $domainTld ? (float) $domainTld->renewal_price : null;
    }

    private function requiresHostingTerms(array $customerServices): bool
    {
        foreach ($customerServices as $customerService) {
            if ($customerService->service->fulfillment_type === 'directadmin') {
                return true;
            }
        }

        return false;
    }

    private function orderLineDescription(CustomerService $customerService): string
    {
        $description = $customerService->service->title;
        if ($customerService->domain) {
            $description .= ' - '.$customerService->domain;
        }

        return $description;
    }

    private function extractTld(?string $domain): ?string
    {
        if (! $domain) {
            return null;
        }

        $parts = explode('.', $domain, 2);

        return $parts[1] ?? null;
    }
}
