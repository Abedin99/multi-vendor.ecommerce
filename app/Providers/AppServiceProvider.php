<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // dynamically set the rootview based on whether the route is backend or frontend 
        // can also be done in a middleware that wraps all admin routes

        if(request()->is('backend/*') or request()->is('backend')){
            View::getFinder()->setPaths([resource_path('backend')]);
        }

        if(request()->is('merchant/*') or request()->is('merchant')){
            View::getFinder()->setPaths([resource_path('merchant')]);
        }

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
