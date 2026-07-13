<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('customer');
    }

    /**
     * Display the customer dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get customer's active services
        $activeServices = $user->activeServices()->get();
        
        // Get customer's all services with pagination
        $allServices = $user->allServices()->paginate(10);
        
        // Get statistics
        $stats = [
            'total_services' => $user->customerServices()->count(),
            'active_services' => $activeServices->count(),
            'monthly_cost' => $activeServices->where('price_type', 'maandelijks')->sum('price'),
            'yearly_cost' => $activeServices->where('price_type', 'jaarlijks')->sum('price'),
            'open_tickets' => $user->openTickets()->count(),
            'total_tickets' => $user->tickets()->count(),
        ];

        // Get services expiring soon
        $expiringSoon = $user->customerServices()
            ->with('service')
            ->expiringSoon()
            ->get();

        return view('customer.dashboard', compact(
            'activeServices',
            'allServices',
            'stats',
            'expiringSoon'
        ));
    }
}
