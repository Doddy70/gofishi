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

  {{-- Web Manifest --}}
  <link rel="manifest" href="{{ asset('manifest.json') }}">
  <meta name="theme-color" content="#FF385C">

  {{-- Tailwind --}}
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'airbnb-red': '#FF385C',
            'airbnb-dark': '#222222',
            'airbnb-gray': '#717171',
          }
        }
      }
    }
  </script>

  {{-- FontAwesome --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

  {{-- Lucide Icons --}}
  <script src="https://unpkg.com/lucide@latest"></script>

  {{-- Alpine.js --}}
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  {{-- Flatpickr --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <style>
      [x-cloak] { display: none !important; }
      .no-scrollbar::-webkit-scrollbar { display: none; }
      .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
      
      /* Custom Datepicker Styling to match Airbnb */
      .flatpickr-calendar {
          border-radius: 28px !important;
          box-shadow: 0 6px 20px rgba(0,0,0,0.2) !important;
          border: 1px solid #ebebeb !important;
          padding: 10px !important;
      }
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

      {{-- AI Chat Assistant --}}
      @include('frontend.partials.ai-chat-bubble')
  </div>

  @yield('script')
  
  <script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
  </script>
</body>
</html>
