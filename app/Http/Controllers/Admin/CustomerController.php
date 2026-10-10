<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\CustomerService;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\ServicePrice;
use App\Models\Ticket;
use App\Services\CustomerNotificationService;
use App\Services\DirectAdminClient;
use App\Services\RenewalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of customers.
     */
    public function index(Request $request)
    {
        $query = User::customers()->with(['customerServices.service']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $customers = $query->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request)
    {
        $request->merge(['is_active' => $request->boolean('is_active')]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'house_number' => 'nullable|string|max:20',
            'postal_code' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'kvk_number' => 'nullable|string|max:20',
            'vat_number' => 'nullable|string|max:30',
            'password' => 'required|string|min:8|confirmed',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Naam is verplicht',
            'email.required' => 'E-mailadres is verplicht',
            'email.email' => 'Voer een geldig e-mailadres in',
            'email.unique' => 'Dit e-mailadres is al in gebruik',
            'password.required' => 'Wachtwoord is verplicht',
            'password.min' => 'Wachtwoord moet minimaal 8 tekens bevatten',
            'password.confirmed' => 'Wachtwoord bevestiging komt niet overeen',
        ]);

        $validated['role'] = 'customer';
        $validated['password'] = Hash::make($validated['password']);

        $customer = User::create($validated);
        $sent = app(CustomerNotificationService::class)->accountCreated($customer);
        AuditLog::record('customer.created', $customer, null, ['email' => $customer->email, 'welcome_email_sent' => $sent]);

        return redirect()
            ->route('admin.customers.show', $customer)
            ->with($sent ? 'success' : 'warning', $sent
                ? 'Klant is succesvol aangemaakt en de welkomstmail is verzonden.'
                : 'Klant is aangemaakt, maar de welkomstmail kon niet worden verzonden. Bekijk het e-maillogboek.');
    }

    /**
     * Display the specified customer.
     */
    public function show(User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(404);
        }

        $customer->load([
            'customerServices.service',
            'customerServices.invoices',
            'tickets' => function ($query) {
                $query->latest()->limit(10);
            }
        ]);

        // Get statistics
        $stats = [
            'total_services' => $customer->customerServices()->count(),
            'active_services' => $customer->activeServices()->count(),
            'total_tickets' => $customer->tickets()->count(),
            'open_tickets' => $customer->openTickets()->count(),
            'total_invoices' => Invoice::where('user_id', $customer->id)->count(),
            'monthly_cost' => $customer->activeServices()
                ->where('price_type', 'maandelijks')
                ->sum('price'),
            'yearly_cost' => $customer->activeServices()
                ->where('price_type', 'jaarlijks')
                ->sum('price'),
        ];

        // Data for services tab
        $availableServices = Service::where('is_active', true)->orderBy('title')->get();

        // Data for invoices tab
        $invoices = Invoice::where('user_id', $customer->id)->latest('invoice_date')->get();

        $billingCycles = ServicePrice::CYCLES;
        $emailLogs = $customer->emailLogs()->with('sender')->latest()->limit(100)->get();

        return view('admin.customers.show', compact('customer', 'stats', 'availableServices', 'invoices', 'billingCycles', 'emailLogs'));
    }

    public function sendEmail(Request $request, User $customer, CustomerNotificationService $notifications)
    {
        abort_unless($customer->isCustomer(), 404);
        abort_unless($request->user()->can('customer-emails.send'), 403);

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:20000',
        ]);

        $sent = $notifications->custom($customer, $validated['subject'], $validated['message']);
        AuditLog::record('customer.email.sent', $customer, null, [
            'subject' => $validated['subject'],
            'status' => $sent ? 'sent' : 'failed',
        ]);

        return redirect()
            ->route('admin.customers.show', [$customer, 'tab' => 'emails'])
            ->with($sent ? 'success' : 'error', $sent
                ? 'E-mail is succesvol verzonden.'
                : 'E-mail kon niet worden verzonden. Bekijk de foutmelding in het e-maillogboek.');
    }

    public function sendStandardEmail(Request $request, User $customer, CustomerNotificationService $notifications)
    {
        abort_unless($customer->isCustomer(), 404);
        abort_unless($request->user()->can('customer-emails.send'), 403);

        $validated = $request->validate([
            'template' => ['required', Rule::in(['account-created', 'verify-email', 'password-reset'])],
        ]);

        if ($validated['template'] === 'verify-email' && $customer->email_verified_at) {
            return back()->with('error', 'Het e-mailadres van deze klant is al bevestigd.');
        }

        $sent = match ($validated['template']) {
            'account-created' => $notifications->accountCreated($customer),
            'verify-email' => $notifications->sendEmailVerification($customer),
            'password-reset' => $notifications->passwordReset($customer),
        };

        AuditLog::record('customer.standard_email.sent', $customer, null, [
            'template' => $validated['template'],
            'status' => $sent ? 'sent' : 'failed',
        ]);

        return redirect()
            ->route('admin.customers.show', [$customer, 'tab' => 'emails'])
            ->with($sent ? 'success' : 'error', $sent
                ? 'Standaard e-mail is succesvol verzonden.'
                : 'E-mail kon niet worden verzonden. Bekijk de foutmelding in het e-maillogboek.');
    }

    public function resendWelcomeEmail(Request $request, User $customer, CustomerNotificationService $notifications)
    {
        $request->merge(['template' => 'account-created']);

        return $this->sendStandardEmail($request, $customer, $notifications);
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(404);
        }

        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($customer->id),
            ],
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'house_number' => 'nullable|string|max:20',
            'postal_code' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'kvk_number' => 'nullable|string|max:20',
            'vat_number' => 'nullable|string|max:30',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Naam is verplicht',
            'email.required' => 'E-mailadres is verplicht',
            'email.email' => 'Voer een geldig e-mailadres in',
            'email.unique' => 'Dit e-mailadres is al in gebruik',
        ]);

        $customer->update($validated);

        return redirect()
            ->route('admin.customers.show', $customer)
            ->with('success', 'Klant is succesvol bijgewerkt.');
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(404);
        }

        try {
            app(\App\Services\CustomerDataResetService::class)->purge($customer);

            return redirect()
                ->route('admin.customers.index')
                ->with('success', 'Klant en alle bijbehorende gegevens zijn definitief verwijderd.');
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('admin.customers.index')
                ->with('error', 'Er is een fout opgetreden bij het verwijderen van de klant.');
        }
    }

    /**
     * Toggle customer active status.
     */
    public function toggleStatus(User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(404);
        }

        $customer->update(['is_active' => !$customer->is_active]);

        $status = $customer->is_active ? 'geactiveerd' : 'gedeactiveerd';

        return redirect()
            ->route('admin.customers.show', $customer)
            ->with('success', "Klant is succesvol {$status}.");
    }

    /**
     * Reset customer password.
     */
    public function resetPassword(Request $request, User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(404);
        }

        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => 'Wachtwoord is verplicht',
            'password.min' => 'Wachtwoord moet minimaal 8 tekens bevatten',
            'password.confirmed' => 'Wachtwoord bevestiging komt niet overeen',
        ]);

        $customer->update([
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()
            ->route('admin.customers.show', $customer)
            ->with('success', 'Wachtwoord is succesvol gereset.');
    }

    /**
     * Show customer services.
     */
    public function services(User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(404);
        }

        $services = $customer->customerServices()
            ->with('service')
            ->latest()
            ->paginate(15);

        return view('admin.customers.services', compact('customer', 'services'));
    }

    /**
     * Show customer tickets.
     */
    public function tickets(User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(404);
        }

        $tickets = $customer->tickets()
            ->with(['assignedTo'])
            ->latest()
            ->paginate(15);

        return view('admin.customers.tickets', compact('customer', 'tickets'));
    }

    /**
     * Assign a service to a customer (auto-generates invoice via observer).
     */
    public function storeService(Request $request, User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(404);
        }

        $request->validate([
            'service_id' => 'required|exists:services,id',
            'price' => 'nullable|numeric|min:0',
            'price_type' => 'nullable|in:eenmalig,maandelijks,jaarlijks',
            'domain' => ['nullable', 'string', 'max:253', 'regex:/^(?!-)(?:[a-z0-9-]{1,63}\.)+[a-z]{2,63}$/i'],
        ]);

        $service = Service::findOrFail($request->service_id);

        CustomerService::create([
            'user_id' => $customer->id,
            'service_id' => $service->id,
            'status' => 'active',
            'price' => $request->filled('price') ? $request->price : ($service->price ?? 0),
            'price_type' => $request->filled('price_type') ? $request->price_type : ($service->price_type ?? 'eenmalig'),
            'domain' => $request->filled('domain') ? strtolower($request->domain) : null,
            'provisioning_status' => $service->fulfillment_type === 'directadmin' ? 'pending' : 'not_required',
            'start_date' => now(),
        ]);

        return redirect()
            ->route('admin.customers.show', [$customer, 'tab' => 'services'])
            ->with('success', "Dienst \"{$service->title}\" is toegewezen en factuur is automatisch aangemaakt.");
    }

    public function importExistingService(Request $request, User $customer, DirectAdminClient $directAdmin)
    {
        $this->authorize('hosting.edit');
        abort_unless($customer->isCustomer(), 404);

        $validated = $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'external_username' => ['required', 'string', 'max:32', 'regex:/^[a-z][a-z0-9_-]+$/'],
            'domain' => ['required', 'string', 'max:253', 'regex:/^(?!-)(?:[a-z0-9-]{1,63}\.)+[a-z]{2,63}$/i'],
            'price' => ['required', 'numeric', 'min:0'],
            'billing_cycle' => ['required', Rule::in(array_keys(ServicePrice::CYCLES))],
            'start_date' => ['required', 'date'],
            'current_period_start' => ['required', 'date'],
            'current_period_end' => ['required_unless:billing_cycle,one_time', 'nullable', 'date', 'after_or_equal:current_period_start'],
            'next_invoice_date' => ['required_if:auto_renew,1', 'nullable', 'date', 'after_or_equal:current_period_start'],
            'payment_method' => ['required', Rule::in(['auto_debit', 'payment_link'])],
            'auto_renew' => ['nullable', 'boolean'],
        ]);

        $service = Service::with('serverConnection')->findOrFail($validated['service_id']);
        if ($service->fulfillment_type !== 'directadmin' || ! $service->serverConnection) {
            return back()->withInput()->with('error', 'Kies een DirectAdmin-product met een geldige serverkoppeling.');
        }
        if (! $service->serverConnection->is_active) {
            return back()->withInput()->with('error', 'De gekoppelde DirectAdmin-server is niet actief.');
        }

        $duplicate = CustomerService::where('external_username', $validated['external_username'])
            ->whereHas('service', fn ($query) => $query->where('server_connection_id', $service->server_connection_id))
            ->exists();
        if ($duplicate) {
            return back()->withInput()->with('error', 'Dit DirectAdmin-account is al aan een Servura-dienst gekoppeld.');
        }

        try {
            $config = $directAdmin->using($service->serverConnection)->getUserConfig($validated['external_username']);
        } catch (\Throwable $exception) {
            report($exception);

            return back()->withInput()->with('error', 'Het DirectAdmin-account kon niet worden gecontroleerd: '.$exception->getMessage());
        }

        $actualPackage = trim((string) ($config['package'] ?? ''));
        $expectedPackage = trim((string) ($service->provider_package ?: $service->directadmin_package));
        if ($actualPackage === '' || strcasecmp($actualPackage, $expectedPackage) !== 0) {
            return back()->withInput()->with('error', "Pakketcontrole mislukt. DirectAdmin gebruikt ‘{$actualPackage}’, Servura verwacht ‘{$expectedPackage}’.");
        }
        $actualDomain = strtolower(trim((string) ($config['domain'] ?? '')));
        $domain = strtolower($validated['domain']);
        if ($actualDomain !== '' && $actualDomain !== $domain) {
            return back()->withInput()->with('error', "Domeincontrole mislukt. DirectAdmin gebruikt ‘{$actualDomain}’, het formulier bevat ‘{$domain}’.");
        }

        $autoRenew = ($validated['auto_renew'] ?? false) && $validated['billing_cycle'] !== 'one_time';
        $customerService = DB::transaction(function () use ($validated, $customer, $service, $domain, $autoRenew) {
            return CustomerService::create([
                'user_id' => $customer->id,
                'service_id' => $service->id,
                'domain' => $domain,
                'external_username' => $validated['external_username'],
                'status' => 'active',
                'provisioning_status' => 'active',
                'provisioning_error' => null,
                'provisioned_at' => $validated['start_date'],
                'price' => $validated['price'],
                'price_type' => $service->price_type ?: $validated['billing_cycle'],
                'billing_cycle' => $validated['billing_cycle'],
                'start_date' => $validated['start_date'],
                'current_period_start' => $validated['current_period_start'],
                'current_period_end' => $validated['current_period_end'] ?? null,
                'next_invoice_date' => $autoRenew ? ($validated['next_invoice_date'] ?? null) : null,
                'end_date' => $validated['billing_cycle'] === 'one_time' ? ($validated['current_period_end'] ?? null) : null,
                'auto_renew' => $autoRenew,
                'payment_method' => $validated['payment_method'],
                'notes' => 'Bestaand DirectAdmin-account geïmporteerd zonder provisioning.',
            ]);
        });

        AuditLog::record('existing_directadmin_service_imported', $customerService, null, [
            'customer_id' => $customer->id,
            'service_id' => $service->id,
            'server_connection_id' => $service->server_connection_id,
            'external_username' => $validated['external_username'],
            'domain' => $domain,
            'provider_package' => $actualPackage,
        ]);

        return redirect()->route('admin.customers.show', [$customer, 'tab' => 'services'])
            ->with('success', "Bestaand DirectAdmin-account ‘{$validated['external_username']}’ is gekoppeld zonder provisioning, factuur of e-mail.");
    }

    public function updateServiceRenewal(Request $request, User $customer, CustomerService $service)
    {
        if (!$customer->isCustomer() || $service->user_id !== $customer->id) {
            abort(404);
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(['active', 'inactive', 'suspended', 'cancelled', 'expired'])],
            'price' => 'required|numeric|min:0',
            'billing_cycle' => ['required', Rule::in(array_keys(ServicePrice::CYCLES))],
            'current_period_start' => 'required|date',
            'current_period_end' => 'nullable|date|after_or_equal:current_period_start',
            'end_date' => 'nullable|date',
            'next_invoice_date' => 'nullable|date',
            'payment_method' => 'required|in:auto_debit,payment_link',
            'auto_renew' => 'boolean',
        ]);
        $validated['auto_renew'] = $request->boolean('auto_renew') && $validated['billing_cycle'] !== 'one_time';
        $validated['price_type'] = $validated['billing_cycle'];
        $validated['end_date'] = $validated['end_date'] ?? $validated['current_period_end'] ?? null;
        if (!$validated['auto_renew']) {
            $validated['next_invoice_date'] = null;
        }
        if ($validated['status'] === 'active') {
            $validated['suspension_reason'] = null;
            $validated['suspended_at'] = null;
        }
        if ($validated['status'] === 'cancelled' && ! $service->cancelled_at) {
            $validated['cancelled_at'] = now();
        }
        $service->update($validated);

        // Bij handmatig activeren van een DirectAdmin-dienst met domein:
        // provisioning starten als die nog niet heeft plaatsgevonden.
        if ($validated['status'] === 'active'
            && $service->service->fulfillment_type === 'directadmin'
            && $service->domain
            && in_array($service->provisioning_status, ['pending', 'pending_payment', 'failed', 'processing'], true)) {
            try {
                app(\App\Services\ProvisioningService::class)->provision($service);
            } catch (\Throwable $e) {
                report($e);
            }
        }
        if ($validated['status'] === 'cancelled') {
            try {
                app(\App\Services\ProvisioningService::class)->suspend($service);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return redirect()->route('admin.customers.show', [$customer, 'tab' => 'services'])
            ->with('success', 'Renewalinstellingen zijn bijgewerkt.');
    }

    public function processServiceRenewal(User $customer, CustomerService $service, RenewalService $renewals)
    {
        if (!$customer->isCustomer() || $service->user_id !== $customer->id) {
            abort(404);
        }

        try {
            $invoice = $renewals->processOne($service);
            if (!$invoice) {
                return back()->with('error', 'Renewal is niet verschuldigd of is voor deze periode al verwerkt.');
            }

            return redirect()->route('admin.financial.invoices.show', $invoice)
                ->with('success', "Renewalfactuur {$invoice->invoice_number} is aangemaakt.");
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with('error', 'Renewal kon niet worden verwerkt: ' . $exception->getMessage());
        }
    }

    /**
     * Cancel a customer service.
     */
    public function cancelService(User $customer, CustomerService $service)
    {
        if (!$customer->isCustomer() || $service->user_id !== $customer->id) {
            abort(404);
        }

        $service->update(['status' => 'cancelled', 'auto_renew' => false, 'cancelled_at' => now()]);

        try {
            app(\App\Services\ProvisioningService::class)->suspend($service);
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()
            ->route('admin.customers.show', [$customer, 'tab' => 'services'])
            ->with('success', 'Dienst is geannuleerd.');
    }

    /**
     * Set the domain for a customer service (required before DirectAdmin provisioning).
     */
    public function updateServiceDomain(Request $request, User $customer, CustomerService $service)
    {
        if (!$customer->isCustomer() || $service->user_id !== $customer->id) {
            abort(404);
        }

        $validated = $request->validate([
            'domain' => ['required', 'string', 'max:253', 'regex:/^(?!-)(?:[a-z0-9-]{1,63}\.)+[a-z]{2,63}$/i'],
        ]);

        $service->update(['domain' => strtolower($validated['domain'])]);

        return redirect()
            ->route('admin.customers.show', [$customer, 'tab' => 'services'])
            ->with('success', 'Domein is opgeslagen. Je kunt nu de provisioning starten.');
    }

    /**
     * Delete a customer service.
     */
    public function destroyService(User $customer, CustomerService $service)
    {
        if (!$customer->isCustomer() || $service->user_id !== $customer->id) {
            abort(404);
        }

        $service->invoices()->update(['customer_service_id' => null]);
        $service->tickets()->update(['customer_service_id' => null]);
        $service->cancellationRequests()->delete();
        $service->delete();

        return redirect()
            ->route('admin.customers.show', [$customer, 'tab' => 'services'])
            ->with('success', 'Dienst is verwijderd.');
    }

    /**
     * (Re)start DirectAdmin provisioning for a customer service.
     */
    public function provisionService(User $customer, CustomerService $service, \App\Services\ProvisioningService $provisioning)
    {
        if (!$customer->isCustomer() || $service->user_id !== $customer->id) {
            abort(404);
        }

        if ($service->service->fulfillment_type !== 'directadmin') {
            return back()->with('error', 'Deze dienst heeft geen DirectAdmin-provisioning.');
        }

        if (! $service->domain) {
            return back()->with('error', 'Vul eerst een domein in voordat provisioning kan starten.');
        }

        try {
            $provisioning->provision($service);

            $service->refresh();
            if ($service->provisioning_status === 'active') {
                $service->update([
                    'status' => 'active',
                    'suspension_reason' => null,
                    'suspended_at' => null,
                ]);
            }

            return redirect()
                ->route('admin.customers.show', [$customer, 'tab' => 'services'])
                ->with('success', 'Provisioning is gestart voor '.$service->domain.'.');
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with('error', 'Provisioning mislukt: '.$exception->getMessage());
        }
    }
}
