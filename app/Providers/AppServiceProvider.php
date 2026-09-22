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
}
