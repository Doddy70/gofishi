<?php

namespace App\Providers;

use App\Http\View\Composers\GlobalComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        // Using a composer to share data across all frontend views
        View::composer('frontend.*', GlobalComposer::class);
        View::composer('vendors.*', GlobalComposer::class);
    }
}
