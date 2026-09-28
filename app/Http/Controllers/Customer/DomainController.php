<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\DomainRegistration;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function index(Request $request)
    {
        $domains = $request->user()->domainRegistrations()
            ->orderByDesc('created_at')
            ->get();

        return view('customer.domains.index', compact('domains'));
    }

    public function show(DomainRegistration $domain)
    {
        abort_if($domain->user_id !== auth()->id(), 403);

        return view('customer.domains.show', compact('domain'));
    }
}
