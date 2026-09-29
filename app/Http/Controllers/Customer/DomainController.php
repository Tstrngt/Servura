<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Jobs\SyncDomainInfoFromProvider;
use App\Jobs\UpdateDomainDnsRecords;
use App\Jobs\UpdateDomainHolderContacts;
use App\Jobs\UpdateDomainNameservers;
use App\Models\DomainInternalTransfer;
use App\Models\DomainRegistration;
use App\Models\User;
use App\Services\DomainDnsService;
use App\Services\DomainHostingService;
use App\Services\DomainService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

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

    public function show(
        DomainRegistration $domain,
        DomainService $domainService,
        DomainDnsService $dnsService,
        DomainHostingService $hostingService,
    ) {
        $this->authorizeView($domain);

        $domain->load(['customerService', 'hostedCustomerService', 'auditLogs']);

        // Sync provider info when stale so the page shows real data.
        if (
            $domain->provider_info_synced_at === null
            || $domain->provider_info_synced_at->lt(now()->subMinutes(5))
        ) {
            $domainService->syncInfo($domain);
            $domain->refresh();
        }

        $label = $domain->statusLabel;

        $overview = [
            'domain_name' => $domain->domain_name,
            'status' => $label['text'],
            'status_color' => $label['color'],
            'provider' => strtoupper($domain->provider),
            'registration_date' => $domain->registered_at?->format('d-m-Y') ?? '-',
            'expiry_date' => $domain->expires_at?->format('d-m-Y') ?? '-',
            'auto_renew' => $domain->auto_renew,
            'registrar_lock' => $domain->registrar_lock,
            'hosting_package' => $domain->hostedCustomerService?->service?->title ?? 'Geen',
            'hosting_status' => $domain->hostedCustomerService?->isActive() ? 'actief' : ($domain->hostedCustomerService?->status ?? '-'),
            'nameserver_status' => $domain->current_nameservers
                ? collect($domain->current_nameservers)->pluck('hostname')->implode(', ')
                : 'Niet gesynchroniseerd',
            'dns_status' => $dnsService->canManage($domain) ? 'TransIP DNS' : 'Extern DNS',
        ];

        $holderContacts = $domainService->contacts($domain);
        $nameservers = $domain->current_nameservers ?? [];
        $dnsManaged = $dnsService->canManage($domain);
        $dnsRecords = $dnsManaged ? $dnsService->records($domain) : [];
        $hosting = $hostingService->details($domain);
        $availableHostingServices = $hosting['linked'] ? [] : $hostingService->availableServicesFor($domain);
        $tldCapabilities = $domainService->tldCapabilities($domain);

        return view('customer.domains.show', compact(
            'domain',
            'overview',
            'holderContacts',
            'nameservers',
            'dnsManaged',
            'dnsRecords',
            'hosting',
            'availableHostingServices',
            'tldCapabilities',
        ));
    }

    public function sync(DomainRegistration $domain, DomainService $domainService)
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

        return back()->with('info', 'Nameserverwijziging is ingepland. De status wordt bijgewerkt zodra de provider de wijziging heeft verwerkt.');
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

        return back()->with('info', 'Houderwijziging is ingepland. De status wordt bijgewerkt zodra de provider de wijziging heeft verwerkt.');
    }

    public function updateDns(Request $request, DomainRegistration $domain)
    {
        $this->authorizeView($domain);

        $validated = $request->validate([
            'records' => ['required', 'array'],
            'records.*.name' => ['required', 'string', 'max:255'],
            'records.*.type' => ['required', 'in:A,AAAA,CNAME,MX,TXT,SRV,CAA,NS'],
            'records.*.expire' => ['required', 'integer', 'min:60'],
            'records.*.content' => ['required', 'string', 'max:255'],
        ]);

        $records = array_values(array_filter($validated['records'], fn ($record) => filled($record['name']) && filled($record['content'])));

        UpdateDomainDnsRecords::dispatch($domain->id, $records, Auth::id());

        return back()->with('info', 'DNS-wijziging is ingepland. De records worden verwerkt via de provider.');
    }

    public function authCode(Request $request, DomainRegistration $domain, DomainService $domainService)
    {
        $this->authorizeView($domain);

        $request->validate(['password' => ['required', 'current_password']]);

        $code = $domainService->getAuthCode($domain, $request->user());

        if ($code === null) {
            return back()->with('error', 'Verhuiscode kon niet worden opgehaald. Mogelijk wordt dit niet ondersteund voor deze extensie of is de provider niet geconfigureerd.');
        }

        return back()->with('auth_code', $code);
    }

    public function autoRenew(Request $request, DomainRegistration $domain)
    {
        $this->authorizeView($domain);

        $validated = $request->validate([
            'auto_renew' => ['required', 'boolean'],
        ]);

        $domain->update(['auto_renew' => $validated['auto_renew']]);

        return back()->with('success', 'Automatische verlenging is '.($domain->auto_renew ? 'ingeschakeld' : 'uitgeschakeld').'.');
    }

    public function requestInternalTransfer(Request $request, DomainRegistration $domain)
    {
        $this->authorizeView($domain);

        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $targetUser = User::where('email', $validated['email'])->first();

        if ($targetUser->id === Auth::id()) {
            return back()->with('error', 'Je kunt een domein niet naar jezelf overdragen.');
        }

        $existing = DomainInternalTransfer::where('domain_registration_id', $domain->id)
            ->where('status', DomainInternalTransfer::STATUS_PENDING)
            ->first();

        if ($existing) {
            return back()->with('error', 'Er is al een lopende overdrachtsaanvraag voor dit domein.');
        }

        try {
            DB::transaction(function () use ($domain, $targetUser) {
                DomainInternalTransfer::create([
                    'domain_registration_id' => $domain->id,
                    'from_user_id' => Auth::id(),
                    'to_user_id' => $targetUser->id,
                    'token' => Str::random(64),
                    'status' => DomainInternalTransfer::STATUS_PENDING,
                ]);
            });
        } catch (Throwable $e) {
            Log::warning('Domain internal transfer request failed', [
                'domain_registration_id' => $domain->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Overdrachtsaanvraag mislukt: '.$e->getMessage());
        }

        return back()->with('success', 'Overdrachtsaanvraag verstuurd. De ontvanger moet deze nog accepteren.');
    }

    public function acceptInternalTransfer(string $token)
    {
        $transfer = DomainInternalTransfer::with('domainRegistration')
            ->where('token', $token)
            ->where('status', DomainInternalTransfer::STATUS_PENDING)
            ->firstOrFail();

        if ($transfer->to_user_id !== Auth::id()) {
            abort(403);
        }

        $domain = $transfer->domainRegistration;

        try {
            DB::transaction(function () use ($domain, $transfer) {
                $domain->update(['user_id' => $transfer->to_user_id]);

                $transfer->update([
                    'status' => DomainInternalTransfer::STATUS_ACCEPTED,
                    'accepted_at' => now(),
                ]);
            });
        } catch (Throwable $e) {
            Log::warning('Domain internal transfer acceptance failed', [
                'domain_registration_id' => $domain->id,
                'transfer_id' => $transfer->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('customer.domains.index')->with('error', 'Overdracht accepteren mislukt: '.$e->getMessage());
        }

        return redirect()->route('customer.domains.show', $domain)->with('success', 'Domein is succesvol overgedragen naar jouw account.');
    }

    public function cancel(DomainRegistration $domain)
    {
        $this->authorizeView($domain);

        $domain->update([
            'auto_renew' => false,
        ]);

        return back()->with('success', 'Het domein wordt niet automatisch verlengd. Het blijft actief tot de einddatum.');
    }

    public function linkHosting(Request $request, DomainRegistration $domain, DomainHostingService $hostingService)
    {
        $this->authorizeView($domain);

        $validated = $request->validate([
            'customer_service_id' => ['required', 'integer', 'exists:customer_services,id'],
        ]);

        $result = $hostingService->link($domain, $validated['customer_service_id'], $request->user());

        return $result['success']
            ? back()->with('success', $result['message'])
            : back()->with('error', $result['message']);
    }

    public function unlinkHosting(Request $request, DomainRegistration $domain, DomainHostingService $hostingService)
    {
        $this->authorizeView($domain);

        $result = $hostingService->unlink($domain, $request->user());

        return $result['success']
            ? back()->with('success', $result['message'])
            : back()->with('error', $result['message']);
    }

    private function authorizeView(DomainRegistration $domain): void
    {
        if ($domain->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
