<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DomainRegistration;

class DomainRegistrationController extends Controller
{
    private function authorizeOwner(): void
    {
        abort_unless(auth()->user()?->isOwner(), 403, 'Alleen eigenaars hebben toegang.');
    }

    public function index()
    {
        $this->authorizeOwner();

        $domains = DomainRegistration::with(['user', 'order'])
            ->orderByDesc('created_at')
            ->paginate(25);

        return view('admin.domains.index', compact('domains'));
    }

    public function show(DomainRegistration $domainRegistration)
    {
        $this->authorizeOwner();

        $domainRegistration->load(['user', 'order', 'customerService']);

        return view('admin.domains.show', compact('domainRegistration'));
    }
}
