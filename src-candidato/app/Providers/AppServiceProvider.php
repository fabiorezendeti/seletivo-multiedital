<?php

namespace App\Providers;

use App\Models\Process\Subscription;
use App\Models\User;
use App\Observers\SubscriptionObserver;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {              
        User::observe(UserObserver::class);        
    }
}
