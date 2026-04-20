@extends('frontend.layout-airbnb')

@section('pageHeading')
  {{ __('Account') }}
@endsection

@section('content')
<div class="min-h-screen bg-white" x-data="{ 
    activeTab: 'trips',
    isEditing: false,
    showVerifyEmail: false,
    showVerifyPhone: false,
    showVerifyId: false
}">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- Profile Header --}}
    <div class="py-8 border-b border-gray-100 sticky top-0 bg-white z-40">
      <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="flex items-start gap-6">
          {{-- Avatar --}}
          <div class="relative">
            <div class="w-24 h-24 rounded-full border-2 border-gray-200 bg-gray-100 overflow-hidden shadow-sm">
                @if($authUser->image)
                    <img src="{{ asset('assets/img/users/' . $authUser->image) }}" class="w-full h-full object-cover">
                @else
                    <i data-lucide="user-circle" class="w-full h-full text-gray-300 p-2"></i>
                @endif
            </div>
            @if($authUser->email_verified_at)
              <div class="absolute -bottom-1 -right-1 bg-green-500 text-white text-[10px] font-bold w-7 h-7 rounded-full flex items-center justify-center border-2 border-white shadow-sm" title="{{ __('Email Verified') }}">
                <i data-lucide="check" class="w-4 h-4"></i>
              </div>
            @endif
          </div>

          {{-- Profile Info --}}
          <div>
            <div class="flex items-center gap-3 mb-2">
              <h1 class="text-2xl font-bold text-gray-900">{{ $authUser->name ?? $authUser->username }}</h1>
              @if($authUser->is_vendor)
                <span class="px-3 py-1 bg-airbnb-red text-white text-xs font-bold rounded-full uppercase">{{ __('Host') }}</span>
              @endif
            </div>
            <p class="text-gray-500 text-sm mb-4">{{ __('Member since') }} {{ $authUser->created_at->format('M Y') }}</p>
            
            {{-- Verification Status --}}
            <div class="flex flex-wrap gap-2">
              <div class="flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 rounded-full border border-gray-200 text-xs font-medium text-gray-700">
                <i data-lucide="mail" class="w-3 h-3 {{ $authUser->email_verified_at ? 'text-green-500' : 'text-gray-300' }}"></i>
                {{ __('Email') }} {{ $authUser->email_verified_at ? __('Verified') : __('Unverified') }}
              </div>
              <div class="flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 rounded-full border border-gray-200 text-xs font-medium text-gray-700">
                <i data-lucide="phone" class="w-3 h-3 {{ $authUser->phone ? 'text-green-500' : 'text-gray-300' }}"></i>
                {{ __('Phone') }} {{ $authUser->phone ? __('Verified') : __('Unverified') }}
              </div>
            </div>
          </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex gap-3 w-full md:w-auto">
          <button @click="isEditing = !isEditing" 
                  class="flex items-center justify-center gap-2 px-6 py-2.5 bg-white border border-gray-300 text-gray-900 rounded-lg hover:bg-gray-50 transition font-semibold text-sm flex-1 md:flex-none">
              <i data-lucide="edit-2" class="w-4 h-4"></i>
              <span x-text="isEditing ? '{{ __('Cancel') }}' : '{{ __('Edit Profile') }}'"></span>
          </button>
          @if(!$authUser->is_vendor)
              <a href="{{ route('vendor.dashboard') }}" class="flex items-center justify-center gap-2 px-6 py-2.5 bg-airbnb-red text-white rounded-lg hover:bg-rose-600 transition font-semibold text-sm">
                  <i data-lucide="home" class="w-4 h-4"></i>
                  <span>{{ __('Switch to Hosting') }}</span>
              </a>
          @endif
        </div>
      </div>
    </div>

    {{-- Tab Navigation (Airbnb Style) --}}
    <div class="border-b border-gray-100 sticky top-20 bg-white z-40">
      <div class="flex gap-8 overflow-x-auto no-scrollbar">
        <button @click="activeTab = 'trips'" 
                :class="activeTab === 'trips' ? 'border-b-2 border-gray-900 text-gray-900' : 'text-gray-500 hover:text-gray-900'"
                class="px-1 py-4 font-semibold text-sm whitespace-nowrap transition">
          <i data-lucide="map-pin" class="w-4 h-4 inline mr-2"></i>{{ __('Trips') }}
        </button>
        <button @click="activeTab = 'reviews'" 
                :class="activeTab === 'reviews' ? 'border-b-2 border-gray-900 text-gray-900' : 'text-gray-500 hover:text-gray-900'"
                class="px-1 py-4 font-semibold text-sm whitespace-nowrap transition">
          <i data-lucide="star" class="w-4 h-4 inline mr-2"></i>{{ __('Reviews') }}
        </button>
        <button @click="activeTab = 'saved'" 
                :class="activeTab === 'saved' ? 'border-b-2 border-gray-900 text-gray-900' : 'text-gray-500 hover:text-gray-900'"
                class="px-1 py-4 font-semibold text-sm whitespace-nowrap transition">
          <i data-lucide="heart" class="w-4 h-4 inline mr-2"></i>{{ __('Saved') }}
        </button>
        <button @click="activeTab = 'payments'" 
                :class="activeTab === 'payments' ? 'border-b-2 border-gray-900 text-gray-900' : 'text-gray-500 hover:text-gray-900'"
                class="px-1 py-4 font-semibold text-sm whitespace-nowrap transition">
          <i data-lucide="credit-card" class="w-4 h-4 inline mr-2"></i>{{ __('Payments') }}
        </button>
        <button @click="activeTab = 'settings'" 
                :class="activeTab === 'settings' ? 'border-b-2 border-gray-900 text-gray-900' : 'text-gray-500 hover:text-gray-900'"
                class="px-1 py-4 font-semibold text-sm whitespace-nowrap transition">
          <i data-lucide="settings" class="w-4 h-4 inline mr-2"></i>{{ __('Settings') }}
        </button>
      </div>
    </div>

    <div class="py-12">
      {{-- TRIPS TAB --}}
      <div x-show="activeTab === 'trips'" x-transition>
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ __('Your Trips') }}</h2>
          <p class="text-gray-500">{{ __('Explore your upcoming and past bookings.') }}</p>
        </div>

        @if(count($bookings) == 0)
          <div class="bg-white rounded-2xl border border-gray-200 p-16 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
              <i data-lucide="calendar-x" class="w-8 h-8"></i>
            </div>
            <p class="text-gray-600 font-medium mb-2">{{ __('No trips booked yet') }}</p>
            <p class="text-gray-500 text-sm mb-6">{{ __('Start exploring and book your first adventure!') }}</p>
            <a href="{{ route('frontend.perahu') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-airbnb-red text-white font-bold rounded-lg hover:bg-rose-600 transition">
              <i data-lucide="compass" class="w-4 h-4"></i>{{ __('Explore Boats') }}
            </a>
          </div>
        @else
          {{-- Upcoming vs Past --}}
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Upcoming Trips --}}
            <div>
              <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Upcoming') }}</h3>
              @php
                $upcomingBookings = $bookings->filter(fn($b) => Carbon\Carbon::parse($b->check_in_date) > now());
              @endphp
              @if($upcomingBookings->count() > 0)
                <div class="space-y-4">
                  @foreach($upcomingBookings->take(5) as $booking)
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden hover:shadow-lg transition group cursor-pointer">
                      <div class="relative bg-gray-200 h-48 overflow-hidden">
                        @if($booking->room && $booking->room->room_galleries->count() > 0)
                          <img src="{{ asset('assets/img/room/' . $booking->room->room_galleries->first()->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                          <div class="w-full h-full bg-gradient-to-br from-airbnb-red/20 to-rose-200 flex items-center justify-center">
                            <i data-lucide="anchor" class="w-12 h-12 text-airbnb-red/30"></i>
                          </div>
                        @endif
                        <div class="absolute top-4 right-4 bg-white rounded-full px-3 py-1 text-xs font-bold shadow-md">
                          {{ $booking->payment_status == 1 ? '✓ Confirmed' : 'Pending' }}
                        </div>
                      </div>
                      <div class="p-4">
                        <p class="font-bold text-gray-900 mb-1">{{ $booking->room->room_content()->first()?->title ?? 'Boat Trip' }}</p>
                        <p class="text-sm text-gray-500 mb-3">
                          <i data-lucide="calendar" class="w-3 h-3 inline mr-1"></i>
                          {{ Carbon\Carbon::parse($booking->check_in_date)->format('M d') }} - {{ Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}
                        </p>
                        <a href="{{ route('user.perahu_booking_details', ['id' => $booking->id]) }}" class="text-airbnb-red font-semibold text-sm hover:underline">
                          {{ __('View Details') }} →
                        </a>
                      </div>
                    </div>
                  @endforeach
                </div>
              @else
                <p class="text-gray-500 text-center py-8">{{ __('No upcoming trips') }}</p>
              @endif
            </div>

            {{-- Past Trips --}}
            <div>
              <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Past Trips') }}</h3>
              @php
                $pastBookings = $bookings->filter(fn($b) => Carbon\Carbon::parse($b->check_in_date) <= now());
              @endphp
              @if($pastBookings->count() > 0)
                <div class="space-y-4">
                  @foreach($pastBookings->take(5) as $booking)
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden hover:shadow-lg transition group cursor-pointer">
                      <div class="relative bg-gray-200 h-48 overflow-hidden">
                        @if($booking->room && $booking->room->room_galleries->count() > 0)
                          <img src="{{ asset('assets/img/room/' . $booking->room->room_galleries->first()->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                          <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                            <i data-lucide="anchor" class="w-12 h-12 text-gray-300"></i>
                          </div>
                        @endif
                        <div class="absolute top-4 right-4 bg-white rounded-full px-3 py-1 text-xs font-bold shadow-md opacity-75">
                          {{ __('Completed') }}
                        </div>
                      </div>
                      <div class="p-4">
                        <p class="font-bold text-gray-900 mb-1">{{ $booking->room->room_content()->first()?->title ?? 'Boat Trip' }}</p>
                        <p class="text-sm text-gray-500 mb-3">
                          <i data-lucide="calendar" class="w-3 h-3 inline mr-1"></i>
                          {{ Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}
                        </p>
                        <a href="{{ route('user.perahu_booking_details', ['id' => $booking->id]) }}" class="text-gray-600 font-semibold text-sm hover:text-gray-900">
                          {{ __('View Details') }} →
                        </a>
                      </div>
                    </div>
                  @endforeach
                </div>
              @else
                <p class="text-gray-500 text-center py-8">{{ __('No past trips') }}</p>
              @endif
            </div>
          </div>
        @endif
      </div>

      {{-- REVIEWS TAB --}}
      <div x-show="activeTab === 'reviews'" x-transition>
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ __('Reviews') }}</h2>
          <p class="text-gray-500">{{ __('See the reviews you have left for your trips and boats.') }}</p>
        </div>

        @if($reviews->count() > 0)
          <div class="grid grid-cols-1 gap-6">
            @foreach($reviews as $review)
              @php
                $room = $review->room;
                $title = $room?->room_content->first()?->title ?? __('Boat Trip');
                $image = $room?->room_galleries->first()?->image;
              @endphp
              <div class="bg-white rounded-3xl border border-gray-200 overflow-hidden shadow-sm transition hover:shadow-md">
                <div class="grid grid-cols-1 lg:grid-cols-[260px,1fr] gap-0 lg:gap-6">
                  <div class="h-56 lg:h-full bg-gray-100 overflow-hidden">
                    @if($image)
                      <img src="{{ asset('assets/img/room/' . $image) }}" class="w-full h-full object-cover">
                    @else
                      <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                        <i data-lucide="anchor" class="w-12 h-12 text-gray-400"></i>
                      </div>
                    @endif
                  </div>
                  <div class="p-6 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                      <div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ $title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $review->created_at->format('d M Y') }}</p>
                      </div>
                      <div class="inline-flex items-center gap-2 bg-amber-100 text-amber-700 px-3 py-2 rounded-full text-sm font-semibold">
                        <span>{{ $review->rating }}</span>
                        <span>/ 5</span>
                      </div>
                    </div>

                    <div class="flex items-center gap-1 text-yellow-400">
                      @for($i = 1; $i <= 5; $i++)
                        <i data-lucide="star" class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-300' }}"></i>
                      @endfor
                    </div>

                    <p class="text-gray-700 leading-7">{{ $review->review }}</p>

                    <div class="flex flex-wrap items-center justify-between gap-4 text-sm text-gray-500">
                      <span>{{ __('Reviewed on') }} {{ $review->created_at->format('d M Y') }}</span>
                      @if($room)
                        <a href="{{ route('frontend.perahu.details', ['slug' => $room->room_content()->first()?->slug ?? 'boat', 'id' => $room->id]) }}" class="text-airbnb-red font-semibold hover:underline">
                          {{ __('View boat') }}
                        </a>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="bg-white rounded-2xl border border-gray-200 p-16 text-center">
            <i data-lucide="star" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
            <p class="text-gray-600 font-medium mb-2">{{ __('No reviews yet') }}</p>
            <p class="text-gray-500 text-sm">{{ __('Complete your first booking and leave a review after your trip.') }}</p>
          </div>
        @endif
      </div>

      {{-- SAVED TAB --}}
      <div x-show="activeTab === 'saved'" x-transition>
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ __('Saved Boats') }}</h2>
          <p class="text-gray-500">{{ __('Boats you\'ve added to your wishlist') }}</p>
        </div>

        @if($roomwishlists->count() > 0)
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($roomwishlists as $wishlist)
              @php
                $room = $wishlist->room ?? null;
              @endphp
              @if($room)
                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden hover:shadow-lg transition group">
                  <div class="relative bg-gray-200 h-48 overflow-hidden">
                    @if($room->room_galleries->count() > 0)
                      <img src="{{ asset('assets/img/room/' . $room->room_galleries->first()->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                    @else
                      <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                        <i data-lucide="anchor" class="w-12 h-12 text-gray-300"></i>
                      </div>
                    @endif
                    <button class="absolute top-4 right-4 p-2 bg-white rounded-full shadow-md hover:bg-gray-50 transition">
                      <i data-lucide="heart" class="w-5 h-5 text-airbnb-red fill-current"></i>
                    </button>
                  </div>
                  <div class="p-4">
                    <p class="font-bold text-gray-900 text-lg mb-1">{{ $room->room_content()->first()?->title ?? 'Boat' }}</p>
                    <div class="flex items-center gap-2 mb-3">
                      <div class="flex gap-0.5">
                        @for($i = 0; $i < 5; $i++)
                          <i data-lucide="star" class="w-3 h-3 {{ $i < 4 ? 'fill-yellow-400 text-yellow-400' : 'text-gray-300' }}"></i>
                        @endfor
                      </div>
                      <span class="text-sm text-gray-500">(42 reviews)</span>
                    </div>
                    <a href="{{ route('frontend.perahu.details', ['slug' => $room->room_content()->first()?->slug ?? 'boat', 'id' => $room->id]) }}" class="block text-airbnb-red font-semibold text-sm hover:underline">
                      {{ __('View Details') }} →
                    </a>
                  </div>
                </div>
              @endif
            @endforeach
          </div>
        @else
          <div class="bg-white rounded-2xl border border-gray-200 p-16 text-center">
            <i data-lucide="heart" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
            <p class="text-gray-600 font-medium mb-2">{{ __('No saved boats') }}</p>
            <p class="text-gray-500 text-sm mb-6">{{ __('Add boats to your wishlist to save them for later') }}</p>
            <a href="{{ route('frontend.perahu') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-airbnb-red text-white font-bold rounded-lg hover:bg-rose-600 transition">
              <i data-lucide="search" class="w-4 h-4"></i>{{ __('Explore Boats') }}
            </a>
          </div>
        @endif
      </div>

      {{-- PAYMENTS TAB --}}
      <div x-show="activeTab === 'payments'" x-transition>
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ __('Payments') }}</h2>
          <p class="text-gray-500">{{ __('Manage your payment methods and billing history') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          {{-- Payment Methods --}}
          <div class="bg-white rounded-2xl border border-gray-200 p-8">
            <h3 class="text-lg font-bold text-gray-900 mb-6">{{ __('Payment Methods') }}</h3>
            <div class="space-y-3">
              <div class="border border-gray-200 rounded-xl p-4 flex items-center justify-between hover:bg-gray-50 transition">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                    <i data-lucide="credit-card" class="w-5 h-5 text-blue-600"></i>
                  </div>
                  <div>
                    <p class="font-semibold text-gray-900 text-sm">{{ __('Credit / Debit Card') }}</p>
                    <p class="text-xs text-gray-500">{{ __('Visa, Mastercard') }}</p>
                  </div>
                </div>
                <i data-lucide="chevron-right" class="w-5 h-5 text-gray-400"></i>
              </div>
              <div class="border border-gray-200 rounded-xl p-4 flex items-center justify-between hover:bg-gray-50 transition">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                    <i data-lucide="wallet" class="w-5 h-5 text-green-600"></i>
                  </div>
                  <div>
                    <p class="font-semibold text-gray-900 text-sm">{{ __('Digital Wallet') }}</p>
                    <p class="text-xs text-gray-500">{{ __('GoPay, OVO, DANA') }}</p>
                  </div>
                </div>
                <i data-lucide="chevron-right" class="w-5 h-5 text-gray-400"></i>
              </div>
            </div>
          </div>

          {{-- Billing History --}}
          <div class="bg-white rounded-2xl border border-gray-200 p-8">
            <h3 class="text-lg font-bold text-gray-900 mb-6">{{ __('Recent Transactions') }}</h3>
            <div class="space-y-3">
              @forelse($bookings->take(3) as $booking)
                <div class="border-b border-gray-100 pb-3 last:border-0">
                  <div class="flex justify-between items-start">
                    <div>
                      <p class="font-semibold text-gray-900 text-sm">#{{ $booking->order_number }}</p>
                      <p class="text-xs text-gray-500">{{ Carbon\Carbon::parse($booking->created_at)->format('M d, Y') }}</p>
                    </div>
                    <p class="font-bold text-gray-900">{{ symbolPrice($booking->total_amount ?? 0) }}</p>
                  </div>
                </div>
              @empty
                <p class="text-gray-500 text-sm text-center py-4">{{ __('No transactions yet') }}</p>
              @endforelse
            </div>
          </div>
        </div>
      </div>

      {{-- SETTINGS TAB --}}
      <div x-show="activeTab === 'settings'" x-transition>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {{-- Left: Edit Profile Form --}}
          <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-200 p-8 mb-8" x-show="isEditing" x-transition>
              <h2 class="text-xl font-bold text-gray-900 mb-6">{{ __('Edit Profile') }}</h2>
              
              <form action="{{ route('user.update_profile') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Full Name') }}</label>
                    <input type="text" name="name" value="{{ $authUser->name }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-airbnb-red focus:border-transparent transition">
                  </div>
                  <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Email') }}</label>
                    <input type="email" value="{{ $authUser->email }}" disabled
                           class="w-full border border-gray-300 bg-gray-50 rounded-lg px-4 py-3 text-gray-500 cursor-not-allowed">
                  </div>
                  <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Phone') }}</label>
                    <input type="text" name="phone" value="{{ $authUser->phone }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-airbnb-red focus:border-transparent transition">
                  </div>
                  <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Date of Birth') }}</label>
                    <input type="date" name="dob" value="{{ $authUser->dob }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-airbnb-red focus:border-transparent transition">
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Bio') }}</label>
                  <textarea name="bio" rows="4" placeholder="{{ __('Tell guests about yourself') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-airbnb-red focus:border-transparent transition resize-none">{{ $authUser->bio ?? '' }}</textarea>
                </div>

                <div class="flex gap-3 pt-4">
                  <button type="submit" class="flex-1 bg-airbnb-red text-white font-bold py-3 rounded-lg hover:bg-rose-600 transition">
                    {{ __('Save Changes') }}
                  </button>
                  <button type="button" @click="isEditing = false" class="flex-1 bg-gray-100 text-gray-900 font-bold py-3 rounded-lg hover:bg-gray-200 transition">
                    {{ __('Cancel') }}
                  </button>
                </div>
              </form>
            </div>

            {{-- Settings Sections --}}
            <div x-show="!isEditing" class="space-y-4">
              {{-- Account Settings --}}
              <div class="bg-white rounded-2xl border border-gray-200 p-8">
                <h3 class="text-lg font-bold text-gray-900 mb-6">{{ __('Account') }}</h3>
                <div class="space-y-4">
                  <a href="{{ route('user.change_password') }}" class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-lg transition group">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-gray-200 transition">
                        <i data-lucide="lock" class="w-5 h-5 text-gray-600"></i>
                      </div>
                      <div>
                        <p class="font-semibold text-gray-900">{{ __('Change Password') }}</p>
                        <p class="text-sm text-gray-500">{{ __('Update your password regularly') }}</p>
                      </div>
                    </div>
                    <i data-lucide="chevron-right" class="w-5 h-5 text-gray-400"></i>
                  </a>
                  
                  <a href="{{ route('user.support_ticket.index') }}" class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-lg transition group">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-gray-200 transition">
                        <i data-lucide="help-circle" class="w-5 h-5 text-gray-600"></i>
                      </div>
                      <div>
                        <p class="font-semibold text-gray-900">{{ __('Support') }}</p>
                        <p class="text-sm text-gray-500">{{ __('Contact our support team') }}</p>
                      </div>
                    </div>
                    <i data-lucide="chevron-right" class="w-5 h-5 text-gray-400"></i>
                  </a>
                </div>
              </div>

              {{-- Privacy & Notifications --}}
              <div class="bg-white rounded-2xl border border-gray-200 p-8">
                <h3 class="text-lg font-bold text-gray-900 mb-6">{{ __('Preferences') }}</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-lg transition">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="bell" class="w-5 h-5 text-gray-600"></i>
                      </div>
                      <div>
                        <p class="font-semibold text-gray-900">{{ __('Notifications') }}</p>
                        <p class="text-sm text-gray-500">{{ __('Manage email notifications') }}</p>
                      </div>
                    </div>
                    <input type="checkbox" class="w-5 h-5 accent-airbnb-red rounded" checked>
                  </div>

                  <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-lg transition">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="eye-off" class="w-5 h-5 text-gray-600"></i>
                      </div>
                      <div>
                        <p class="font-semibold text-gray-900">{{ __('Privacy') }}</p>
                        <p class="text-sm text-gray-500">{{ __('Manage your privacy settings') }}</p>
                      </div>
                    </div>
                    <i data-lucide="chevron-right" class="w-5 h-5 text-gray-400"></i>
                  </div>
                </div>
              </div>

              {{-- Danger Zone --}}
              <div class="bg-red-50 border border-red-200 rounded-2xl p-8">
                <h3 class="text-lg font-bold text-red-900 mb-6">{{ __('Danger Zone') }}</h3>
                <div class="space-y-3">
                  <p class="text-sm text-red-800">{{ __('These actions cannot be undone.') }}</p>
                  <button class="w-full px-6 py-3 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 transition">
                    {{ __('Delete Account') }}
                  </button>
                  <a href="{{ route('user.logout') }}" class="block text-center px-6 py-3 bg-white text-gray-900 font-bold rounded-lg border border-gray-300 hover:bg-gray-50 transition">
                    {{ __('Logout') }}
                  </a>
                </div>
              </div>
            </div>
          </div>

          {{-- Right: Quick Info Sidebar --}}
          <div class="lg:col-span-1">
            {{-- Verification Cards --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6 sticky top-40 space-y-4">
              <h3 class="font-bold text-gray-900">{{ __('Profile Completeness') }}</h3>
              
              {{-- Progress Bar --}}
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-airbnb-red h-2 rounded-full" style="width: 70%"></div>
              </div>
              <p class="text-sm text-gray-600">70% {{ __('Complete') }}</p>

              <div class="space-y-2 pt-4 border-t border-gray-100">
                {{-- Email Verified --}}
                <div class="flex items-center gap-3 p-3 rounded-lg {{ $authUser->email_verified_at ? 'bg-green-50' : 'bg-yellow-50' }}">
                  <i data-lucide="{{ $authUser->email_verified_at ? 'check-circle' : 'alert-circle' }}" class="w-5 h-5 {{ $authUser->email_verified_at ? 'text-green-600' : 'text-yellow-600' }}"></i>
                  <div class="flex-1">
                    <p class="text-xs font-bold text-gray-900">{{ __('Email') }}</p>
                    <p class="text-xs {{ $authUser->email_verified_at ? 'text-green-600' : 'text-yellow-600' }}">{{ $authUser->email_verified_at ? __('Verified') : __('Unverified') }}</p>
                  </div>
                </div>

                {{-- Phone Verified --}}
                <div class="flex items-center gap-3 p-3 rounded-lg {{ $authUser->phone ? 'bg-green-50' : 'bg-gray-50' }}">
                  <i data-lucide="{{ $authUser->phone ? 'check-circle' : 'circle' }}" class="w-5 h-5 {{ $authUser->phone ? 'text-green-600' : 'text-gray-400' }}"></i>
                  <div class="flex-1">
                    <p class="text-xs font-bold text-gray-900">{{ __('Phone') }}</p>
                    <p class="text-xs {{ $authUser->phone ? 'text-green-600' : 'text-gray-500' }}">{{ $authUser->phone ? __('Added') : __('Add Phone') }}</p>
                  </div>
                </div>

                {{-- Government ID --}}
                <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50">
                  <i data-lucide="circle" class="w-5 h-5 text-gray-400"></i>
                  <div class="flex-1">
                    <p class="text-xs font-bold text-gray-900">{{ __('Government ID') }}</p>
                    <p class="text-xs text-gray-500">{{ __('Not verified') }}</p>
                  </div>
                </div>
              </div>

              <a href="#" class="block text-center px-4 py-2 text-airbnb-red font-semibold text-sm hover:underline">
                {{ __('Complete verification') }} →
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
