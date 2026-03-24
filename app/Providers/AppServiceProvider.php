<?php

namespace App\Providers;

use App\Http\Helpers\VendorPermissionHelper;
use Illuminate\Support\Facades\Schema;
use App\Models\BasicSettings\SocialMedia;
use App\Models\HomePage\Section;
use App\Models\Language;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    $this->app->bind(
      \App\Contracts\Messaging\WhatsAppProvider::class,
      \App\Services\Messaging\FonnteProvider::class
    );
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    Paginator::useBootstrap();
    
    if (str_starts_with(config('app.url'), 'https://')) {
      URL::forceScheme('https');
    }

    // ULTRA-SAFE BOOT: Prevents 500 error if database is missing or incomplete
    if (!app()->runningInConsole()) {
      
      // 1. Basic Settings Shield
      $settings = null;
      if (Schema::hasTable('basic_settings')) {
          try {
              $settings = DB::table('basic_settings')->first();
          } catch (\Exception $e) {}
      }

      // 2. Languages Shield
      $langs = collect([]);
      $defaultLang = (object) ['id' => 1, 'code' => 'en', 'name' => 'English', 'is_default' => 1]; // Dummy fallback
      
      if (Schema::hasTable('languages')) {
          try {
              $langs = Language::all();
              $foundDefault = Language::where('is_default', 1)->first() ?? Language::first();
              if ($foundDefault) {
                  $defaultLang = $foundDefault;
              }
          } catch (\Exception $e) {}
      }

      // 3. Global Share (Basic)
      View::share([
          'settings' => $settings, 
          'websiteInfo' => $settings, // Compatibility with some older views
          'langs' => $langs, 
          'defaultLang' => $defaultLang
      ]);

      // 4. View Composers for contextual data
      View::composer(['admin.*', 'vendors.*', 'frontend.*'], function ($view) use ($defaultLang, $settings, $langs) {
          
          // Re-inject for safety in case some controller overwrites the array
          $view->with('defaultLang', $defaultLang);
          $view->with('langs', $langs);
          $view->with('settings', $settings);

          // Menus Logic (Mainly for Frontend)
          $menuData = null;
          if ($defaultLang && Schema::hasTable('menu_builders')) {
              try {
                  $menuBuilder = DB::table('menu_builders')->where('language_id', $defaultLang->id)->first();
                  if ($menuBuilder && !empty($menuBuilder->menus)) {
                      $menuData = json_decode($menuBuilder->menus, true);
                      
                      // Process URLs through get_href if href is empty (dynamic menus)
                      foreach ($menuData as &$m) {
                          if (empty($m['href'])) $m['href'] = get_href($m);
                          if (!empty($m['children'])) {
                              foreach ($m['children'] as &$c) {
                                  if (empty($c['href'])) $c['href'] = get_href($c);
                              }
                          }
                      }
                  }
              } catch (\Exception $e) {}
          }

          // Fallback Menu if DB is empty or for safety
          if (empty($menuData)) {
              $menuData = [
                  ['text' => __('Home'), 'href' => route('index'), 'target' => '_self'],
                  ['text' => __('Perahu'), 'href' => route('frontend.perahu'), 'target' => '_self'],
                  ['text' => __('Dermaga'), 'href' => route('frontend.lokasi'), 'target' => '_self'],
                  ['text' => __('Artikel'), 'href' => route('frontend.blogs'), 'target' => '_self'],
                  ['text' => __('FAQ'), 'href' => route('faq'), 'target' => '_self'],
              ];
          }

          $footerTextInfo = null;
          if ($defaultLang && Schema::hasTable('footer_texts')) {
              try {
                  $footerTextInfo = DB::table('footer_texts')->where('language_id', $defaultLang->id)->first();
              } catch (\Exception $e) {}
          }

          // Dynamic Quick Links (Integrated with Footer)
          $quickLinks = collect([]);
          if ($defaultLang && Schema::hasTable('quick_links')) {
              try {
                  $quickLinks = DB::table('quick_links')->where('language_id', $defaultLang->id)->orderBy('serial_number', 'asc')->get();
              } catch (\Exception $e) {}
          }

          // Dynamic Custom Pages (Integrated with Footer)
          $supportPages = collect([]);
          $companyPages = collect([]);
          if ($defaultLang && Schema::hasTable('page_contents')) {
              try {
                  $allPages = DB::table('page_contents')->where('language_id', $defaultLang->id)->get();
                  $supportTitles = ['Pusat Bantuan', 'Privacy Policy', 'Dukungan & Bantuan', 'Kebijakan Pembatalan'];
                  $supportPages = $allPages->filter(fn($p) => in_array($p->title, $supportTitles));
                  $companyPages = $allPages->filter(fn($p) => !in_array($p->title, $supportTitles));
              } catch (\Exception $e) {}
          }
          
          $view->with('footerTextInfo', $footerTextInfo);
          $view->with('menuData', $menuData);
          $view->with('quickLinks', $quickLinks);
          $view->with('supportPages', $supportPages);
          $view->with('companyPages', $companyPages);

          // Theme version for Admin
          if ($settings && !isset($settings->admin_theme_version)) {
              $settings->admin_theme_version = 'light';
          }
      });
    }
  }
}
