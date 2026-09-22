<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

        // Get customer website URL if available
        $websiteUrl = $user->customerServices()->whereNotNull('domain')->first()?->domain;
        if (empty($websiteUrl)) {
            $websiteUrl = $user->company ? 'https://www.' . Str::slug($user->company) . '.nl' : null;
        }

        return view('customer.dashboard', compact(
            'recentRequests',
            'activeServices',
            'hasActiveService',
            'websiteUrl'
        ));
    }
}
