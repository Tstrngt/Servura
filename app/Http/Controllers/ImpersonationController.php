<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public function start(Request $request, User $customer)
    {
        abort_unless($request->user()->canAccessAdmin(), 403);
        abort_unless($request->user()->can('customers.impersonate'), 403);
        abort_if($request->session()->has('impersonator_id'), 409, 'Er is al een klantsessie actief.');
        abort_unless($customer->isCustomer() && $customer->is_active, 404);

        $staff = $request->user();
        AuditLog::record('customer.impersonation.started', $customer, null, [
            'staff_id' => $staff->id,
            'customer_email' => $customer->email,
        ], null, $staff);

        $request->session()->put([
            'impersonator_id' => $staff->id,
            'impersonator_name' => $staff->name,
            'impersonated_customer_id' => $customer->id,
        ]);
        Auth::login($customer);
        $request->session()->regenerate();

        return redirect()->route('customer.dashboard')
            ->with('success', "U bekijkt het klantportaal als {$customer->name}.");
    }

    public function stop(Request $request)
    {
        $staffId = $request->session()->get('impersonator_id');
        $customerId = $request->session()->get('impersonated_customer_id');
        abort_unless($staffId && $customerId, 404);

        $staff = User::find($staffId);
        $customer = User::find($customerId);
        abort_unless($staff && $staff->is_active && $staff->canAccessAdmin(), 403);

        Auth::login($staff);
        $request->session()->forget(['impersonator_id', 'impersonator_name', 'impersonated_customer_id']);
        $request->session()->regenerate();

        AuditLog::record('customer.impersonation.stopped', $customer, null, [
            'staff_id' => $staff->id,
            'customer_email' => $customer?->email,
        ], null, $staff);

        return redirect()->route($customer ? 'admin.customers.show' : 'admin.customers.index', $customer ? [$customer] : [])
            ->with('success', 'U bent teruggekeerd naar het adminportaal.');
    }
}
