<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Mews\Purifier\Facades\Purifier;

class SanitizeHtml
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();

        // Fields to sanitize across all languages
        foreach ($input as $key => $value) {
            if (is_string($value) && (str_ends_with($key, '_description') || $key === 'other_features' || $key === 'review')) {
                $input[$key] = Purifier::clean($value, 'youtube');
            }
        }

        $request->merge($input);

        return $next($request);
    }
}
