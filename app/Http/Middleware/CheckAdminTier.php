<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdminTier
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $tier
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $tier)
    {
        $admin = auth('admin')->user();

        if (!$admin) {
            return redirect()->route('admin.login');
        }

        $roleName = $admin->role ? $admin->role->name : null;

        // Tier 1: Super Admin only
        if ($tier == '1' && !str_contains($roleName, 'Admin 1')) {
            abort(403, 'Akses Super Admin (Tier 1) diperlukan.');
        }

        // Tier 2: Manager and above
        if ($tier == '2' && !str_contains($roleName, 'Admin 1') && !str_contains($roleName, 'Admin 2')) {
            abort(403, 'Akses Manager (Tier 2) diperlukan.');
        }

        // Tier 3: Staff and above
        if ($tier == '3' && !str_contains($roleName, 'Admin 1') && !str_contains($roleName, 'Admin 2') && !str_contains($roleName, 'Admin 3')) {
            abort(403, 'Akses Staff (Tier 3) diperlukan.');
        }

        return $next($request);
    }
}
