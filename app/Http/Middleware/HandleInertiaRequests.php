<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\Auth;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
                'vendor' => Auth::guard('vendor')->user(),
                'admin' => Auth::guard('admin')->user(),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'warning' => fn () => $request->session()->get('warning'),
            ],
            'basicInfo' => bs(),
            'currentLanguage' => get_lang(),
            'categories' => \App\Models\RoomCategory::where('language_id', get_lang()->id)
                ->where('status', 1)
                ->orderBy('serial_number', 'asc')
                ->get()
                ->map(function($cat) {
                    return [
                        'id' => $cat->id,
                        'label' => $cat->name,
                        'name' => $cat->name,
                        'icon' => 'fa-solid fa-ship'
                    ];
                }),
        ]);
    }
}
