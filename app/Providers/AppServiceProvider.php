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
}
