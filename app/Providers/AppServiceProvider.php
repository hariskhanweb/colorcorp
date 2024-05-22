<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        //
        // if (env('APP_ENV') === 'production') { //so you can work on it locally
        //     \Illuminate\Support\Facades\URL::forceScheme('https');
        // }
    }
}
