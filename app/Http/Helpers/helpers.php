<?php

use App\Models\BasicSettings\Basic;
use App\Models\Language;
use Illuminate\Support\Facades\Session;

if (!function_exists('bs')) {
    function bs() {
        return Basic::where('uniqid', 12345)->first();
    }
}

if (!function_exists('get_lang')) {
    function get_lang() {
        $language = null;
        if (Session::has('currentLocaleCode')) {
            $locale = Session::get('currentLocaleCode');
            $language = Language::where('code', $locale)->first();
        }
        
        if (!$language) {
            $language = Language::where('is_default', 1)->first();
        }
        
        if (!$language) {
            $language = Language::first();
        }
        
        return $language;
    }
}
