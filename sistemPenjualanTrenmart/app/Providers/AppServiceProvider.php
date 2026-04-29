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

            if (!Auth::check() || !Auth::user()->isAdmin()) {
                $view->with([
                    'pendingReviewCount' => $pendingReviewCount,
                    'latestPendingReviewUser' => $latestPendingReviewUser,
                ]);

                return;
            }

            $latestPendingReviewUser = User::where('customer_type', 'langganan')
                ->where('is_approved', false)
                ->orderByDesc('created_at')
                ->first();

            $pendingReviewCount = User::where('customer_type', 'langganan')
                ->where('is_approved', false)
                ->count();

            $view->with([
                'pendingReviewCount' => $pendingReviewCount,
                'latestPendingReviewUser' => $latestPendingReviewUser,
            ]);
        });
    }
}
