<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    private const ROLE_COLUMN_MAP = [
        'owner' => 'owner',
        'administrator' => 'admin',
        'support' => 'employee',
        'finance' => 'employee',
        'developer' => 'employee',
        'custom' => 'employee',
    ];

    private const PERMISSION_GROUPS = [
        'Dashboard' => ['dashboard.view'],
        'Klanten' => ['customers.view', 'customers.edit', 'customers.delete'],
        'Bestellingen & betalingen' => ['orders.view', 'orders.edit', 'payments.manage', 'refunds.process'],
        'Facturen' => ['invoices.view', 'invoices.download', 'invoices.drafts.manage', 'invoices.send', 'invoices.credit.create', 'invoice.settings.edit', 'invoice.design.publish'],
        'Domeinen' => ['domains.view', 'domains.edit', 'domains.dns.manage', 'domains.nameservers.edit', 'domains.holder.edit', 'domains.authcode.view', 'domains.cancel'],
        'Hosting' => ['hosting.view', 'hosting.edit', 'hosting.provision', 'hosting.directadmin.login', 'hosting.terminate'],
        'Juridisch' => ['legal.view', 'legal.edit', 'legal.publish'],
        'Instellingen' => ['settings.view', 'settings.edit', 'settings.email.edit', 'settings.integrations.view', 'settings.integrations.edit'],
        'Gevoelige integraties' => ['mollie.settings.edit', 'transip.settings.edit', 'api.credentials.edit', 'server.settings.edit'],
        'Medewerkers' => ['staff.view', 'staff.create', 'staff.edit', 'staff.permissions.manage', 'staff.delete'],
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $this->authorize('staff.view');

        $staff = User::staff()->orderByRaw("CASE role WHEN 'owner' THEN 1 WHEN 'admin' THEN 2 ELSE 3 END")->orderBy('name')->get();
        $roles = Role::whereIn('name', array_keys(self::ROLE_COLUMN_MAP))->orderBy('name')->get();
        $groups = self::PERMISSION_GROUPS;
        $allPermissions = collect($groups)->flatten()->values()->all();

        return view('admin.settings.staff', compact('staff', 'roles', 'groups', 'allPermissions'));
    }

    public function store(Request $request)
    {
        $this->authorize('staff.create');

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'spatie_role' => ['required', Rule::in(array_keys(self::ROLE_COLUMN_MAP))],
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
            'password' => 'required|string|min:12|confirmed',
        ]);

        $dbRole = self::ROLE_COLUMN_MAP[$data['spatie_role']];
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $dbRole,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $user->assignRole($data['spatie_role']);
        $this->syncPermissions($user, $data['permissions'] ?? []);

        AuditLog::record('staff.created', $user, null, ['role' => $data['spatie_role']]);

        return back()->with('success', 'Medewerkeraccount is aangemaakt.');
    }

    public function update(Request $request, User $staff)
    {
        $this->authorize('staff.edit');

        abort_unless(in_array($staff->role, ['owner', 'admin', 'employee'], true), 404);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($staff)],
            'spatie_role' => ['nullable', Rule::in(array_keys(self::ROLE_COLUMN_MAP))],
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
            'is_active' => 'boolean',
            'password' => 'nullable|string|min:12|confirmed',
        ]);

        // Owner protection: only an owner can edit an owner, and an owner must remain active with owner role.
        if ($staff->isOwner()) {
            abort_unless(auth()->user()->isOwner(), 403);
            $data['spatie_role'] = 'owner';
            $data['is_active'] = true;
        } else {
            $data['is_active'] = $request->boolean('is_active');
        }

        $before = $staff->only(['name', 'email', 'role', 'is_active']);

        $update = [
            'name' => $data['name'],
            'email' => $data['email'],
            'is_active' => $data['is_active'],
        ];

        if (! $staff->isOwner() && ! empty($data['spatie_role'])) {
            $update['role'] = self::ROLE_COLUMN_MAP[$data['spatie_role']];
        }

        if (! blank($data['password'] ?? null)) {
            $update['password'] = $data['password'];
        }

        $staff->update($update);

        if (! $staff->isOwner() && ! empty($data['spatie_role'])) {
            $staff->syncRoles($data['spatie_role']);
        }
        $this->syncPermissions($staff, $data['permissions'] ?? []);

        AuditLog::record('staff.updated', $staff, $before, $staff->only(['name', 'email', 'role', 'is_active']));

        return back()->with('success', 'Medewerkeraccount is bijgewerkt.');
    }

    public function destroy(User $staff)
    {
        $this->authorize('staff.delete');

        abort_if($staff->isOwner() || $staff->is(auth()->user()), 403);
        abort_unless(in_array($staff->role, ['admin', 'employee'], true), 404);

        $before = ['name' => $staff->name, 'email' => $staff->email];
        $staff->delete();

        AuditLog::record('staff.deleted', null, $before, null);

        return back()->with('success', 'Medewerkeraccount is verwijderd.');
    }

    private function syncPermissions(User $user, array $permissions): void
    {
        $allowed = collect(self::PERMISSION_GROUPS)->flatten()->values();
        $valid = collect($permissions)->intersect($allowed)->unique()->values()->all();

        // Keep any permissions outside of the managed groups intact.
        $existing = $user->permissions->pluck('name');
        $unmanaged = $existing->diff($allowed)->values()->all();

        $user->syncPermissions(array_merge($valid, $unmanaged));
    }
}
