<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class Demo
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
        if (env('DEMO_MODE') == 'active') {
            if (
                $request->isMethod('POST') ||
                $request->isMethod('PUT') ||
                Route::is('frontend.perahu.remove_wishlist')||
                Route::is('frontend.lokasi.add_wishlist')||
                Route::is('frontend.lokasi.remove_wishlist')||
                Route::is('frontend.perahu.add_wishlist')
            ) {
                session()->flash('warning', 'This is Demo version. You can not change anything.');
                return redirect()->back();
            }
        }
        return $next($request);
    }
}
