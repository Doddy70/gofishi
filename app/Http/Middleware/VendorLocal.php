<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class VendorLocal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('vendor')->check()) {
            $locale = Auth::guard('vendor')->user()->lang_code;
        }

        $locale = isset($locale) ? trim($locale) : null;
        if (!empty($locale) && str_starts_with($locale, 'admin_')) {
            $locale = substr($locale, strlen('admin_'));
        }

        if (empty($locale)) {
            // set the default language as system locale
            $locale = Language::query()->where('is_default', '=', 1)
                ->pluck('code')
                ->first();
        }

        $locale = is_string($locale) ? trim($locale) : $locale;
        if (!empty($locale) && str_starts_with($locale, 'admin_')) {
            $locale = substr($locale, strlen('admin_'));
        }

        // set the selected language as system locale
        App::setLocale($locale);


        return $next($request);
    }
}
