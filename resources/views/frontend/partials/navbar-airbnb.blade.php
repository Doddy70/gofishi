@php
    $lang = $currentLanguageInfo ?? get_lang();
    $user = Auth::guard('web')->user();
    $isAuthenticated = Auth::guard('web')->check();
@endphp

<header class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm" x-data="{ showUserMenu: false }">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center h-20">
      {{-- Logo --}}
      <a href="{{ route('index') }}" class="flex items-center">
        <svg
          class="h-8 w-8 text-airbnb-red"
          viewBox="0 0 32 32"
          fill="currentColor"
        >
          <path d="M16 1c2 0 3.46 1.5 3.46 3.8 0 2.6-2.5 6-3.46 7.3-.96-1.3-3.46-4.7-3.46-7.3C12.54 2.5 14 1 16 1zm0 14.5c4.5 0 8.5 1.5 11.5 4C29 21 30 23 30 25.5c0 2.8-2.2 5-5 5H7c-2.8 0-5-2.2-5-5 0-2.5 1-4.5 2.5-6 3-2.5 7-4 11.5-4z" />
        </svg>
        <span class="ml-2 text-xl font-bold text-airbnb-red">{{ $websiteInfo->website_title ?? 'Go Fishi' }}</span>
      </a>

      {{-- Center Navigation Menus --}}
      <nav class="hidden lg:flex items-center space-x-2">
        @if (!empty($menuData))
            @foreach ($menuData as $menu)
                @if (isset($menu['children']) && count($menu['children']) > 0)
                    {{-- Dropdown Menu --}}
                    <div class="relative" x-data="{ open: false }" @mouseover="open = true" @mouseleave="open = false">
                        <button class="px-4 py-2 text-[15px] font-semibold text-gray-700 hover:bg-gray-100 rounded-full transition flex items-center gap-1">
                            {{ $menu['text'] }}
                            <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" 
                             x-cloak
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             class="absolute left-0 mt-1 w-48 bg-white rounded-xl shadow-xl py-2 border border-gray-100 z-50">
                            @foreach ($menu['children'] as $child)
                                <a href="{{ $child['href'] }}" target="{{ $child['target'] ?? '_self' }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                    {{ $child['text'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    {{-- Single Link --}}
                    <a href="{{ $menu['href'] }}" target="{{ $menu['target'] ?? '_self' }}" 
                       class="px-4 py-2 text-[15px] font-semibold text-gray-700 hover:bg-gray-100 rounded-full transition">
                        {{ $menu['text'] }}
                    </a>
                @endif
            @endforeach
        @endif
      </nav>

      {{-- User Menu --}}
      <div class="flex items-center space-x-4">
        <a
          href="{{ route('vendor.dashboard') }}"
          class="hidden md:block text-sm font-semibold text-gray-700 hover:bg-gray-100 px-4 py-2 rounded-full transition"
        >
          {{ __('Become a Host') }}
        </a>

        <div class="relative">
          <button
            @click="showUserMenu = !showUserMenu"
            @click.away="showUserMenu = false"
            class="flex items-center space-x-2 border border-gray-300 rounded-full py-2 px-4 hover:shadow-md transition bg-white"
          >
            <i data-lucide="menu" class="w-4 h-4 text-gray-700"></i>
            @if($isAuthenticated && $user->image)
              <img
                src="{{ asset('assets/img/users/' . $user->image) }}"
                alt="{{ $user->username }}"
                class="w-7 h-7 rounded-full object-cover"
              />
            @else
              <i data-lucide="user-circle" class="w-7 h-7 text-gray-700"></i>
            @endif
          </button>

          {{-- Dropdown Menu --}}
          <div 
            x-show="showUserMenu"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            x-cloak
            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 border border-gray-200 z-50"
          >
            @if($isAuthenticated)
                <a
                    href="{{ route('user.dashboard') }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                    {{ __('Profile') }}
                </a>
                <a
                    href="{{ route('user.perahu_bookings') }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                    {{ __('My Bookings') }}
                </a>
                <a
                    href="{{ route('user.wishlist.perahu') }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                    {{ __('Favorites') }}
                </a>
                <hr class="my-2" />
                <a
                    href="{{ route('user.logout') }}"
                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                    {{ __('Logout') }}
                </a>
            @else
                <a
                    href="{{ route('user.login') }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 font-semibold"
                >
                    {{ __('Log in') }}
                </a>
                <a
                    href="{{ route('user.signup') }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 font-semibold"
                >
                    {{ __('Sign up') }}
                </a>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</header>
