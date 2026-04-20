<!DOCTYPE html>
@php
    $lang = $currentLanguageInfo ?? get_lang();
    $webInfo = $websiteInfo ?? bs();
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $lang->direction == 1 ? 'rtl' : '' }}">

<head>
  {{-- Meta Data --}}
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- Title --}}
  <title>@yield('pageHeading') {{ $webInfo->website_title ? '| ' . $webInfo->website_title : '' }}</title>

  @php
    $basicSettings = \App\Models\BasicSettings\Basic::select('pixel_status', 'pixel_id', 'google_analytics_status', 'google_analytics_id')->first();
  @endphp

  {{-- Meta Pixel --}}
  @if(isset($basicSettings) && $basicSettings->pixel_status == 1)
    <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '{{ $basicSettings->pixel_id }}');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id={{ $basicSettings->pixel_id }}&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Meta Pixel Code -->
  @endif

  {{-- Google Analytics --}}
  @if(isset($basicSettings) && $basicSettings->google_analytics_status == 1)
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $basicSettings->google_analytics_id }}"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', '{{ $basicSettings->google_analytics_id }}');
    </script>
  @endif

  {{-- Favicon --}}
  @if(!empty($webInfo->favicon))
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/' . $webInfo->favicon) }}">
  @endif

  {{-- Preload Critical Assets (LCP) --}}
  @if(!empty($webInfo->logo))
    <link rel="preload" as="image" href="{{ asset('assets/img/' . $webInfo->logo) }}" fetchpriority="high">
  @endif

  {{-- Web Manifest --}}
  <link rel="manifest" href="{{ asset('manifest.json') }}">
  <meta name="theme-color" content="#FF385C">

  {{-- Toastr CSS with Print optimization --}}
  <link rel="stylesheet" href="{{ asset('assets/admin/css/toastr.min.css') }}" media="all">

  <style>
    /* Font Display Swap for A11y & Performance */
    @font-face { font-display: swap; }
    img { content-visibility: auto; }
  </style>

  {{-- Vite Assets --}}
  @vite(['resources/js/app.js'])

  {{-- FontAwesome --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

  {{-- Flatpickr --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <style>
      [x-cloak] { display: none !important; }
      .no-scrollbar::-webkit-scrollbar { display: none; }
      .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
      
      /* Custom Datepicker Styling to match Airbnb */
      /* Airbnb Standard Calendar Styling */
      .flatpickr-calendar {
          background: #ffffff !important;
          border-radius: 32px !important;
          box-shadow: 0 0 0 1px rgba(0,0,0,0.04), 0 8px 28px rgba(0,0,0,0.2) !important;
          border: none !important;
          padding: 32px !important;
          width: auto !important;
          margin-top: 12px !important;
          font-family: inherit !important;
      }
      .flatpickr-months { padding: 0 !important; }
      .flatpickr-month { height: 48px !important; color: #222222 !important; }
      .flatpickr-current-month { font-weight: 600 !important; font-size: 16px !important; padding: 0 !important; }
      .flatpickr-weekday { font-weight: 600 !important; color: #717171 !important; font-size: 12px !important; }
      .flatpickr-innerContainer { padding: 0 !important; margin-top: 10px !important; }
      .flatpickr-day { 
          border-radius: 50% !important; 
          font-weight: 600 !important;
          height: 40px !important;
          line-height: 40px !important;
          width: 40px !important;
          margin: 1px !important;
          font-size: 14px !important;
      }
      .flatpickr-day.selected { background: #222222 !important; border-color: #222222 !important; }
      .flatpickr-day:hover { background: #f7f7f7 !important; border-color: transparent !important; }
      .flatpickr-day.today { border-color: transparent !important; text-decoration: underline !important; }
      

  </style>
</head>

<body class="antialiased font-sans text-airbnb-dark bg-white min-h-screen">
  
  <div id="app" class="relative flex flex-col min-h-screen">
      
      {{-- Header (Ported from Header.tsx) --}}
      @include('frontend.partials.navbar-airbnb')

      {{-- Main Content --}}
      <main class="flex-grow">
        @yield('content')
      </main>

      {{-- Footer --}}
      @include('frontend.partials.footer')

      {{-- AI Chat Assistant (Homepage only) --}}
      @if(Route::currentRouteName() === 'index')
        @include('frontend.partials.ai-chat-bubble')
      @endif
  </div>

  @yield('script')

  {{-- Core Scripts: jQuery & Toastr (Required for notifications) --}}
  <script src="{{ asset('assets/front/js/vendors/jquery.min.js') }}" defer></script>
  <script src="{{ asset('assets/admin/js/toastr.min.js') }}" defer></script>

  {{-- Alpine.js & Lucide (Required for Airbnb UI logic) --}}
  <script src="https://unpkg.com/lucide@latest" defer></script>
  <script defer src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  {{-- Session Flash Notifications --}}
  @if (session()->has('success'))
    <script>toastr['success']("{{ __(session('success')) }}");</script>
  @endif
  @if (session()->has('error'))
    <script>toastr['error']("{{ __(session('error')) }}");</script>
  @endif

  <script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
  </script>
</body>
</html>
