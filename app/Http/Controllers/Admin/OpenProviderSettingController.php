<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\BillingSetting;
use App\Services\Domains\DomainProviderFactory;
use App\Services\Domains\OpenProviderClient;
use Illuminate\Http\Request;

class OpenProviderSettingController extends Controller
{
    public function index()
    {
        $this->authorizeOwner();
        $settings = [
            'enabled' => BillingSetting::boolean('openprovider_enabled'),
            'environment' => BillingSetting::valueFor('openprovider_environment', 'sandbox'),
            'username' => BillingSetting::valueFor('openprovider_username', ''),
            'has_password' => BillingSetting::encryptedValueFor('openprovider_password', '') !== '',
            'customer_handle' => BillingSetting::valueFor('openprovider_customer_handle', ''),
            'default_nameservers' => BillingSetting::valueFor('domain_default_nameservers', ''),
            'last_status' => BillingSetting::valueFor('openprovider_last_status', 'Nog niet getest'),
            'last_checked_at' => BillingSetting::valueFor('openprovider_last_checked_at', ''),
            'is_default' => BillingSetting::valueFor('domain_provider', 'transip') === 'openprovider',
        ];

        return view('admin.settings.openprovider', compact('settings'));
    }

    public function update(Request $request, OpenProviderClient $client)
    {
        $this->authorizeOwner();
        $validated = $request->validate([
            'openprovider_enabled' => 'boolean',
            'openprovider_environment' => 'required|in:sandbox,production',
            'openprovider_username' => 'nullable|email|max:255',
            'openprovider_password' => 'nullable|string|max:1000',
            'openprovider_customer_handle' => 'nullable|string|max:100',
            'domain_default_nameservers' => 'nullable|string|max:1000',
            'use_as_default' => 'boolean',
        ]);

        BillingSetting::setValue('openprovider_enabled', $request->boolean('openprovider_enabled') ? '1' : '0');
        BillingSetting::setValue('openprovider_environment', $validated['openprovider_environment']);
        BillingSetting::setValue('openprovider_username', $validated['openprovider_username'] ?? '');
        BillingSetting::setValue('openprovider_customer_handle', $validated['openprovider_customer_handle'] ?? '');
        BillingSetting::setValue('domain_default_nameservers', $validated['domain_default_nameservers'] ?? '');
        if (filled($validated['openprovider_password'] ?? null)) {
            BillingSetting::setEncryptedValue('openprovider_password', $validated['openprovider_password']);
        }
        if ($request->boolean('use_as_default')) {
            BillingSetting::setValue('domain_provider', 'openprovider');
        }
        $client->clearToken();
        AuditLog::record('openprovider.settings.updated', null, null, [
            'environment' => $validated['openprovider_environment'],
            'enabled' => $request->boolean('openprovider_enabled'),
            'default' => $request->boolean('use_as_default'),
        ]);

        return back()->with('success', 'Openprovider-instellingen opgeslagen.');
    }

    public function test()
    {
        $this->authorizeOwner();
        $provider = DomainProviderFactory::make('openprovider');
        $result = $provider->testConnection();
        BillingSetting::setValue('openprovider_last_status', ($result['success'] ? 'OK: ' : 'Fout: ').$result['message']);
        BillingSetting::setValue('openprovider_last_checked_at', now()->format('Y-m-d H:i:s'));

        return back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    private function authorizeOwner(): void
    {
        abort_unless(auth()->user()?->isOwner(), 403, 'Alleen eigenaars hebben toegang.');
    }
}
