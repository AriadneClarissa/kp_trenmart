<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

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
            $pendingReviewCount = 0;
            $latestPendingReviewUser = null;
            $notificationUnreadCount = 0;
            $recentNotifications = collect();

            if (Auth::check()) {
                $notificationUnreadCount = Auth::user()->unreadNotifications()->count();
                $recentNotifications = Auth::user()->notifications()->latest()->take(5)->get();
            }

            if (!Auth::check() || !Auth::user()->isAdmin()) {
                $view->with([
                    'pendingReviewCount' => $pendingReviewCount,
                    'latestPendingReviewUser' => $latestPendingReviewUser,
                    'notificationUnreadCount' => $notificationUnreadCount,
                    'recentNotifications' => $recentNotifications,
                ]);

                return;
            }

            // Approval flow removed — no pending users
            $view->with([
                'pendingReviewCount' => 0,
                'latestPendingReviewUser' => null,
                'notificationUnreadCount' => $notificationUnreadCount,
                'recentNotifications' => $recentNotifications,
            ]);
        });
    }
}
