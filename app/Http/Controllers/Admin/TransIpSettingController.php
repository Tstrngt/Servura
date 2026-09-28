<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillingSetting;
use App\Services\Domains\DomainProviderFactory;
use Illuminate\Http\Request;

class TransIpSettingController extends Controller
{
    private function authorizeOwner(): void
    {
        abort_unless(auth()->user()?->isOwner(), 403, 'Alleen eigenaars hebben toegang.');
    }

    public function index()
    {
        $this->authorizeOwner();

        $settings = [
            'enabled' => BillingSetting::boolean('transip_enabled'),
            'username' => BillingSetting::valueFor('transip_username', ''),
            'whitelist_only' => BillingSetting::boolean('transip_whitelist_only'),
            'has_private_key' => BillingSetting::encryptedValueFor('transip_private_key', '') !== '',
            'last_status' => BillingSetting::valueFor('transip_last_status', 'Nog niet getest'),
            'last_checked_at' => BillingSetting::valueFor('transip_last_checked_at', ''),
        ];

        return view('admin.settings.transip', compact('settings'));
    }

    public function update(Request $request)
    {
        $this->authorizeOwner();

        $validated = $request->validate([
            'transip_enabled' => 'boolean',
            'transip_username' => 'nullable|string|max:255',
            'transip_private_key' => 'nullable|string',
            'transip_whitelist_only' => 'boolean',
        ]);

        BillingSetting::setValue('transip_enabled', $request->boolean('transip_enabled') ? '1' : '0');
        BillingSetting::setValue('transip_username', $validated['transip_username'] ?? '');
        BillingSetting::setValue('transip_whitelist_only', $request->boolean('transip_whitelist_only') ? '1' : '0');

        if (filled($validated['transip_private_key'] ?? null)) {
            BillingSetting::setEncryptedValue('transip_private_key', $validated['transip_private_key']);
        }

        return back()->with('success', 'TransIP-instellingen opgeslagen.');
    }

    public function test()
    {
        $this->authorizeOwner();

        $provider = DomainProviderFactory::default();

        if (! $provider || ! $provider->isConfigured()) {
            return back()->with('error', 'TransIP is nog niet geconfigureerd.');
        }

        $result = $provider->testConnection();

        BillingSetting::setValue('transip_last_status', $result['success'] ? 'OK: '.$result['message'] : 'Fout: '.$result['message']);
        BillingSetting::setValue('transip_last_checked_at', now()->format('Y-m-d H:i:s'));

        if (! $result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }
}
