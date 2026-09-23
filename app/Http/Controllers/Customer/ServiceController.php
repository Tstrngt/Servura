<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerService;
use App\Services\CancellationService;
use App\Services\DirectAdminClient;
use App\Services\PortalTicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('customer');
    }

    /**
     * Display the customer's services overview page.
     */
    public function index()
    {
        $user = Auth::user();

        $activeServices = $user->activeServices()->get();
        $allServices = $user->allServices()->paginate(10);

        $stats = [
            'total_services' => $user->customerServices()->count(),
            'active_services' => $activeServices->count(),
            'monthly_cost' => $activeServices->where('price_type', 'maandelijks')->sum('price'),
            'yearly_cost' => $activeServices->where('price_type', 'jaarlijks')->sum('price'),
            'open_tickets' => $user->openTickets()->count(),
            'total_tickets' => $user->tickets()->count(),
        ];

        $expiringSoon = $user->customerServices()
            ->with('service')
            ->expiringSoon()
            ->get();

        return view('customer.services.index', compact(
            'activeServices',
            'allServices',
            'stats',
            'expiringSoon'
        ));
    }

    public function show(CustomerService $customerService, CancellationService $cancellationService)
    {
        $this->authorizeView($customerService);

        $customerService->load([
            'service.serverConnection',
            'service.prices' => fn ($query) => $query->where('is_enabled', true),
            'cancellationRequests' => fn ($query) => $query->latest(),
        ]);
        $cancellationPreview = $customerService->isActive() && ! $customerService->cancel_at_period_end
            ? $cancellationService->preview($customerService)
            : null;

        return view('customer.services.show', compact('customerService', 'cancellationPreview'));
    }

    public function cancel(
        Request $request,
        CustomerService $customerService,
        CancellationService $cancellationService,
        PortalTicketService $ticketService
    ) {
        $this->authorizeView($customerService);
        $validated = $request->validate(['reason' => ['nullable', 'string', 'max:1000']]);

        $cancellation = $cancellationService->request(
            Auth::user(),
            $customerService,
            $validated['reason'] ?? null,
            $ticketService
        );

        return back()->with('success', 'Je opzegverzoek is ingediend. De geplande einddatum is '.$cancellation->effective_at->format('d-m-Y').'.');
    }

    public function transfer(Request $request, CustomerService $customerService, PortalTicketService $ticketService)
    {
        $this->authorizeView($customerService);

        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'reason' => 'nullable|string|max:1000',
        ]);

        $ticketService->create(Auth::user(), [
            'title' => 'Overdrachtsaanvraag '.$customerService->service->title,
            'description' => 'Ik wil deze dienst overdragen naar '.$validated['email'].".\n\n".($validated['reason'] ?? 'Geen aanvullende reden opgegeven.'),
            'priority' => 'medium',
            'category' => 'general',
            'request_type' => 'dienst_overdragen',
            'request_details' => ['service:'.$customerService->id, 'nieuwe_eigenaar:'.$validated['email']],
            'page' => 'Mijn diensten',
        ]);

        return back()->with('success', 'Je overdrachtsaanvraag is ontvangen. We nemen contact op zodra deze is verwerkt.');
    }

    public function upgrade(Request $request, CustomerService $customerService, PortalTicketService $ticketService)
    {
        $this->authorizeView($customerService);

        $validated = $request->validate([
            'target_service_price_id' => 'required|exists:service_prices,id',
            'reason' => 'nullable|string|max:1000',
        ]);

        $targetPrice = $customerService->service->prices->firstWhere('id', $validated['target_service_price_id']);

        if (! $targetPrice || ! $targetPrice->is_enabled) {
            return back()->with('error', 'Gekozen pakket is niet beschikbaar.');
        }

        $ticketService->create(Auth::user(), [
            'title' => 'Upgrade-aanvraag '.$customerService->service->title,
            'description' => 'Ik wil upgraden naar '.$targetPrice->label.' (€'.number_format($targetPrice->price, 2, ',', '.').").\n\n".($validated['reason'] ?? 'Geen aanvullende reden opgegeven.'),
            'priority' => 'medium',
            'category' => 'feature_request',
            'request_type' => 'dienst_upgraden',
            'request_details' => ['service:'.$customerService->id, 'pakket:'.$targetPrice->id],
            'page' => 'Mijn diensten',
        ]);

        return back()->with('success', 'Je upgrade-aanvraag is ontvangen. We nemen contact op zodra deze is verwerkt.');
    }

    public function resetPassword(Request $request, CustomerService $customerService, DirectAdminClient $directAdmin)
    {
        $this->authorizeView($customerService);

        if ($customerService->service->fulfillment_type !== 'directadmin' || ! $customerService->external_username || ! $customerService->service->serverConnection) {
            return back()->with('error', 'Wachtwoord reset is alleen beschikbaar voor actieve DirectAdmin-diensten.');
        }

        $newPassword = Str::password(20, true, true, false);

        try {
            $directAdmin->using($customerService->service->serverConnection)->resetPassword($customerService->external_username, $newPassword);
            $customerService->update(['external_password' => $newPassword]);

            return back()->with('success', 'Het DirectAdmin-wachtwoord is succesvol gewijzigd. Het nieuwe wachtwoord wordt veilig getoond op deze pagina.');
        } catch (\Throwable $e) {
            Log::error('DirectAdmin wachtwoord reset mislukt', [
                'customer_service_id' => $customerService->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Wachtwoord reset mislukt: '.$e->getMessage());
        }
    }

    public function directAdminLogin(Request $request, CustomerService $customerService)
    {
        $this->authorizeView($customerService);

        if ($customerService->service->fulfillment_type !== 'directadmin' || ! $customerService->external_username || ! $customerService->external_password || ! $customerService->service->serverConnection) {
            return back()->with('error', 'Direct inloggen is alleen beschikbaar voor actieve DirectAdmin-diensten.');
        }

        $serverUrl = rtrim($customerService->service->serverConnection->url, '/');

        return view('customer.services.directadmin-login', [
            'customerService' => $customerService,
            'loginUrl' => $serverUrl.'/CMD_LOGIN',
            'username' => $customerService->external_username,
            'password' => $customerService->external_password,
        ]);
    }

    private function authorizeView(CustomerService $customerService): void
    {
        if ($customerService->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
