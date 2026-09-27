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
            'site_location' => BillingSetting::valueFor('site_location', ''),
            'date_format' => BillingSetting::valueFor('date_format', 'd-m-Y'),
            'default_country' => BillingSetting::valueFor('default_country', 'NL'),
            'default_language' => BillingSetting::valueFor('default_language', 'nl'),
            'language_menu_enabled' => BillingSetting::boolean('language_menu_enabled'),
            'newsletter_enabled' => BillingSetting::boolean('newsletter_enabled'),
            'company_legal_name' => BillingSetting::valueFor('company_legal_name', ''),
            'company_trade_name' => BillingSetting::valueFor('company_trade_name', ''),
            'company_address' => BillingSetting::valueFor('company_address', ''),
            'company_postal_code' => BillingSetting::valueFor('company_postal_code', ''),
            'company_city' => BillingSetting::valueFor('company_city', ''),
            'company_country' => BillingSetting::valueFor('company_country', ''),
            'company_kvk_number' => BillingSetting::valueFor('company_kvk_number', ''),
            'company_vat_number' => BillingSetting::valueFor('company_vat_number', ''),
            'company_email' => BillingSetting::valueFor('company_email', BillingSetting::valueFor('contact_email', config('mail.from.address'))),
            'company_phone' => BillingSetting::valueFor('company_phone', ''),
            'company_website' => BillingSetting::valueFor('company_website', ''),
            'company_privacy_email' => BillingSetting::valueFor('company_privacy_email', ''),
            'company_abuse_email' => BillingSetting::valueFor('company_abuse_email', ''),
            'social_instagram' => BillingSetting::valueFor('social_instagram', ''),
            'social_linkedin' => BillingSetting::valueFor('social_linkedin', ''),
            'social_facebook' => BillingSetting::valueFor('social_facebook', ''),
            'social_youtube' => BillingSetting::valueFor('social_youtube', ''),
            'social_tiktok' => BillingSetting::valueFor('social_tiktok', ''),
            'social_x' => BillingSetting::valueFor('social_x', ''),
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
            'site_location' => 'nullable|string|max:255',
            'date_format' => ['required', Rule::in(['d-m-Y', 'd/m/Y', 'Y-m-d', 'm/d/Y'])],
            'default_country' => 'required|string|size:2',
            'default_language' => ['required', Rule::in(['nl', 'en', 'de', 'fr'])],
            'language_menu_enabled' => 'boolean',
            'newsletter_enabled' => 'boolean',
            'company_legal_name' => 'nullable|string|max:255',
            'company_trade_name' => 'nullable|string|max:255',
            'company_address' => 'nullable|string|max:500',
            'company_postal_code' => 'nullable|string|max:20',
            'company_city' => 'nullable|string|max:100',
            'company_country' => 'nullable|string|max:100',
            'company_kvk_number' => 'nullable|string|max:50',
            'company_vat_number' => 'nullable|string|max:50',
            'company_email' => 'nullable|email|max:255',
            'company_phone' => 'nullable|string|max:30',
            'company_website' => 'nullable|url|max:255',
            'company_privacy_email' => 'nullable|email|max:255',
            'company_abuse_email' => 'nullable|email|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'social_linkedin' => 'nullable|url|max:255',
            'social_facebook' => 'nullable|url|max:255',
            'social_youtube' => 'nullable|url|max:255',
            'social_tiktok' => 'nullable|url|max:255',
            'social_x' => 'nullable|url|max:255',
        ]);

        $validated['language_menu_enabled'] = $request->boolean('language_menu_enabled');
        $validated['newsletter_enabled'] = $request->boolean('newsletter_enabled');

        foreach ($validated as $key => $value) {
            BillingSetting::setValue($key, is_bool($value) ? ($value ? '1' : '0') : ($value ?? ''));
        }

        return back()->with('success', 'Algemene instellingen zijn opgeslagen.');
    }

    public function formbuilder()
    {
        $this->authorizeOwner();

        $groups = \App\Support\QuoteFormFields::resolve();
        $lines = collect($groups)->map(fn ($options) => \App\Support\QuoteFormFields::toLines($options));

        return view('admin.settings.formbuilder', compact('lines'));
    }

    public function updateFormbuilder(Request $request)
    {
        $this->authorizeOwner();

        $validated = $request->validate(
            collect(array_keys(\App\Support\QuoteFormFields::GROUPS))
                ->mapWithKeys(fn ($key) => ["fields.$key" => 'nullable|string|max:20000'])
                ->all()
        );

        $fields = [];
        foreach (\App\Support\QuoteFormFields::GROUPS as $key => $label) {
            $parsed = \App\Support\QuoteFormFields::parseLines($request->input("fields.$key", ''));
            if (count($parsed) > 0) {
                $fields[$key] = $parsed;
            }
        }

        BillingSetting::setValue('quote_form_fields', json_encode($fields));

        return back()->with('success', 'Offerteformulier is bijgewerkt.');
    }

    public function legal()
    {
        $this->authorizeOwner();

        $legal = config('legal');

        $settings = [
            'legal_terms_version' => BillingSetting::valueFor('legal_terms_version', $legal['versions']['terms']['version'] ?? ''),
            'legal_terms_effective_date' => BillingSetting::valueFor('legal_terms_effective_date', $legal['versions']['terms']['effective_date'] ?? ''),
            'legal_privacy_version' => BillingSetting::valueFor('legal_privacy_version', $legal['versions']['privacy']['version'] ?? ''),
            'legal_privacy_effective_date' => BillingSetting::valueFor('legal_privacy_effective_date', $legal['versions']['privacy']['effective_date'] ?? ''),
            'legal_cookies_version' => BillingSetting::valueFor('legal_cookies_version', $legal['versions']['cookies']['version'] ?? ''),
            'legal_cookies_effective_date' => BillingSetting::valueFor('legal_cookies_effective_date', $legal['versions']['cookies']['effective_date'] ?? ''),
            'legal_hosting_terms_version' => BillingSetting::valueFor('legal_hosting_terms_version', $legal['versions']['hosting_terms']['version'] ?? ''),
            'legal_hosting_terms_effective_date' => BillingSetting::valueFor('legal_hosting_terms_effective_date', $legal['versions']['hosting_terms']['effective_date'] ?? ''),
            'legal_acceptable_use_version' => BillingSetting::valueFor('legal_acceptable_use_version', $legal['versions']['acceptable_use']['version'] ?? ''),
            'legal_acceptable_use_effective_date' => BillingSetting::valueFor('legal_acceptable_use_effective_date', $legal['versions']['acceptable_use']['effective_date'] ?? ''),
            'legal_retention_contact_request' => BillingSetting::valueFor('legal_retention_contact_request', $legal['retention']['contact_request'] ?? ''),
            'legal_retention_quote_request' => BillingSetting::valueFor('legal_retention_quote_request', $legal['retention']['quote_request'] ?? ''),
            'legal_retention_customer_account' => BillingSetting::valueFor('legal_retention_customer_account', $legal['retention']['customer_account'] ?? ''),
            'legal_retention_analytics' => BillingSetting::valueFor('legal_retention_analytics', $legal['retention']['analytics'] ?? ''),
            'legal_hosting_backup_retention' => BillingSetting::valueFor('legal_hosting_backup_retention', $legal['hosting']['backup_retention'] ?? ''),
            'legal_hosting_backup_frequency' => BillingSetting::valueFor('legal_hosting_backup_frequency', $legal['hosting']['backup_frequency'] ?? ''),
            'legal_hosting_recovery_time' => BillingSetting::valueFor('legal_hosting_recovery_time', $legal['hosting']['recovery_time'] ?? ''),
            'legal_hosting_uptime_sla' => BillingSetting::valueFor('legal_hosting_uptime_sla', $legal['hosting']['uptime_sla'] ?? ''),
            'legal_hosting_support_response_time' => BillingSetting::valueFor('legal_hosting_support_response_time', $legal['hosting']['support_response_time'] ?? ''),
            'legal_hosting_fair_use_storage' => BillingSetting::valueFor('legal_hosting_fair_use_storage', $legal['hosting']['fair_use_storage'] ?? ''),
            'legal_hosting_fair_use_traffic' => BillingSetting::valueFor('legal_hosting_fair_use_traffic', $legal['hosting']['fair_use_traffic'] ?? ''),
        ];

        return view('admin.settings.legal', compact('settings'));
    }

    public function updateLegal(Request $request)
    {
        $this->authorizeOwner();

        $validated = $request->validate([
            'legal_terms_version' => 'nullable|string|max:20',
            'legal_terms_effective_date' => 'nullable|string|max:50',
            'legal_privacy_version' => 'nullable|string|max:20',
            'legal_privacy_effective_date' => 'nullable|string|max:50',
            'legal_cookies_version' => 'nullable|string|max:20',
            'legal_cookies_effective_date' => 'nullable|string|max:50',
            'legal_hosting_terms_version' => 'nullable|string|max:20',
            'legal_hosting_terms_effective_date' => 'nullable|string|max:50',
            'legal_acceptable_use_version' => 'nullable|string|max:20',
            'legal_acceptable_use_effective_date' => 'nullable|string|max:50',
            'legal_retention_contact_request' => 'nullable|string|max:100',
            'legal_retention_quote_request' => 'nullable|string|max:100',
            'legal_retention_customer_account' => 'nullable|string|max:100',
            'legal_retention_analytics' => 'nullable|string|max:100',
            'legal_hosting_backup_retention' => 'nullable|string|max:100',
            'legal_hosting_backup_frequency' => 'nullable|string|max:100',
            'legal_hosting_recovery_time' => 'nullable|string|max:100',
            'legal_hosting_uptime_sla' => 'nullable|string|max:100',
            'legal_hosting_support_response_time' => 'nullable|string|max:100',
            'legal_hosting_fair_use_storage' => 'nullable|string|max:100',
            'legal_hosting_fair_use_traffic' => 'nullable|string|max:100',
        ]);

        foreach ($validated as $key => $value) {
            BillingSetting::setValue($key, $value ?? '');
        }

        return back()->with('success', 'Juridische instellingen zijn opgeslagen.');
    }

    public function security()
    {
        $this->authorizeOwner();

        $captcha = [
            'enabled' => BillingSetting::boolean('captcha_enabled'),
            'provider' => BillingSetting::valueFor('captcha_provider', 'recaptcha_v2'),
            'site_key' => BillingSetting::valueFor('captcha_site_key', ''),
            'has_secret' => BillingSetting::encryptedValueFor('captcha_secret_key', '') !== '',
        ];

        return view('admin.settings.security', compact('captcha'));
    }

    public function updateSecurity(Request $request)
    {
        $this->authorizeOwner();

        $validated = $request->validate([
            'captcha_enabled' => 'boolean',
            'captcha_provider' => ['required', Rule::in(array_keys(\App\Services\CaptchaService::PROVIDERS))],
            'captcha_site_key' => 'nullable|string|max:255',
            'captcha_secret_key' => 'nullable|string|max:255',
        ]);

        BillingSetting::setValue('captcha_enabled', $request->boolean('captcha_enabled') ? '1' : '0');
        BillingSetting::setValue('captcha_provider', $validated['captcha_provider']);
        BillingSetting::setValue('captcha_site_key', $validated['captcha_site_key'] ?? '');
        if (filled($validated['captcha_secret_key'] ?? null)) {
            BillingSetting::setEncryptedValue('captcha_secret_key', $validated['captcha_secret_key']);
        }

        return back()->with('success', 'Beveiligingsinstellingen zijn opgeslagen.');
    }

    public function newsletter()
    {
        $this->authorizeOwner();

        $subscribers = \App\Models\NewsletterSubscriber::latest('subscribed_at')->paginate(25);

        return view('admin.settings.newsletter', [
            'subscribers' => $subscribers,
            'enabled' => BillingSetting::boolean('newsletter_enabled'),
        ]);
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

    public function emailTemplates()
    {
        $this->authorizeOwner();
        $templates = collect(\App\Support\EmailTemplateRegistry::TEMPLATES)->map(fn ($label, $key) => [
            'key' => $key,
            'label' => $label,
            'subject' => BillingSetting::valueFor("email_template_{$key}_subject") ?: \App\Support\EmailTemplateRegistry::SUBJECTS[$key],
            'html' => BillingSetting::valueFor("email_template_{$key}_html") ?: \App\Support\EmailTemplateRegistry::HTML[$key],
        ]);
        $recipient = BillingSetting::valueFor('contact_form_recipient', 'vraag@servura.nl');
        $variables = \App\Support\EmailTemplateRegistry::VARIABLES;

        return view('admin.settings.email-templates', compact('templates', 'recipient', 'variables'));
    }

    public function updateEmailTemplates(Request $request)
    {
        $this->authorizeOwner();
        $validated = $request->validate([
            'contact_form_recipient' => 'required|email|max:255',
            'template_key' => ['required', Rule::in(array_keys(\App\Support\EmailTemplateRegistry::TEMPLATES))],
            'subject' => 'nullable|string|max:255',
            'html' => 'nullable|string|max:100000',
        ]);
        BillingSetting::setValue('contact_form_recipient', $validated['contact_form_recipient']);
        BillingSetting::setValue("email_template_{$validated['template_key']}_subject", $validated['subject'] ?? '');
        BillingSetting::setValue("email_template_{$validated['template_key']}_html", $validated['html'] ?? '');

        return back()->with('success', 'E-mailtemplate is opgeslagen.');
    }

    private function authorizeOwner(): void
    {
        if (! auth()->user()->isOwner()) {
            abort(403, 'Alleen de eigenaar heeft toegang tot instellingen.');
        }
    }
}
