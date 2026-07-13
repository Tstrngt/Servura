<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ticket;
use App\Models\CustomerService;
use App\Models\PortfolioItem;
use App\Models\Service;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        ];

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

        // Get ticket status distribution
        $ticketStatuses = [
            'open' => Ticket::open()->count(),
            'in_progress' => Ticket::inProgress()->count(),
            'waiting_for_customer' => Ticket::waitingForCustomer()->count(),
            'resolved' => Ticket::resolved()->count(),
            'closed' => Ticket::closed()->count(),
        ];

        // Get ticket priority distribution
        $ticketPriorities = [
            'low' => Ticket::priority('low')->count(),
            'medium' => Ticket::priority('medium')->count(),
            'high' => Ticket::priority('high')->count(),
            'urgent' => Ticket::priority('urgent')->count(),
        ];

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
            'recentTickets',
            'unreadMessages',
            'ticketStatuses',
            'ticketPriorities',
            'recentCustomers',
            'expiringServices'
        ));
    }
}
