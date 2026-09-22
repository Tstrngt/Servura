<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerService;
use App\Models\Ticket;
use App\Models\User;
use App\Services\DirectAdminClient;
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

    public function show(CustomerService $customerService)
    {
        $this->authorizeView($customerService);

        $customerService->load(['service.serverConnection', 'service.prices' => fn ($query) => $query->where('is_enabled', true)]);

        return view('customer.services.show', compact('customerService'));
    }

    public function cancel(Request $request, CustomerService $customerService)
    {
        $this->authorizeView($customerService);

        if (!$customerService->isActive()) {
            return back()->with('error', 'Deze dienst is al inactief of geannuleerd.');
        }

        $customerService->update([
            'cancel_at_period_end' => true,
            'cancelled_at' => now(),
        ]);

        Ticket::create([
            'user_id' => Auth::id(),
            'subject' => 'Opzegging ' . $customerService->service->title,
            'message' => 'De klant heeft de dienst opgezegd via het klantportaal. Periode loopt t/m ' . ($customerService->end_date?->format('d-m-Y') ?? 'onbekend') . '.',
            'status' => 'open',
            'priority' => 'normal',
            'page' => 'services',
        ]);

        return back()->with('success', 'De dienst is opgezegd. Hij blijft actief tot het einde van de huidige periode.');
    }

    public function transfer(Request $request, CustomerService $customerService)
    {
        $this->authorizeView($customerService);

        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'reason' => 'nullable|string|max:1000',
        ]);

        Ticket::create([
            'user_id' => Auth::id(),
            'subject' => 'Overdrachtsaanvraag ' . $customerService->service->title,
            'message' => 'De klant wil deze dienst overdragen naar: ' . $validated['email'] . "\n\n" . ($validated['reason'] ?? 'Geen reden opgegeven.'),
            'status' => 'open',
            'priority' => 'normal',
            'page' => 'services',
        ]);

        return back()->with('success', 'Je overdrachtsaanvraag is ontvangen. We nemen contact op zodra deze is verwerkt.');
    }

    public function upgrade(Request $request, CustomerService $customerService)
    {
        $this->authorizeView($customerService);

        $validated = $request->validate([
            'target_service_price_id' => 'required|exists:service_prices,id',
            'reason' => 'nullable|string|max:1000',
        ]);

        $targetPrice = $customerService->service->prices->firstWhere('id', $validated['target_service_price_id']);

        if (!$targetPrice || !$targetPrice->is_enabled) {
            return back()->with('error', 'Gekozen pakket is niet beschikbaar.');
        }

        Ticket::create([
            'user_id' => Auth::id(),
            'subject' => 'Upgrade-aanvraag ' . $customerService->service->title,
            'message' => 'De klant wil upgraden naar pakket: ' . $targetPrice->label . ' (€' . number_format($targetPrice->price, 2, ',', '.') . ').\n\n' . ($validated['reason'] ?? 'Geen reden opgegeven.'),
            'status' => 'open',
            'priority' => 'normal',
            'page' => 'services',
        ]);

        return back()->with('success', 'Je upgrade-aanvraag is ontvangen. We nemen contact op zodra deze is verwerkt.');
    }

    public function resetPassword(Request $request, CustomerService $customerService, DirectAdminClient $directAdmin)
    {
        $this->authorizeView($customerService);

        if ($customerService->service->fulfillment_type !== 'directadmin' || !$customerService->external_username || !$customerService->service->serverConnection) {
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

            return back()->with('error', 'Wachtwoord reset mislukt: ' . $e->getMessage());
        }
    }

    public function directAdminLogin(Request $request, CustomerService $customerService)
    {
        $this->authorizeView($customerService);

        if ($customerService->service->fulfillment_type !== 'directadmin' || !$customerService->external_username || !$customerService->external_password || !$customerService->service->serverConnection) {
            return back()->with('error', 'Direct inloggen is alleen beschikbaar voor actieve DirectAdmin-diensten.');
        }

        $serverUrl = rtrim($customerService->service->serverConnection->url, '/');

        return view('customer.services.directadmin-login', [
            'customerService' => $customerService,
            'loginUrl' => $serverUrl . '/CMD_LOGIN',
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
