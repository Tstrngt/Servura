<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
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

        // Get recent tickets / requests for the dashboard
        $recentRequests = $user->tickets()->latest()->take(5)->get();

        $hasActiveService = $activeServices->isNotEmpty();

        $primaryWebsite = $activeServices->first(fn ($customerService) => filled($customerService->domain));
        $websiteUrl = $primaryWebsite ? 'https://'.ltrim($primaryWebsite->domain, '/') : null;

        return view('customer.dashboard', compact(
            'recentRequests',
            'activeServices',
            'hasActiveService',
            'primaryWebsite',
            'websiteUrl'
        ));
    }
}
