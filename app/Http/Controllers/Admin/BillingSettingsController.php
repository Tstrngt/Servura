<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillingSetting;
use Illuminate\Http\Request;

class BillingSettingsController extends Controller
{
    public function edit()
    {
        $settings = [
            'default_vat_rate' => BillingSetting::decimal('default_vat_rate', 21.0),
            'country_vat_enabled' => BillingSetting::boolean('country_vat_enabled'),
            'invoice_due_days' => BillingSetting::integer('invoice_due_days', 14),
            'suspension_grace_days' => BillingSetting::integer('suspension_grace_days', 7),
            'business_country' => BillingSetting::valueFor('business_country', 'NL'),
        ];
        $mollieKey = (string) config('mollie.key', env('MOLLIE_KEY', ''));
        $mollieConfigured = $mollieKey !== '' && !str_contains($mollieKey, 'xxxx');
        $mollie = [
            'configured' => $mollieConfigured,
            'mode' => !$mollieConfigured ? 'unknown' : (str_starts_with($mollieKey, 'live_') ? 'live' : 'test'),
        ];

        return view('admin.billing-settings', compact('settings', 'mollie'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'default_vat_rate' => 'required|numeric|min:0|max:100',
            'invoice_due_days' => 'required|integer|min:1|max:90',
            'suspension_grace_days' => 'required|integer|min:0|max:90',
            'business_country' => 'required|string|size:2',
            'country_vat_enabled' => 'boolean',
        ]);

        BillingSetting::setValue('default_vat_rate', $validated['default_vat_rate']);
        BillingSetting::setValue('invoice_due_days', $validated['invoice_due_days']);
        BillingSetting::setValue('suspension_grace_days', $validated['suspension_grace_days']);
        BillingSetting::setValue('business_country', strtoupper($validated['business_country']));
        BillingSetting::setValue('country_vat_enabled', $request->boolean('country_vat_enabled') ? '1' : '0');

        return back()->with('success', 'Facturatie-instellingen zijn opgeslagen.');
    }
}
