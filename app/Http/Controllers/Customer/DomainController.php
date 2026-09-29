<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Jobs\SyncDomainInfoFromProvider;
use App\Jobs\UpdateDomainHolderContacts;
use App\Jobs\UpdateDomainNameservers;
use App\Models\DomainRegistration;
use App\Services\DomainSelfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DomainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('customer');
    }

    public function index(Request $request)
    {
        $domains = $request->user()->domainRegistrations()
            ->orderByDesc('created_at')
            ->get();

        return view('customer.domains.index', compact('domains'));
    }

    public function show(DomainRegistration $domain, DomainSelfService $service)
    {
        $this->authorizeView($domain);

        $domain->load(['customerService', 'hostedCustomerService', 'auditLogs']);

        // Sync if the cached data is stale or missing.
        if (
            $domain->provider_info_synced_at === null
            || $domain->provider_info_synced_at->lt(now()->subMinutes(5))
        ) {
            SyncDomainInfoFromProvider::dispatch($domain->id);
        }

        $tldCapabilities = $service->tldCapabilities($domain);
        $contacts = $domain->status === DomainRegistration::STATUS_ACTIVE
            ? $this->fetchContacts($domain)
            : [];

        return view('customer.domains.show', compact(
            'domain',
            'tldCapabilities',
            'contacts',
        ));
    }

    public function sync(DomainRegistration $domain)
    {
        $this->authorizeView($domain);

        SyncDomainInfoFromProvider::dispatch($domain->id);

        return back()->with('success', 'Domeingegevens worden opgehaald van de provider.');
    }

    public function updateNameservers(Request $request, DomainRegistration $domain)
    {
        $this->authorizeView($domain);

        $validated = $request->validate([
            'nameservers' => ['required', 'array', 'min:1'],
            'nameservers.*.hostname' => ['required', 'string', 'max:255'],
            'nameservers.*.ipv4' => ['nullable', 'ipv4'],
            'nameservers.*.ipv6' => ['nullable', 'ipv6'],
        ]);

        $nameservers = array_values($validated['nameservers']);

        UpdateDomainNameservers::dispatch($domain->id, $nameservers, Auth::id());

        return back()->with('info', 'Nameserverwijziging is ingepland. De status wordt bijgewerkt zodra TransIP de wijziging heeft verwerkt.');
    }

    public function updateHolder(Request $request, DomainRegistration $domain)
    {
        $this->authorizeView($domain);

        $validated = $request->validate([
            'contacts' => ['required', 'array', 'min:1'],
            'contacts.*.type' => ['required', 'in:registrant,administrative,technical'],
            'contacts.*.first_name' => ['required', 'string', 'max:255'],
            'contacts.*.last_name' => ['required', 'string', 'max:255'],
            'contacts.*.company_name' => ['nullable', 'string', 'max:255'],
            'contacts.*.company_kvk' => ['nullable', 'string', 'max:255'],
            'contacts.*.company_type' => ['nullable', 'string', 'max:255'],
            'contacts.*.street' => ['required', 'string', 'max:255'],
            'contacts.*.number' => ['required', 'string', 'max:255'],
            'contacts.*.postal_code' => ['required', 'string', 'max:255'],
            'contacts.*.city' => ['required', 'string', 'max:255'],
            'contacts.*.country' => ['required', 'string', 'size:2'],
            'contacts.*.phone_number' => ['nullable', 'string', 'max:255'],
            'contacts.*.email' => ['required', 'email', 'max:255'],
        ]);

        $contacts = array_values($validated['contacts']);

        UpdateDomainHolderContacts::dispatch($domain->id, $contacts, Auth::id());

        return back()->with('info', 'Houderwijziging is ingepland. De status wordt bijgewerkt zodra TransIP de wijziging heeft verwerkt.');
    }

    public function authCode(Request $request, DomainRegistration $domain, DomainSelfService $service)
    {
        $this->authorizeView($domain);

        $request->validate(['password' => ['required', 'current_password']]);

        $code = $service->getAuthCode($domain, $request->user());

        if ($code === null) {
            return back()->with('error', 'Verhuiscode kon niet worden opgehaald. Mogelijk wordt dit niet ondersteund voor deze extensie of is de provider niet geconfigureerd.');
        }

        return back()->with('auth_code', $code);
    }

    private function fetchContacts(DomainRegistration $domain): array
    {
        $provider = \App\Services\Domains\DomainProviderFactory::default();

        if (! $provider || ! $provider->isConfigured()) {
            return [];
        }

        try {
            return $provider->getContacts($domain->domain_name);
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function authorizeView(DomainRegistration $domain): void
    {
        if ($domain->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
