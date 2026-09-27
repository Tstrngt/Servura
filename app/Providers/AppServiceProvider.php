<?php

namespace App\Providers;

use App\Models\BillingSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureMollieKey();
        $this->configureMailSettings();
        $this->configureSiteSettings();
        $this->configureCompanySettings();
        $this->configureLegalSettings();

        View::composer('*', function ($view) {
            if (Auth::check()) {
                $notifications = Auth::user()->notifications()->unread()->limit(10)->get();
                $view->with('unreadNotifications', $notifications);
                $view->with('unreadCount', $notifications->count());
            } else {
                $view->with('unreadNotifications', collect());
                $view->with('unreadCount', 0);
            }
        });
    }

    private function configureMollieKey(): void
    {
        try {
            $mollieKey = BillingSetting::encryptedValueFor('mollie_key');
            if ($mollieKey && $mollieKey !== '') {
                Config::set('mollie.key', $mollieKey);
            }
        } catch (\Throwable $e) {
            // Database may not be available during migrations or cache warming.
        }
    }

    private function configureMailSettings(): void
    {
        try {
            $mailer = BillingSetting::valueFor('mail_mailer');
            if (! $mailer) {
                return;
            }

            Config::set('mail.default', $mailer);

            if ($mailer === 'smtp') {
                Config::set('mail.mailers.smtp', array_filter([
                    'transport' => 'smtp',
                    'host' => BillingSetting::valueFor('mail_host'),
                    'port' => BillingSetting::integer('mail_port', 587),
                    'encryption' => BillingSetting::valueFor('mail_encryption', 'tls') !== 'none'
                        ? BillingSetting::valueFor('mail_encryption', 'tls')
                        : null,
                    'username' => BillingSetting::valueFor('mail_username'),
                    'password' => BillingSetting::encryptedValueFor('mail_password'),
                    'timeout' => null,
                ]));
            }

            Config::set('mail.from', array_filter([
                'address' => BillingSetting::valueFor('mail_from_address'),
                'name' => BillingSetting::valueFor('mail_from_name'),
            ]) ?: Config::get('mail.from'));
        } catch (\Throwable $e) {
            // Database may not be available during migrations or cache warming.
        }
    }

    /**
     * Load site settings (locale, social links, newsletter) into config
     * so views can read them without hitting the database per render.
     */
    private function configureSiteSettings(): void
    {
        try {
            Config::set('site', [
                'name' => BillingSetting::valueFor('site_name', config('app.name')),
                'location' => BillingSetting::valueFor('site_location', ''),
                'date_format' => BillingSetting::valueFor('date_format', 'd-m-Y'),
                'default_country' => BillingSetting::valueFor('default_country', 'NL'),
                'default_language' => BillingSetting::valueFor('default_language', 'nl'),
                'language_menu' => BillingSetting::boolean('language_menu_enabled'),
                'newsletter_enabled' => BillingSetting::boolean('newsletter_enabled'),
            ]);

            Config::set('social', [
                'instagram' => BillingSetting::valueFor('social_instagram', ''),
                'linkedin' => BillingSetting::valueFor('social_linkedin', ''),
                'facebook' => BillingSetting::valueFor('social_facebook', ''),
                'youtube' => BillingSetting::valueFor('social_youtube', ''),
                'tiktok' => BillingSetting::valueFor('social_tiktok', ''),
                'x' => BillingSetting::valueFor('social_x', ''),
            ]);
        } catch (\Throwable $e) {
            // Database may not be available during migrations or cache warming.
        }
    }

    /**
     * Load company details from BillingSetting or config file defaults.
     */
    private function configureCompanySettings(): void
    {
        try {
            $defaults = config('company');

            $companyEmail = BillingSetting::valueFor('company_email');
            if (blank($companyEmail)) {
                $companyEmail = BillingSetting::valueFor('contact_email', $defaults['email'] ?? '');
            }

            Config::set('company', [
                'legal_name' => BillingSetting::valueFor('company_legal_name', $defaults['legal_name'] ?? ''),
                'trade_name' => BillingSetting::valueFor('company_trade_name', $defaults['trade_name'] ?? ''),
                'address' => BillingSetting::valueFor('company_address', $defaults['address'] ?? ''),
                'postal_code' => BillingSetting::valueFor('company_postal_code', $defaults['postal_code'] ?? ''),
                'city' => BillingSetting::valueFor('company_city', $defaults['city'] ?? ''),
                'country' => BillingSetting::valueFor('company_country', $defaults['country'] ?? ''),
                'kvk_number' => BillingSetting::valueFor('company_kvk_number', $defaults['kvk_number'] ?? ''),
                'vat_number' => BillingSetting::valueFor('company_vat_number', $defaults['vat_number'] ?? ''),
                'email' => $companyEmail,
                'phone' => BillingSetting::valueFor('company_phone', $defaults['phone'] ?? ''),
                'website' => BillingSetting::valueFor('company_website', $defaults['website'] ?? ''),
                'privacy_email' => BillingSetting::valueFor('company_privacy_email', $defaults['privacy_email'] ?? ''),
                'abuse_email' => BillingSetting::valueFor('company_abuse_email', $defaults['abuse_email'] ?? ''),
            ]);
        } catch (\Throwable $e) {
            // Database may not be available during migrations or cache warming.
        }
    }

    /**
     * Load legal document versions from BillingSetting or config file defaults.
     */
    private function configureLegalSettings(): void
    {
        try {
            $defaults = config('legal');

            $versions = $defaults['versions'] ?? [];
            foreach (array_keys($versions) as $key) {
                $versions[$key]['version'] = BillingSetting::valueFor("legal_{$key}_version", $versions[$key]['version']);
                $versions[$key]['effective_date'] = BillingSetting::valueFor("legal_{$key}_effective_date", $versions[$key]['effective_date']);
            }

            $retention = $defaults['retention'] ?? [];
            foreach (array_keys($retention) as $key) {
                $retention[$key] = BillingSetting::valueFor("legal_retention_{$key}", $retention[$key] ?? '');
            }

            $hosting = $defaults['hosting'] ?? [];
            foreach (array_keys($hosting) as $key) {
                $hosting[$key] = BillingSetting::valueFor("legal_hosting_{$key}", $hosting[$key] ?? '');
            }

            Config::set('legal', array_merge($defaults, [
                'versions' => $versions,
                'retention' => $retention,
                'hosting' => $hosting,
            ]));
        } catch (\Throwable $e) {
            // Database may not be available during migrations or cache warming.
        }
    }
}
