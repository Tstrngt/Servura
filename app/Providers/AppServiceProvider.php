<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
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
}
