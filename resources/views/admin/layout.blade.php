<!DOCTYPE html>
<html dir="{{ $defaultLang->direction == 1 ? 'rtl' : '' }}">

<head>
  {{-- required meta tags --}}
  <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

  {{-- csrf-token for ajax request --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- title --}}
  <title>{{ __('Admin') . ' | ' . $websiteInfo->website_title }}</title>

  {{-- fav icon --}}
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}">

  {{-- include styles --}}
  @includeIf('admin.partials.styles')

  <style>
      /* Modern Admin Dashboard Overrides */
      :root {
          --primary-color: #FF385C;
          --sidebar-bg: #ffffff;
          --content-bg: #f8f9fa;
      }
      .sidebar {
          border-right: 1px solid #edf2f7 !important;
          box-shadow: none !important;
      }
      .sidebar .nav-item a {
          padding: 12px 25px !important;
          border-radius: 8px !important;
          margin: 4px 15px !important;
          font-weight: 500 !important;
          color: #4a5568 !important;
      }
      .sidebar .nav-item.active a {
          background: rgba(255, 56, 92, 0.08) !important;
          color: var(--primary-color) !important;
      }
      .sidebar .nav-item.active i {
          color: var(--primary-color) !important;
      }
      .main-panel {
          background-color: var(--content-bg) !important;
      }
      .card {
          border: 1px solid #edf2f7 !important;
          border-radius: 12px !important;
          box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
      }
      .navbar-header {
          border-bottom: 1px solid #edf2f7 !important;
          box-shadow: none !important;
      }
      .btn-primary {
          background-color: var(--primary-color) !important;
          border-color: var(--primary-color) !important;
          border-radius: 8px !important;
      }
  </style>

  {{-- additional style --}}
  @yield('style')
</head>

<body data-background-color="{{ ($settings->admin_theme_version ?? 'light') == 'light' ? 'white' : 'dark' }}"
  dir="{{ optional($defaultLang)->direction == 1 ? 'rtl' : '' }}">
  {{-- loader start --}}
  <div class="request-loader">
    <img src="{{ asset('assets/img/loader.gif') }}" alt="loader">
  </div>
  {{-- loader end --}}

  <div class="wrapper">
    {{-- top navbar area start --}}
    @includeIf('admin.partials.top-navbar')
    {{-- top navbar area end --}}

    {{-- side navbar area start --}}
    @includeIf('admin.partials.side-navbar')
    {{-- side navbar area end --}}

    <div class="main-panel">
      <div class="content">
        <div class="page-inner">
          @yield('content')
        </div>
      </div>

      {{-- footer area start --}}
      @includeIf('admin.partials.footer')
      {{-- footer area end --}}
    </div>
  </div>

  {{-- include scripts --}}
  @includeIf('admin.partials.scripts')

  {{-- additional script --}}
  @yield('variables')
  @yield('script')
</body>

</html>
