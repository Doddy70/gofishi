<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
        using: function () {
            Route::middleware('web')
                ->namespace('App\Http\Controllers')
                ->group(base_path('routes/web.php'));

            Route::middleware('api')
                ->prefix('api')
                ->namespace('App\Http\Controllers')
                ->group(base_path('routes/api.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            } elseif ($request->is('vendor') || $request->is('vendor/*')) {
                return route('vendor.login');
            }
            return route('user.login');
        });

        $middleware->alias([
            'change.lang' => \App\Http\Middleware\ChangeLanguage::class,
            'admin.tier' => \App\Http\Middleware\CheckAdminTier::class,
            'adminLang' => \App\Http\Middleware\AdminLocale::class,
            'vendorLang' => \App\Http\Middleware\VendorLocal::class,
            'Demo' => \App\Http\Middleware\Demo::class,
            'permission' => \App\Http\Middleware\HasPermission::class,
            'user.email.verify' => \App\Http\Middleware\userEmailVerified::class,
            'email.verify' => \App\Http\Middleware\emailVerified::class,
            'document.verified' => \App\Http\Middleware\DocumentVerified::class,
            'packageLimitsCheck' => \App\Http\Middleware\CheckPackageLimits::class,
            'sanitize.html' => \App\Http\Middleware\SanitizeHtml::class,
            'Deactive' => \App\Http\Middleware\Deactive::class,
        ]);
        
        $middleware->validateCsrfTokens(except: [
            'perahu/booking/midtrans/webhook',
            'perahu/booking/xendit/webhook',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
