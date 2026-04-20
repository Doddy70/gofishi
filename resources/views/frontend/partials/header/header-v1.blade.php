<!-- Header-area start -->
<header class="header-area header_v1 airbnb-header" id="mainHeader">
  
  <div class="main-navbar">
    <div class="container custom-container">
      <nav class="navbar navbar-expand-lg airbnb-nav-wrapper">
        <!-- 1. Logo (Left) -->
        <div class="nav-left">
          <a class="navbar-brand" href="{{ route('index') }}">
            <img src="{{ asset('assets/img/' . $websiteInfo->logo) }}" alt="logo" class="airbnb-logo">
          </a>
        </div>

        <!-- 2. The POV Feature: Super Search Pill -->
        <div class="nav-center">
          <div class="super-search-trigger" id="searchTrigger" onclick="expandSearch()">
            <div class="pill-segment border-end">
              <span class="pill-label">{{ __('Ke mana saja') }}</span>
            </div>
            <div class="pill-segment border-end">
              <span class="pill-label">{{ __('Minggu mana pun') }}</span>
            </div>
            <div class="pill-segment">
              <span class="pill-label text-muted">{{ __('Tambah tamu') }}</span>
            </div>
            <div class="pill-search-icon">
              <i class="fal fa-search"></i>
            </div>
          </div>
        </div>

        <!-- 3. User Menu (Right) -->
        <div class="nav-right">
          <div class="right-items d-flex align-items-center">
            <a href="{{ route('vendor.login') }}" class="become-host-btn d-none d-md-block">{{ __('Jadilah Host') }}</a>
            <div class="global-icon mx-3 d-none d-md-block">
              <i class="fal fa-globe"></i>
            </div>
            
            <div class="user-menu-dropdown dropdown">
              <button class="user-pill-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fal fa-bars"></i>
                <div class="user-avatar">
                  <i class="fas fa-user-circle"></i>
                </div>
              </button>
              <ul class="dropdown-menu dropdown-menu-end airbnb-dropdown">
                @if (!Auth::guard('web')->check() && !Auth::guard('vendor')->check())
                  <li><a class="dropdown-item fw-bold" href="{{ route('user.login') }}">{{ __('Login') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('user.signup') }}">{{ __('Daftar') }}</a></li>
                  <div class="dropdown-divider"></div>
                  <li><a class="dropdown-item" href="{{ route('vendor.login') }}">{{ __('Jadilah Host') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('frontend.custom_page', ['slug' => 'pusat-bantuan']) }}">{{ __('Pusat Bantuan') }}</a></li>
                @else
                  @if(Auth::guard('web')->check())
                    <li><a class="dropdown-item fw-bold" href="{{ route('user.dashboard') }}">{{ __('Profil Saya') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('user.wishlist.perahu') }}">{{ __('Wishlist') }}</a></li>
                  @endif
                  @if(Auth::guard('vendor')->check())
                    <li><a class="dropdown-item fw-bold" href="{{ route('vendor.dashboard') }}">{{ __('Dashboard Host') }}</a></li>
                  @endif
                  <div class="dropdown-divider"></div>
                  <li><a class="dropdown-item" href="{{ route('user.logout') }}">{{ __('Keluar') }}</a></li>
                @endif
              </ul>
            </div>
          </div>
        </div>
      </nav>
    </div>
  </div>

  <!-- EXPANDED SEARCH PANEL (The POV Masterpiece) -->
  <div class="search-expansion-overlay" id="searchOverlay" onclick="closeSearch(event)">
    <div class="search-panel-container" onclick="event.stopPropagation()">
      <form action="{{ route('frontend.perahu') }}" method="GET" class="search-panel-form">
        <!-- Segment: Destination -->
        <div class="search-segment first" onclick="focusInput('dest')">
          <label class="segment-title">{{ __('Lokasi') }}</label>
          <input type="text" name="location" id="destInput" placeholder="{{ __('Cari dermaga...') }}" class="segment-input">
        </div>
        
        <div class="segment-divider"></div>

        <!-- Segment: Date -->
        <div class="search-segment" onclick="focusInput('date')">
          <label class="segment-title">{{ __('Waktu') }}</label>
          <input type="text" name="checkInDates" id="dateInput" placeholder="{{ __('Tambah tanggal') }}" class="segment-input" readonly>
        </div>

        <div class="segment-divider"></div>

        <!-- Segment: Guests -->
        <div class="search-segment last" onclick="focusInput('guests')">
          <div class="segment-content">
            <label class="segment-title">{{ __('Tamu') }}</label>
            <input type="text" placeholder="{{ __('Tambah tamu') }}" class="segment-input" readonly>
          </div>
          <button type="submit" class="expanded-search-btn">
            <i class="fal fa-search"></i>
            <span>{{ __('Cari') }}</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Category Slider -->
  <div class="category-slider-wrapper">
    <div class="container custom-container">
      <div class="category-slider">
        <div class="category-items-container" id="categoryScroll">
          @php $categories = App\Models\RoomCategory::where('status', 1)->get(); @endphp
          @foreach($categories as $category)
            <a href="{{ route('frontend.perahu', ['category' => $category->slug]) }}" class="category-item {{ request()->input('category') == $category->slug ? 'active' : '' }}">
              <div class="category-icon">
                <i class="{{ $category->icon ?? 'fal fa-ship' }}"></i>
              </div>
              <span class="category-label">{{ $category->name }}</span>
            </a>
          @endforeach
        </div>
        <button class="scroll-btn prev" onclick="scrollCategories(-200)"><i class="fal fa-chevron-left"></i></button>
        <button class="scroll-btn next" onclick="scrollCategories(200)"><i class="fal fa-chevron-right"></i></button>
      </div>
    </div>
  </div>
</header>
