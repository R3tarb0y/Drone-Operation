<?php

namespace App\Providers;
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
        View::composer('layouts.navigation', function ($view) {
            $notifications = [
                'requests' => \App\Models\Requests::where('status', 'pending')->get(),
                'receives' => \App\Models\Receive::where('status', 'pending')->get(),
                'transfers' => \App\Models\Transfer::where('status', 'pending')->get(),
                'estimations' => \App\Models\Estimation::where('status', 'pending')->get(),
            ];
    
            $view->with('notifications', $notifications);
        });
    }
}
