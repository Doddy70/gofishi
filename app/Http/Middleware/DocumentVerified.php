<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DocumentVerified
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
            $vendor = Auth::guard('vendor')->user();
            if ($vendor->document_verified != 1) {
                if ($request->ajax()) {
                    return response()->json(['error' => __('Please verify your documents to access this feature.')], 403);
                }
                Session::flash('warning', __('Please verify your documents to access this feature.'));
                return redirect()->route('vendor.dashboard');
            }
        }
        return $next($request);
    }
}
