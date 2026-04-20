@php
    $lang = $currentLanguageInfo ?? get_lang();
    $user = Auth::guard('web')->user();
    $isAuthenticated = Auth::guard('web')->check();
    // Same-app return after login (Airbnb-style redirect_url)
    $navReturnPath = request()->getRequestUri();
    if (str_starts_with($navReturnPath, '/user/login') || str_starts_with($navReturnPath, '/user/signup')) {
        $navReturnPath = '/';
    }
    $loginWithRedirect = route('user.login', ['redirect_url' => $navReturnPath]);
    $signupWithRedirect = route('user.signup', ['redirect_url' => $navReturnPath]);
@endphp

<header
  class="sticky top-0 bg-white border-b border-gray-200 shadow-sm"
  :style="showUserMenu ? 'z-index: 9999' : 'z-index: 50'"
  x-data="{ showUserMenu: false }"
  @keydown.escape.window="showUserMenu = false"
>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center h-16 sm:h-20">
      {{-- Logo --}}
      <a href="{{ route('index') }}" class="flex items-center min-w-0 shrink">
        @if(!empty($websiteInfo->logo))
            <img src="{{ asset('assets/img/' . $websiteInfo->logo) }}" alt="logo" class="h-8 w-auto" width="120" height="32" fetchpriority="high">
        @else
            <svg class="h-8 w-8 text-airbnb-red shrink-0" viewBox="0 0 32 32" fill="currentColor">
              <path d="M16 1c2 0 3.46 1.5 3.46 3.8 0 2.6-2.5 6-3.46 7.3-.96-1.3-3.46-4.7-3.46-7.3C12.54 2.5 14 1 16 1zm0 14.5c4.5 0 8.5 1.5 11.5 4C29 21 30 23 30 25.5c0 2.8-2.2 5-5 5H7c-2.8 0-5-2.2-5-5 0-2.5 1-4.5 2.5-6 3-2.5 7-4 11.5-4z" />
            </svg>
        @endif
        <span class="ml-2 text-xl font-bold text-airbnb-red truncate">{{ $websiteInfo->website_title ?? 'GoFishi' }}</span>
      </a>

      {{-- Center Navigation — Desktop (Hidden in User Dashboard Portal) --}}
      @if(!request()->is('user*'))
      <nav class="hidden lg:flex items-center space-x-1 xl:space-x-2 flex-1 justify-center max-w-2xl mx-4">
        @if (!empty($menuData))
            @foreach ($menuData as $menu)
                @if (isset($menu['children']) && count($menu['children']) > 0)
                    <div class="relative" x-data="{ open: false }" @mouseover="open = true" @mouseleave="open = false">
                        <button type="button" class="px-3 xl:px-4 py-2 text-[15px] font-semibold text-gray-700 hover:bg-gray-100 rounded-full transition flex items-center gap-1">
                            {{ $menu['text'] }}
                            <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" x-cloak x-transition
                             class="absolute left-0 mt-1 w-52 bg-white rounded-xl shadow-xl py-2 border border-gray-100 z-50">
                            @foreach ($menu['children'] as $child)
                                <a href="{{ $child['href'] }}" target="{{ $child['target'] ?? '_self' }}"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                    {{ $child['text'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ $menu['href'] }}" target="{{ $menu['target'] ?? '_self' }}"
                       class="px-3 xl:px-4 py-2 text-[15px] font-semibold text-gray-700 hover:bg-gray-100 rounded-full transition whitespace-nowrap">
                        {{ $menu['text'] }}
                    </a>
                @endif
            @endforeach
        @endif
      </nav>
      @else
      {{-- Empty flex space to maintain logo-right profile balance on Dashboard --}}
      <div class="flex-1"></div>
      @endif

      {{-- Right: Host + Globe + Airbnb-style profile pill --}}
      <div class="flex items-center gap-2 sm:gap-3 shrink-0">
        <a
          href="{{ route('frontend.host.landing') }}"
          class="hidden md:inline-flex text-sm font-semibold text-gray-700 hover:bg-gray-100 px-3 py-2 rounded-full transition whitespace-nowrap"
        >
          {{ __('Jadi Host') }}
        </a>

        {{-- Bantuan / bahasa (disamakan ke FAQ + kontak — bisa diganti menu builder nanti) --}}
        <a
          href="{{ route('frontend.custom_page', ['slug' => 'pusat-bantuan']) }}"
          class="hidden sm:flex items-center justify-center w-10 h-10 rounded-full border border-transparent hover:border-gray-200 hover:bg-gray-50 transition"
          title="{{ __('Pusat bantuan') }}"
        >
          <i data-lucide="globe" class="w-[18px] h-[18px] text-gray-700"></i>
        </a>

        <div class="relative">
          <button
            type="button"
            @click="showUserMenu = !showUserMenu"
            @click.outside="showUserMenu = false"
            class="flex items-center gap-2 sm:gap-3 pl-3 sm:pl-4 pr-2 sm:pr-3 py-2 border border-gray-300 rounded-full hover:shadow-md transition bg-white"
            :aria-expanded="showUserMenu"
            aria-haspopup="true"
          >
            <i data-lucide="menu" class="w-4 h-4 text-gray-700"></i>
            @if($isAuthenticated && $user->image)
              <img
                src="{{ asset('assets/img/users/' . $user->image) }}"
                alt="{{ $user->username }}"
                class="w-7 h-7 rounded-full object-cover border border-gray-200"
                width="28" height="28"
              />
            @else
              <i data-lucide="user-circle" class="w-7 h-7 text-gray-500"></i>
            @endif
          </button>

          {{-- Backdrop (mobile) --}}
          <div
            x-show="showUserMenu"
            x-transition.opacity
            x-cloak
            @click="showUserMenu = false"
            class="fixed inset-0 bg-black/30 lg:hidden"
            style="z-index: 9998;"
          ></div>

          {{-- Pure Dropdown System --}}
          <div
            x-show="showUserMenu"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 lg:translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 lg:translate-y-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            {{-- Desktop: 380px | Mobile: Flexible --}}
            class="absolute right-0 top-full mt-3 bg-white rounded-[24px] shadow-[0_12px_44px_rgba(0,0,0,0.22)] border border-gray-100 z-[9999] overflow-hidden py-2"
            style="width: 380px !important; max-width: calc(100vw - 2rem); min-width: 280px;"
          >
            {{-- 1. Help Center --}}
            <a href="{{ route('frontend.custom_page', ['slug' => 'pusat-bantuan']) }}" 
               class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 transition">
              <i data-lucide="help-circle" class="w-5 h-5 text-gray-700"></i>
              <span class="text-[15px] font-medium text-gray-900">{{ __('Pusat Bantuan') }}</span>
            </a>

            <div class="h-[1px] bg-gray-100 mx-6"></div>

            {{-- 2. Become a Host Card --}}
            <div class="px-2">
              <a href="{{ route('frontend.host.landing') }}" 
                 class="relative block p-6 hover:bg-gray-50 rounded-[20px] transition group">
                <div class="flex justify-between items-start gap-4">
                  <div class="flex-1 pr-12">
                    <h4 class="text-[16px] font-bold text-gray-900 mb-1 leading-tight">{{ __('Menjadi Tuan Rumah') }}</h4>
                    <p class="text-[13px] text-gray-500 leading-relaxed">
                      {{ __('Anda bisa mulai menerima tamu dengan mudah dan mendapatkan penghasilan tambahan.') }}
                    </p>
                  </div>
                  <div class="absolute right-6 top-1/2 -translate-y-1/2 w-14 h-14">
                    <img src="{{ asset('assets/img/ai/host-illus.png') }}" 
                         alt="host" class="w-full h-full object-contain" width="56" height="56">
                  </div>
                </div>
              </a>
            </div>

            <div class="h-[1px] bg-gray-100 mx-6"></div>

            {{-- 3. Secondary Links --}}
            <div class="py-1">
              <a href="#" class="block px-6 py-3.5 text-[14px] font-medium text-gray-700 hover:bg-gray-50 transition">{{ __('Temukan rekan tuan rumah') }}</a>
              <a href="{{ route('contact') }}" class="block px-6 py-3.5 text-[14px] font-medium text-gray-700 hover:bg-gray-50 transition">{{ __('Hubungi kami') }}</a>
            </div>

            <div class="h-[1px] bg-gray-100 mx-6"></div>

            {{-- 4. Auth Section --}}
            <div class="pt-1">
              @if(!$isAuthenticated)
                <a href="{{ $loginWithRedirect }}" 
                   class="block px-6 py-4 text-[15px] font-bold text-gray-900 hover:bg-gray-50 transition">
                  {{ __('Masuk atau mendaftar') }}
                </a>
              @else
                <div class="px-6 py-3">
                  <p class="text-sm font-bold text-gray-900">{{ $user->name ?? $user->username }}</p>
                  <p class="text-xs text-gray-500">{{ $user->email }}</p>
                </div>
                <hr class="border-gray-50" />
                <a href="{{ route('user.dashboard') }}" class="block px-6 py-3 text-[14px] font-medium text-gray-700 hover:bg-gray-50 transition">{{ __('Akun') }}</a>
                <a href="{{ route('user.logout') }}" class="block px-6 py-4 text-[15px] font-bold text-rose-600 hover:bg-rose-50 transition">{{ __('Keluar') }}</a>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>
