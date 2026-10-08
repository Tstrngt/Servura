<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    use WithoutModelEvents;

    private const PERMISSIONS = [
        'dashboard.view',
        'customers.view',
        'customers.edit',
        'customers.delete',
        'orders.view',
        'orders.edit',
        'payments.manage',
        'refunds.process',
        'invoices.view',
        'invoices.download',
        'invoices.drafts.manage',
        'invoices.send',
        'invoices.credit.create',
        'invoice.settings.edit',
        'invoice.design.publish',
        'domains.view',
        'domains.edit',
        'domains.dns.manage',
        'domains.nameservers.edit',
        'domains.holder.edit',
        'domains.authcode.view',
        'domains.cancel',
        'hosting.view',
        'hosting.edit',
        'hosting.provision',
        'hosting.directadmin.login',
        'hosting.terminate',
        'legal.view',
        'legal.edit',
        'legal.publish',
        'settings.view',
        'settings.edit',
        'settings.email.edit',
        'settings.integrations.view',
        'settings.integrations.edit',
        'mollie.settings.edit',
        'transip.settings.edit',
        'api.credentials.edit',
        'server.settings.edit',
        'staff.view',
        'staff.create',
        'staff.edit',
        'staff.permissions.manage',
        'staff.delete',
    ];

    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (self::PERMISSIONS as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $roles = [
            'owner' => self::PERMISSIONS,
            'administrator' => array_diff(self::PERMISSIONS, [
                // Admins can do almost everything, but owner-only actions remain protected by isOwner().
            ]),
            'support' => [
                'dashboard.view',
                'customers.view',
                'customers.edit',
                'orders.view',
                'payments.manage',
                'domains.view',
                'domains.edit',
                'domains.dns.manage',
                'domains.nameservers.edit',
                'domains.holder.edit',
                'domains.authcode.view',
                'hosting.view',
                'hosting.edit',
                'hosting.directadmin.login',
            ],
            'finance' => [
                'dashboard.view',
                'customers.view',
                'orders.view',
                'orders.edit',
                'payments.manage',
                'refunds.process',
                'invoices.view',
                'invoices.download',
                'invoices.drafts.manage',
                'invoices.send',
                'invoices.credit.create',
            ],
            'developer' => [
                'dashboard.view',
                'settings.view',
                'settings.edit',
                'settings.email.edit',
                'settings.integrations.view',
                'settings.integrations.edit',
                'mollie.settings.edit',
                'transip.settings.edit',
                'api.credentials.edit',
                'server.settings.edit',
            ],
        ];

        foreach ($roles as $name => $permissions) {
            $role = Role::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
            $role->syncPermissions($permissions);
        }

        // Map existing role column values to Spatie roles for staff members.
        $roleMap = [
            'owner' => 'owner',
            'admin' => 'administrator',
            'employee' => 'support',
        ];

        foreach ($roleMap as $dbRole => $spatieRole) {
            $role = Role::findByName($spatieRole, 'web');
            \App\Models\User::where('role', $dbRole)
                ->whereDoesntHave('roles')
                ->get()
                ->each(fn ($user) => $user->assignRole($role));
        }
    }
}
