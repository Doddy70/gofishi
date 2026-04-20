<!-- Airbnb Design Foundation -->
<style>
  :root {
    --airbnb-red: #FF385C;
    --airbnb-dark: #222222;
    --airbnb-gray: #717171;
    --airbnb-light-gray: #F7F7F7;
    --airbnb-border: #DDDDDD;
  }

  /* Global Typography Reset */
  body {
    font-family: 'Circular', -apple-system, BlinkMacSystemFont, Roboto, Helvetica Neue, sans-serif !important;
    color: var(--airbnb-dark);
    -webkit-font-smoothing: antialiased;
  }

  /* Standard Airbnb Components (Utility Classes) */
  .btn-airbnb {
    background-color: var(--airbnb-red) !important;
    color: white !important;
    font-weight: 600 !important;
    border-radius: 8px !important;
    padding: 12px 24px !important;
    transition: all 0.2s ease !important;
    border: none !important;
  }
  .btn-airbnb:hover {
    background-color: #E31C5F !important;
    transform: scale(1.02);
  }
  .btn-airbnb:active {
    transform: scale(0.98);
  }

  .card-airbnb {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
  }

  .input-airbnb {
    width: 100%;
    padding: 12px 16px;
    border-radius: 12px;
    border: 1px solid #B0B0B0;
    outline: none;
    transition: all 0.2s ease;
  }
  .input-airbnb:focus {
    border-color: black !important;
    box-shadow: 0 0 0 1px black inset !important;
  }

  /* Layout Helper */
  .container-airbnb {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px;
  }

  .scrollbar-hide::-webkit-scrollbar { display: none; }
  .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<!-- Bootstrap CSS (Legacy compatibility) -->
<link rel="stylesheet" href="{{ asset('assets/front/css/vendors/bootstrap.min.css') }}">
<!-- Fontawesome Icon CSS -->
<link rel="stylesheet" href="{{ asset('assets/front/fonts/fontawesome/css/all.min.css') }}">
<!-- Date-range Picker -->
<link rel="stylesheet" href="{{ asset('assets/front/css/vendors/daterangepicker.css') }}">
<!-- Data Tables -->
<link rel="stylesheet" href="{{ asset('assets/front/css/vendors/datatables.min.css') }}">
<!-- Noui Range Slider -->
<link rel="stylesheet" href="{{ asset('assets/front/css/vendors/nouislider.min.css') }}">
<!-- Magnific Popup CSS -->
<link rel="stylesheet" href="{{ asset('assets/front/css/vendors/magnific-popup.min.css') }}">
{{-- toastr --}}
<link rel="stylesheet" href="{{ asset('assets/admin/css/toastr.min.css') }}">
<!-- Swiper Slider -->
<link rel="stylesheet" href="{{ asset('assets/front/css/vendors/swiper-bundle.min.css') }}">
<!-- Nice Select -->
<link rel="stylesheet" href="{{ asset('assets/front/css/vendors/nice-select.css') }}">
<!-- Select 2 -->
<link rel="stylesheet" href="{{ asset('assets/front/css/vendors/select2.min.css') }}">
<!-- AOS Animation CSS -->
<link rel="stylesheet" href="{{ asset('assets/front/css/vendors/aos.min.css') }}">
{{-- whatsapp css --}}
<link rel="stylesheet" href="{{ asset('assets/front/css/floating-whatsapp.css') }}">
<!-- Animate CSS -->
<link rel="stylesheet" href="{{ asset('assets/front/css/vendors/animate.min.css') }}">
<!-- Leaflet Map CSS  -->
<link rel="stylesheet" href="{{ asset('assets/front/css/vendors/leaflet.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/front/css/vendors/MarkerCluster.css') }}">
<link rel="stylesheet" href="{{ asset('assets/front/css/vendors/leaflet.fullscreen.css') }}">
<!-- Tinymce-content CSS  -->
<link rel="stylesheet" href="{{ asset('assets/front/css/tinymce-content.css') }}">

<!-- Main Style CSS -->
<link rel="stylesheet" href="{{ asset('assets/front/css/base.css') }}">
<link rel="stylesheet" href="{{ asset('assets/front/css/header.css') }}">
<link rel="stylesheet" href="{{ asset('assets/front/css/footer.css') }}">

@if (!request()->routeIs('index'))
  <link rel="stylesheet" href="{{ asset('assets/front/css/inner-pages.css') }}">
@endif

<link rel="stylesheet" href="{{ asset('assets/front/css/style.css') }}">
<!-- Responsive CSS -->
<link rel="stylesheet" href="{{ asset('assets/front/css/responsive.css') }}">

{{-- rtl css are goes here --}}
@if ($currentLanguageInfo->direction == 1)
  <link rel="stylesheet" href="{{ asset('assets/front/css/rtl.css') }}">
@endif
@php
  $primaryColor = $basicInfo->primary_color;
  // check, whether color has '#' or not, will return 0 or 1
  if (!function_exists('checkColorCode')) {
    function checkColorCode($color)
    {
        return preg_match('/^#[a-f0-9]{6}/i', $color);
    }
  }

  // if, primary color value does not contain '#', then add '#' before color value
  if (isset($primaryColor) && checkColorCode($primaryColor) == 0) {
      $primaryColor = '#' . $primaryColor;
  }

  // change decimal point into hex value for opacity
  if (!function_exists('rgb')) {
    function rgb($color = null)
    {
        if (!$color) {
            echo '';
        }
        $hex = htmlspecialchars($color);
        list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
        echo "$r, $g, $b";
    }
  }
@endphp
<style>
  :root {
    /* Core Component Spacing Tokens */
    --sp-1: 4px;
    --sp-2: 8px;
    --sp-3: 12px;
    --sp-4: 16px;
    --sp-6: 24px;
    --sp-8: 32px;

    /* Core Component Typography Tokens */
    --fs-xs: 12px;
    --fs-sm: 14px;
    --fs-md: 16px;
    --fs-lg: 18px;
    --fs-xl: 20px;
    --fs-2xl: 24px;

    /* Core Component Color Tokens */
    --text-primary: #222222;
    --text-secondary: #717171;
    --text-tertiary: #b0b0b0;
    --primary-500: #FF385C;
    --status-success: #00A699;
    
    /* Airbnb Brand Legacy */
    --color-primary: var(--primary-500) !important;
    --color-dark: var(--text-primary) !important;
    --border-light: #DDDDDD !important;
    --shadow-md: 0 6px 16px rgba(0,0,0,0.12) !important;
  }

  /* Core component classes */
  .v-stack { display: flex; flex-direction: column; }
  .h-stack { display: flex; flex-direction: row; }
  .gap-4 { gap: var(--sp-4); }
  .gap-8 { gap: var(--sp-8); }

  /* ================================================================
     AIRBNB PIXEL-PERFECT HEADER
     ================================================================ */
  .airbnb-header {
    background: #fff !important;
    border-bottom: 1px solid #ebebeb !important;
    position: sticky !important;
    top: 0;
    z-index: 1000;
  }

  .custom-container {
    max-width: 1280px !important;
    margin: 0 auto;
    padding: 0 24px !important;
  }

  .airbnb-nav-wrapper {
    height: 80px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 !important;
  }

  .airbnb-logo {
    height: 32px;
    object-fit: contain;
  }

  /* Search Pill */
  .airbnb-search-pill {
    display: flex;
    align-items: center;
    background: #fff;
    border: 1px solid #DDDDDD;
    border-radius: 40px;
    padding: 8px 8px 8px 24px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.08), 0 4px 12px rgba(0,0,0,0.05);
    cursor: pointer;
    transition: box-shadow 0.2s ease;
  }

  .airbnb-search-pill:hover {
    box-shadow: 0 2px 4px rgba(0,0,0,0.18);
  }

  .pill-btn {
    background: none;
    border: none;
    font-size: 14px;
    padding: 0 16px;
    outline: none;
  }

  .pill-divider {
    height: 24px;
    width: 1px;
    background: #DDDDDD;
  }

  .pill-search-icon {
    width: 32px;
    height: 32px;
    background: var(--color-primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
  }

  /* Right Items */
  .become-host-btn {
    font-size: 14px;
    font-weight: 600;
    color: #222222;
    padding: 10px 12px;
    border-radius: 20px;
    transition: background 0.2s ease;
  }

  .become-host-btn:hover {
    background: #f7f7f7;
  }

  .user-pill-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #fff;
    border: 1px solid #DDDDDD;
    padding: 5px 5px 5px 12px;
    border-radius: 30px;
    transition: box-shadow 0.2s ease;
  }

  .user-pill-btn:hover {
    box-shadow: 0 2px 4px rgba(0,0,0,0.18);
  }

  .user-avatar {
    font-size: 30px;
    color: #717171;
  }

  /* Category Slider */
  .category-slider-wrapper {
    background: #fff;
    box-shadow: 0 1px 2px rgba(0,0,0,0.08);
  }

  .category-slider {
    position: relative;
    display: flex;
    align-items: center;
    padding: 12px 0;
  }

  .category-items-container {
    display: flex;
    gap: 32px;
    overflow-x: auto;
    scroll-behavior: smooth;
    -ms-overflow-style: none;
    scrollbar-width: none;
  }

  .category-items-container::-webkit-scrollbar {
    display: none;
  }

  .category-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    min-width: fit-content;
    padding-bottom: 10px;
    border-bottom: 2px solid transparent;
    opacity: 0.6;
    transition: all 0.2s ease;
  }

  .category-item:hover {
    opacity: 1;
    border-bottom: 2px solid #DDDDDD;
  }

  .category-item.active {
    opacity: 1;
    border-bottom: 2px solid #222222;
  }

  .category-icon {
    font-size: 24px;
  }

  .category-label {
    font-size: 12px;
    font-weight: 600;
  }

  .scroll-btn {
    position: absolute;
    width: 28px;
    height: 28px;
    background: #fff;
    border: 1px solid #DDDDDD;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    z-index: 10;
    cursor: pointer;
  }

  .scroll-btn.prev { left: -14px; }
  .scroll-btn.next { right: -14px; }

  /* Design Spell: Bold Search/Filter Forms */
  .airbnb-filter-form, .sort-area, .location-group {
    background: #fff !important;
    border: 1px solid #DDDDDD !important;
    border-radius: 40px !important; /* Perfectly rounded like modern search bars */
    box-shadow: 0 3px 12px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.04) !important;
    transition: box-shadow 0.2s ease-in-out, transform 0.2s ease-in-out !important;
    padding: 8px 12px !important;
  }

  .airbnb-filter-form:hover, .sort-area:hover, .location-group:hover {
    box-shadow: 0 6px 20px rgba(0,0,0,0.12) !important;
    transform: translateY(-1px) !important;
  }

  /* Sidebar filter container */
  .sidebar-scroll {
    background: #fff !important;
    border-radius: 16px !important;
    box-shadow: 0 6px 16px rgba(0,0,0,0.1) !important;
    padding: 20px !important;
    border: 1px solid #f0f0f0 !important;
  }

  /* Input refinement */
  .location-group input {
    border: none !important;
    box-shadow: none !important;
    background: transparent !important;
    font-weight: 500 !important;
  }

  /* Design Spell: Card "Magic" interaction */
  .magic-card {
    transition: all 0.3s cubic-bezier(0.2, 0, 0, 1) !important;
    cursor: pointer;
  }
  
  .magic-img-wrapper {
    overflow: hidden;
    border-radius: 12px !important;
    background-color: #f7f7f7;
  }
  
  .card-img-slide {
    opacity: 0;
    transition: opacity 0.4s ease-in-out !important;
    z-index: 1;
  }
  
  .card-img-slide.active {
    opacity: 1 !important;
    z-index: 2;
  }

  /* Design Spell: Heart Icon animation */
  .magic-heart {
    transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
  }
  
  .magic-heart:hover {
    transform: scale(1.1);
  }
  
  .magic-heart:active {
    transform: scale(0.9);
  }

  /* Design Spell: Card Navigation Buttons */
  .magic-nav-btn {
    opacity: 0;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.9) !important;
    border: 1px solid rgba(0,0,0,0.08) !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    transition: opacity 0.2s ease, transform 0.2s ease !important;
  }
  
  .magic-nav-prev { left: 10px; }
  .magic-nav-next { right: 10px; }
  
  .magic-card:hover .magic-nav-btn {
    opacity: 1;
  }
  
  .magic-nav-btn:hover {
    background: white !important;
    transform: translateY(-50%) scale(1.05);
  }

  /* Design Spell: Carousel Dots */
  .magic-dots {
    position: absolute;
    bottom: 12px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 6px;
    z-index: 10;
  }
  
  .magic-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.6);
    transition: all 0.2s ease !important;
  }
  
  .magic-dot.active {
    background: white;
    transform: scale(1.2);
  }

  /* Button "Fluid" interaction */
  .btn-primary, .main-btn, .btn-reserve-main {
    background: linear-gradient(to right, #E61E4D 0%, #E31C5F 50%, #D70466 100%) !important;
    border: none !important;
    transition: transform 0.1s ease !important;
    font-weight: 600 !important;
    border-radius: 8px !important;
    padding: 12px 24px !important;
  }
  
  .btn-primary:active, .main-btn:active, .btn-reserve-main:active {
    transform: scale(0.96);
  }

  /* Airbnb Category Scroll Refinement */
  .category-item {
    opacity: 0.7;
    transition: all 0.2s ease !important;
    border-bottom: 2px solid transparent;
    padding-bottom: 10px;
  }
  
  .category-item:hover {
    opacity: 1;
    border-bottom: 2px solid #DDDDDD;
  }
  
  .category-item.active {
    opacity: 1;
    border-bottom: 2px solid var(--color-primary);
  }

  /* Detail Page Magic */
  .airbnb-listing-title {
    font-size: 26px !important;
    font-weight: 600 !important;
    color: #222222 !important;
    line-height: 30px !important;
  }

  .section-heading {
    font-size: 22px !important;
    font-weight: 600 !important;
    color: #222222 !important;
  }

  .airbnb-action-btn {
    padding: 8px !important;
    border-radius: 8px !important;
    background: transparent !important;
    border: none !important;
    text-decoration: underline !important;
    font-weight: 600 !important;
    font-size: 14px !important;
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
    transition: background 0.2s ease !important;
  }

  .airbnb-action-btn:hover {
    background: #f7f7f7 !important;
  }

  /* Booking Card Magic */
  .booking-card-airbnb {
    border: 1px solid #DDDDDD !important;
    border-radius: 12px !important;
    padding: 24px !important;
    box-shadow: rgba(0, 0, 0, 0.12) 0px 6px 16px !important;
    background: white !important;
  }

  .booking-input-group:focus-within {
    box-shadow: inset 0 0 0 2px #222222 !important;
    border-radius: 8px !important;
    z-index: 10 !important;
  }

  .btn-reserve-nav {
    background: linear-gradient(to right, #E61E4D 0%, #E31C5F 50%, #D70466 100%) !important;
    color: white !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 10px 24px !important;
    font-weight: 600 !important;
    transition: transform 0.1s ease !important;
  }

  .btn-reserve-nav:active {
    transform: scale(0.96) !important;
  }

  /* Sticky Nav Magic */
  .detail-sticky-nav {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background: white !important;
    border-bottom: 1px solid #DDDDDD !important;
    z-index: 1000 !important;
    transform: translateY(-100%);
    transition: transform 0.3s cubic-bezier(0.45, 0, 0.55, 1) !important;
  }

  .detail-sticky-nav.visible {
    transform: translateY(0);
  }

  /* Global Dropdown Z-index Fix */
  .location-dropdown, .guest-dropdown, .daterangepicker {
    z-index: 10000 !important;
  }

/* ================================================================
   MOBILE PREMIUM SEARCH UI (Airbnb Style)
   ================================================================ */
@media (max-width: 767px) {
  /* Hide the actual bar initially on mobile */
  .airbnb-search-bar {
    display: none !important;
  }

  /* Compact Pill */
  .mobile-search-pill {
    display: flex !important;
    align-items: center;
    background: #FFFFFF;
    border: 0.5px solid rgba(0, 0, 0, 0.08);
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    border-radius: 1000px;
    padding: 10px 16px;
    width: 90vw;
    margin: 0 auto;
    cursor: pointer;
    transition: transform 0.1s ease;
  }

  .mobile-search-pill:active {
    transform: scale(0.98);
  }

  .pill-icon {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #222222;
    margin-right: 12px;
  }

  .pill-content {
    display: flex;
    flex-direction: column;
  }

  .pill-title {
    font-size: 14px;
    font-weight: 600;
    color: #222222;
  }

  .pill-subtitle {
    font-size: 12px;
    color: #717171;
  }

  /* Full Screen Overlay */
  .search-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #F7F7F7;
    z-index: 99999;
    display: none;
    flex-direction: column;
    padding: 20px;
    overflow-y: auto;
  }

  .search-overlay.active {
    display: flex;
  }

  .overlay-close {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 1px solid #DDDDDD;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 24px;
  }

  .overlay-card {
    background: white;
    border-radius: 24px;
    padding: 20px;
    box-shadow: 0 6px 16px rgba(0,0,0,0.08);
    margin-bottom: 12px;
  }

  .overlay-card-title {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 16px;
    color: #222222;
  }

  .overlay-input-group {
    background: #FFFFFF;
    border: 1px solid #B0B0B0;
    border-radius: 12px;
    padding: 12px 16px;
    margin-bottom: 8px;
  }

  .overlay-label {
    font-size: 12px;
    font-weight: 800;
    color: #222222;
    text-transform: uppercase;
  }

  .overlay-field {
    border: none;
    width: 100%;
    font-size: 16px;
    outline: none;
    padding: 4px 0;
  }

  .overlay-footer {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: white;
    padding: 16px 24px;
    border-top: 1px solid #DDDDDD;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 100;
  }

  .btn-clear {
    text-decoration: underline;
    font-weight: 600;
    color: #222222;
    background: transparent;
    border: none;
  }

  .btn-search-overlay {
    background: linear-gradient(to right, #E61E4D 0%, #E31C5F 50%, #D70466 100%);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
  }
}

/* Hide pill on desktop */
@media (min-width: 768px) {
  .mobile-search-pill, .search-overlay {
    display: none !important;
  }
}
</style>
<style>
/* ================================================================
     POV FEATURE: AIRBNB MASTER-CARD (Oceanic Luxury Minimal)
     ================================================================ */
  
  .airbnb-master-card {
    transition: all 0.3s cubic-bezier(0.2, 0, 0, 1) !important;
    cursor: pointer;
  }

  .magic-img-wrapper {
    overflow: hidden;
    border-radius: 12px !important;
    position: relative;
    background-color: #f7f7f7;
  }

  .image-slides-link {
    display: block;
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
  }

  .card-img-slide {
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: absolute;
    top: 0;
    left: 0;
    opacity: 0;
    transition: opacity 0.4s ease-in-out !important;
    z-index: 1;
  }

  .card-img-slide.active {
    opacity: 1 !important;
    z-index: 2;
  }

  /* Wishlist Heart */
  .wishlist-action-btn {
    position: absolute;
    top: 12px;
    right: 12px;
    z-index: 10;
    background: transparent;
    border: none;
    padding: 0;
    cursor: pointer;
    transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  }

  .wishlist-action-btn:hover {
    transform: scale(1.1);
  }

  .wishlist-action-btn:active {
    transform: scale(0.9);
  }

  .wishlist-action-btn svg {
    height: 24px;
    width: 24px;
    fill: rgba(0, 0, 0, 0.5);
    stroke: white;
    stroke-width: 2px;
    overflow: visible;
  }

  .wishlist-action-btn.active svg {
    fill: var(--color-primary);
    stroke: var(--color-primary);
  }

  /* Navigation Arrows */
  .img-nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 30px;
    height: 30px;
    background: rgba(255, 255, 255, 0.9) !important;
    border: 1px solid rgba(0,0,0,0.08) !important;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    opacity: 0;
    transition: opacity 0.25s ease, transform 0.2s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }

  .airbnb-master-card:hover .img-nav-btn {
    opacity: 1;
  }

  .img-nav-btn:hover {
    background: white !important;
    transform: translateY(-50%) scale(1.05);
  }

  .img-nav-btn.prev { left: 8px; }
  .img-nav-btn.next { right: 8px; }

  /* Dots Indicator */
  .magic-dots {
    position: absolute;
    bottom: 12px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 6px;
    z-index: 10;
  }

  .magic-dot {
    width: 6px;
    height: 6px;
    background: rgba(255, 255, 255, 0.6);
    border-radius: 50%;
    transition: all 0.2s ease;
  }

  .magic-dot.active {
    background: white;
    transform: scale(1.3);
  }

  /* Typography Stack */
  .content-stack {
    padding-top: 4px;
  }

  .location-text {
    font-size: 15px;
    color: var(--color-dark);
  }

  .rating-badge {
    font-size: 14px;
    color: var(--color-dark);
  }

  .rating-badge i {
    font-size: 11px;
  }

  .boat-title, .boat-meta {
    font-size: 14px;
    color: var(--color-medium);
    line-height: 18px;
  }

  .price-line {
    font-size: 15px;
    color: var(--color-dark);
    margin-top: 2px;
  }

  .price-unit {
    font-weight: 400;
  }

  /* Reset for original theme compatibility */
  .header-next {
    margin-top: 0 !important;
  }
</style>
<style>
  /* ================================================================
     AIRBNB PIXEL-PERFECT HEADER & SUPER SEARCH
     ================================================================ */
  .airbnb-header {
    background: #fff !important;
    border-bottom: 1px solid var(--border-light) !important;
    position: fixed !important;
    top: 0;
    width: 100%;
    z-index: 1000;
    transition: all 0.3s cubic-bezier(0.2, 0, 0, 1) !important;
  }

  .airbnb-header.expanded {
    height: 160px !important;
  }

  .custom-container {
    max-width: 1280px !important;
    margin: 0 auto;
    padding: 0 24px !important;
  }

  .airbnb-nav-wrapper {
    height: 80px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 !important;
  }

  .airbnb-logo {
    height: 32px;
    object-fit: contain;
  }

  /* The Pill Trigger */
  .super-search-trigger {
    display: flex;
    align-items: center;
    background: #fff;
    border: 1px solid var(--border-light);
    border-radius: 40px;
    padding: 8px 8px 8px 24px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.08), 0 4px 12px rgba(0,0,0,0.05);
    cursor: pointer;
    transition: all 0.25s ease;
    max-width: fit-content;
    margin: 0 auto;
  }

  .super-search-trigger:hover {
    box-shadow: 0 2px 4px rgba(0,0,0,0.18);
  }

  .pill-segment {
    padding: 0 16px;
  }

  .pill-label {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary);
    white-space: nowrap;
  }

  .pill-search-icon {
    width: 32px;
    height: 32px;
    background: var(--primary-500);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
  }

  /* EXPANDED PANEL */
  .search-expansion-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.25);
    z-index: 999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    backdrop-filter: blur(4px);
  }

  .search-expansion-overlay.active {
    opacity: 1;
    visibility: visible;
  }

  .search-panel-container {
    position: absolute;
    top: 80px;
    left: 50%;
    transform: translateX(-50%) translateY(-20px);
    width: 100%;
    max-width: 850px;
    background: #fff;
    border-radius: 40px;
    box-shadow: 0 32px 64px rgba(0,0,0,0.2);
    transition: all 0.3s cubic-bezier(0.2, 0, 0, 1);
    opacity: 0;
  }

  .search-expansion-overlay.active .search-panel-container {
    transform: translateX(-50%) translateY(0);
    opacity: 1;
  }

  .search-panel-form {
    display: flex;
    align-items: center;
    background: #F7F7F7;
    border-radius: 40px;
    border: 1px solid var(--border-light);
  }

  .search-segment {
    flex: 1;
    padding: 14px 32px;
    border-radius: 40px;
    cursor: pointer;
    transition: background 0.2s ease;
    position: relative;
  }

  .search-segment:hover {
    background: #EBEBEB;
  }

  .search-segment.active {
    background: #fff !important;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
  }

  .segment-title {
    display: block;
    font-size: 12px;
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 2px;
    text-transform: none;
  }

  .segment-input {
    border: none;
    background: transparent;
    width: 100%;
    font-size: 14px;
    color: var(--text-secondary);
    outline: none;
    padding: 0;
  }

  .segment-divider {
    width: 1px;
    height: 32px;
    background: var(--border-light);
  }

  .expanded-search-btn {
    background: linear-gradient(to right, #FF385C 0%, #D70466 100%) !important;
    color: #fff !important;
    border: none !important;
    border-radius: 30px !important;
    padding: 14px 24px !important;
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    transition: transform 0.1s ease !important;
    margin-right: 8px;
  }
  
  /* Reset for original theme compatibility */
  .header-next {
    margin-top: 0 !important;
  }
</style>
@yield('style')
