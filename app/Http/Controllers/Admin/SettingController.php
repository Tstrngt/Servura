<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillingSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function general()
    {
        $this->authorizeOwner();

        $settings = [
            'site_name' => BillingSetting::valueFor('site_name', config('app.name')),
            'contact_email' => BillingSetting::valueFor('contact_email', config('mail.from.address')),
            'contact_phone' => BillingSetting::valueFor('contact_phone', ''),
            'contact_address' => BillingSetting::valueFor('contact_address', ''),
        ];

        return view('admin.settings.general', compact('settings'));
    }

    public function updateGeneral(Request $request)
    {
        $this->authorizeOwner();

        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:30',
            'contact_address' => 'nullable|string|max:500',
        ]);

        foreach ($validated as $key => $value) {
            BillingSetting::setValue($key, $value ?? '');
        }

        return back()->with('success', 'Algemene instellingen zijn opgeslagen.');
    }

    public function mail()
    {
        $this->authorizeOwner();

        $settings = [
            'mail_mailer' => BillingSetting::valueFor('mail_mailer', config('mail.default', 'smtp')),
            'mail_host' => BillingSetting::valueFor('mail_host', ''),
            'mail_port' => BillingSetting::valueFor('mail_port', '587'),
            'mail_username' => BillingSetting::valueFor('mail_username', ''),
            'mail_password_set' => BillingSetting::encryptedValueFor('mail_password') !== null,
            'mail_encryption' => BillingSetting::valueFor('mail_encryption', 'tls'),
            'mail_from_address' => BillingSetting::valueFor('mail_from_address', ''),
            'mail_from_name' => BillingSetting::valueFor('mail_from_name', ''),
        ];

        return view('admin.settings.mail', compact('settings'));
    }

    public function updateMail(Request $request)
    {
        $this->authorizeOwner();

        $validated = $request->validate([
            'mail_mailer' => ['required', Rule::in(['smtp', 'log'])],
            'mail_host' => 'nullable|string|max:255',
            'mail_port' => 'nullable|integer|min:1|max:65535',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => ['nullable', Rule::in(['tls', 'ssl', 'none'])],
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name' => 'nullable|string|max:255',
        ]);

        BillingSetting::setValue('mail_mailer', $validated['mail_mailer']);
        BillingSetting::setValue('mail_host', $validated['mail_host'] ?? '');
        BillingSetting::setValue('mail_port', $validated['mail_port'] ?? '587');
        BillingSetting::setValue('mail_username', $validated['mail_username'] ?? '');
        BillingSetting::setValue('mail_encryption', $validated['mail_encryption'] ?? 'tls');
        BillingSetting::setValue('mail_from_address', $validated['mail_from_address'] ?? '');
        BillingSetting::setValue('mail_from_name', $validated['mail_from_name'] ?? '');

        if (filled($validated['mail_password'] ?? null)) {
            BillingSetting::setEncryptedValue('mail_password', $validated['mail_password']);
        }

        return back()->with('success', 'Mailinstellingen zijn opgeslagen.');
    }

    public function sendTestMail(Request $request)
    {
        $this->authorizeOwner();

        try {
            Mail::raw('Dit is een testmail vanuit het Servura adminpanel. De mailinstellingen werken correct.', function ($message) use ($request) {
                $message->to($request->user()->email)->subject('Servura testmail');
            });

            return back()->with('success', 'Testmail is verstuurd naar '.$request->user()->email.'.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Testmail mislukt: '.$e->getMessage());
        }
    }

    public function payments()
    {
        $this->authorizeOwner();

        $mollieKey = BillingSetting::encryptedValueFor('mollie_key', '');
        $mollieConfigured = $mollieKey !== '' && ! str_contains($mollieKey, 'xxxx');
        $mollie = [
            'configured' => $mollieConfigured,
            'mode' => ! $mollieConfigured ? 'unknown' : (str_starts_with($mollieKey, 'live_') ? 'live' : 'test'),
        ];

        return view('admin.settings.payments', compact('mollie'));
    }

    public function updatePayments(Request $request)
    {
        $this->authorizeOwner();

        $validated = $request->validate([
            'mollie_key' => 'nullable|string|max:255',
            'mollie_key_mode' => ['required', Rule::in(['test', 'live'])],
        ]);

        $mollieKey = trim($validated['mollie_key'] ?? '');
        if ($mollieKey !== '') {
            if (! str_starts_with($mollieKey, 'test_') && ! str_starts_with($mollieKey, 'live_')) {
                $prefix = $validated['mollie_key_mode'] === 'live' ? 'live_' : 'test_';
                $mollieKey = $prefix.$mollieKey;
            }
            BillingSetting::setEncryptedValue('mollie_key', $mollieKey);
            Config::set('mollie.key', $mollieKey);
        }

        return back()->with('success', 'Betaalprovider-instellingen zijn opgeslagen.');
    }

    /**
     * Delete every customer account and all related data (test-mode reset).
     */
    public function resetCustomers(Request $request, \App\Services\CustomerDataResetService $reset)
    {
        $this->authorizeOwner();

        $request->validate([
            'confirm' => ['required', Rule::in(['RESET'])],
        ]);

        $count = $reset->purgeAllCustomers();

        return back()->with('success', $count.' klantaccount(s) en alle bijbehorende gegevens zijn verwijderd.');
    }

    private function authorizeOwner(): void
    {
        if (! auth()->user()->isOwner()) {
            abort(403, 'Alleen de eigenaar heeft toegang tot instellingen.');
        }
    }
}
