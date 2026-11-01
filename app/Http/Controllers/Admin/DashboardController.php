<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbuseReport;
use App\Models\ContactMessage;
use App\Models\CustomerService;
use App\Models\Invoice;
use App\Models\Ticket;
use App\Models\User;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Get statistics
        $stats = [
            'total_customers' => User::customers()->count(),
            'active_customers' => User::customers()->active()->count(),
            'total_tickets' => Ticket::count(),
            'open_tickets' => Ticket::open()->count(),
            'overdue_tickets' => Ticket::overdue()->count(),
            'total_services' => CustomerService::count(),
            'active_services' => CustomerService::active()->count(),
            'monthly_revenue' => CustomerService::active()
                ->where('price_type', 'maandelijks')
                ->sum('price'),
            'yearly_revenue' => CustomerService::active()
                ->where('price_type', 'jaarlijks')
                ->sum('price'),
            'unread_messages' => ContactMessage::where('is_read', false)->count(),
            'new_abuse_reports' => AbuseReport::where('status', 'new')->count(),
            'revenue_this_month' => (float) Invoice::where('status', 'betaald')
                ->where('paid_at', '>=', now()->startOfMonth())
                ->sum('total'),
            'revenue_this_year' => (float) Invoice::where('status', 'betaald')
                ->whereYear('paid_at', now()->year)
                ->sum('total'),
        ];

        // Omzet per dag van de afgelopen 30 dagen (voor de grafiek)
        $paidLast30Days = Invoice::where('status', 'betaald')
            ->where('paid_at', '>=', now()->subDays(29)->startOfDay())
            ->get(['paid_at', 'total']);

        $dailyRevenue = collect(range(29, 0))->map(function ($daysAgo) use ($paidLast30Days) {
            $date = now()->subDays($daysAgo)->startOfDay();

            return [
                'label' => $date->format('d-m'),
                'total' => (float) $paidLast30Days->filter(fn ($invoice) => $invoice->paid_at->isSameDay($date))->sum('total'),
            ];
        });

        // Omzet per maand van het huidige jaar (voor de grafiek)
        $paidThisYear = Invoice::where('status', 'betaald')
            ->whereYear('paid_at', now()->year)
            ->get(['paid_at', 'total']);

        $monthNames = ['jan', 'feb', 'mrt', 'apr', 'mei', 'jun', 'jul', 'aug', 'sep', 'okt', 'nov', 'dec'];
        $monthlyRevenue = collect(range(1, 12))->map(fn ($month) => [
            'label' => $monthNames[$month - 1],
            'total' => (float) $paidThisYear->filter(fn ($invoice) => $invoice->paid_at->month === $month)->sum('total'),
        ]);

        // Klanten die het laatst in het portaal zijn ingelogd
        $activeCustomers = User::customers()
            ->whereNotNull('last_login_at')
            ->orderByDesc('last_login_at')
            ->take(5)
            ->get();

        // Actieve diensten
        $activeServices = CustomerService::with(['user', 'service'])
            ->active()
            ->latest()
            ->take(5)
            ->get();

        // Openstaande abuse-meldingen
        $abuseReports = AbuseReport::whereIn('status', ['new', 'reviewing'])
            ->latest()
            ->take(5)
            ->get();

        // Get recent tickets
        $recentTickets = Ticket::with(['user', 'assignedTo'])
            ->latest()
            ->take(5)
            ->get();

        // Get unread contact messages
        $unreadMessages = ContactMessage::where('is_read', false)
            ->latest()
            ->take(5)
            ->get();

        // Get recent customers
        $recentCustomers = User::customers()
            ->latest()
            ->take(5)
            ->get();

        // Get services expiring soon
        $expiringServices = CustomerService::with(['user', 'service'])
            ->expiringSoon()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'dailyRevenue',
            'monthlyRevenue',
            'recentTickets',
            'unreadMessages',
            'recentCustomers',
            'activeCustomers',
            'activeServices',
            'abuseReports',
            'expiringServices'
        ));
    }

    public function markContactMessageAsRead(ContactMessage $contactMessage)
    {
        $contactMessage->markAsRead();

        return redirect()->back();
    }

    public function markAllContactMessagesAsRead()
    {
        ContactMessage::unread()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return redirect()->back();
    }
}
