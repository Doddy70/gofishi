<!-- Header-area start -->
<header class="header-area header_v3 airbnb-header" id="mainHeader">
  
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
            <a href="{{ route('vendor.login') }}" class="become-host-btn d-none d-md-block">{{ __('Buka Perahu Anda') }}</a>
            
            <div class="language-selection mx-3 d-none d-md-block">
              <form action="{{ route('change_language') }}" method="GET">
                <select class="border-0 bg-transparent fw-bold text-dark" name="lang_code" onchange="this.form.submit()" style="cursor: pointer; outline: none; font-size:14px;">
                  @foreach ($allLanguageInfos as $languageInfo)
                    <option value="{{ $languageInfo->code }}" {{ $languageInfo->code == $currentLanguageInfo->code ? 'selected' : '' }}>
                      {{ strtoupper($languageInfo->code) }}
                    </option>
                  @endforeach
                </select>
              </form>
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
                  <li><a class="dropdown-item fw-bold" href="{{ route('user.login') }}">{{ __('Masuk') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('user.signup') }}">{{ __('Daftar') }}</a></li>
                  <div class="dropdown-divider"></div>
                  <li><a class="dropdown-item" href="{{ route('vendor.login') }}">{{ __('Login Vendor') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('faq') }}">{{ __('Pusat Bantuan') }}</a></li>
                @else
                  @if(Auth::guard('web')->check())
                    <li><a class="dropdown-item fw-bold" href="{{ route('user.dashboard') }}">{{ __('Profil Saya') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('user.wishlist.perahu') }}">{{ __('Wishlist') }}</a></li>
                  @endif
                  @if(Auth::guard('vendor')->check())
                    <li><a class="dropdown-item fw-bold" href="{{ route('vendor.dashboard') }}">{{ __('Dashboard Host') }}</a></li>
                  @endif
                  <div class="dropdown-divider"></div>
                  @if(Auth::guard('web')->check())
                    <li><a class="dropdown-item" href="{{ route('user.logout') }}">{{ __('Keluar') }}</a></li>
                  @else
                    <li><a class="dropdown-item" href="{{ route('vendor.logout') }}">{{ __('Keluar') }}</a></li>
                  @endif
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

  <!-- Category Slider (The ICONS) -->
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

<!-- Mobile Search Overlay (Airbnb Style) -->
<div id="mobileSearchOverlay" class="search-overlay d-lg-none">
  <div class="overlay-header">
    <button class="overlay-close" onclick="document.getElementById('mobileSearchOverlay').classList.remove('active')">
      <i class="fal fa-times"></i>
    </button>
  </div>
  <div class="overlay-body">
    <form action="{{ route('frontend.perahu') }}" method="GET">
      <div class="overlay-card">
        <h2 class="overlay-card-title">{{ __('Ke mana?') }}</h2>
        <div class="overlay-input-group">
          <label class="overlay-label">{{ __('Lokasi') }}</label>
          <input type="text" name="location" class="overlay-field" placeholder="{{ __('Cari dermaga atau perairan') }}">
        </div>
      </div>
      
      <div class="overlay-footer">
        <button type="button" class="btn-clear">{{ __('Hapus semua') }}</button>
        <button type="submit" class="btn-search-overlay">
          <i class="fal fa-search"></i> {{ __('Cari') }}
        </button>
      </div>
    </form>
  </div>
</div>

