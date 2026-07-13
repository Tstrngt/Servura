<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CustomerService;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of customers.
     */
    public function index(Request $request)
    {
        $query = User::customers()->with(['customerServices.service']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $customers = $query->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Naam is verplicht',
            'email.required' => 'E-mailadres is verplicht',
            'email.email' => 'Voer een geldig e-mailadres in',
            'email.unique' => 'Dit e-mailadres is al in gebruik',
            'password.required' => 'Wachtwoord is verplicht',
            'password.min' => 'Wachtwoord moet minimaal 8 tekens bevatten',
            'password.confirmed' => 'Wachtwoord bevestiging komt niet overeen',
        ]);

        $validated['role'] = 'customer';
        $validated['password'] = Hash::make($validated['password']);

        $customer = User::create($validated);

        return redirect()
            ->route('admin.customers.show', $customer)
            ->with('success', 'Klant is succesvol aangemaakt.');
    }

    /**
     * Display the specified customer.
     */
    public function show(User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(404);
        }

        $customer->load([
            'customerServices.service',
            'tickets' => function ($query) {
                $query->latest()->limit(10);
            }
        ]);

        // Get statistics
        $stats = [
            'total_services' => $customer->customerServices()->count(),
            'active_services' => $customer->activeServices()->count(),
            'total_tickets' => $customer->tickets()->count(),
            'open_tickets' => $customer->openTickets()->count(),
            'monthly_cost' => $customer->activeServices()
                ->where('price_type', 'maandelijks')
                ->sum('price'),
            'yearly_cost' => $customer->activeServices()
                ->where('price_type', 'jaarlijks')
                ->sum('price'),
        ];

        return view('admin.customers.show', compact('customer', 'stats'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(404);
        }

        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($customer->id),
            ],
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Naam is verplicht',
            'email.required' => 'E-mailadres is verplicht',
            'email.email' => 'Voer een geldig e-mailadres in',
            'email.unique' => 'Dit e-mailadres is al in gebruik',
        ]);

        $customer->update($validated);

        return redirect()
            ->route('admin.customers.show', $customer)
            ->with('success', 'Klant is succesvol bijgewerkt.');
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(404);
        }

        // Check if customer has active services or tickets
        if ($customer->activeServices()->count() > 0 || $customer->openTickets()->count() > 0) {
            return redirect()
                ->route('admin.customers.index')
                ->with('error', 'Kan klant niet verwijderen. Klant heeft nog actieve diensten of open tickets.');
        }

        DB::beginTransaction();
        try {
            // Delete related data
            $customer->customerServices()->delete();
            $customer->tickets()->delete();
            $customer->delete();

            DB::commit();

            return redirect()
                ->route('admin.customers.index')
                ->with('success', 'Klant is succesvol verwijderd.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->route('admin.customers.index')
                ->with('error', 'Er is een fout opgetreden bij het verwijderen van de klant.');
        }
    }

    /**
     * Toggle customer active status.
     */
    public function toggleStatus(User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(404);
        }

        $customer->update(['is_active' => !$customer->is_active]);

        $status = $customer->is_active ? 'geactiveerd' : 'gedeactiveerd';

        return redirect()
            ->route('admin.customers.show', $customer)
            ->with('success', "Klant is succesvol {$status}.");
    }

    /**
     * Reset customer password.
     */
    public function resetPassword(Request $request, User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(404);
        }

        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => 'Wachtwoord is verplicht',
            'password.min' => 'Wachtwoord moet minimaal 8 tekens bevatten',
            'password.confirmed' => 'Wachtwoord bevestiging komt niet overeen',
        ]);

        $customer->update([
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()
            ->route('admin.customers.show', $customer)
            ->with('success', 'Wachtwoord is succesvol gereset.');
    }

    /**
     * Show customer services.
     */
    public function services(User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(404);
        }

        $services = $customer->customerServices()
            ->with('service')
            ->latest()
            ->paginate(15);

        return view('admin.customers.services', compact('customer', 'services'));
    }

    /**
     * Show customer tickets.
     */
    public function tickets(User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(404);
        }

        $tickets = $customer->tickets()
            ->with(['assignedTo'])
            ->latest()
            ->paginate(15);

        return view('admin.customers.tickets', compact('customer', 'tickets'));
    }
}
