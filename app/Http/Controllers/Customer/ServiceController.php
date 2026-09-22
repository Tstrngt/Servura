<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
}
