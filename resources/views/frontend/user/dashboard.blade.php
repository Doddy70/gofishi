@extends('frontend.layout-airbnb')

@section('pageHeading')
  {{ __('Dashboard') }}
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 py-12" x-data="{ isEditing: false }">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- Header Section --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-6 border border-gray-200">
      <div class="bg-gradient-to-r from-airbnb-red to-rose-600 h-32"></div>
      <div class="px-8 pb-8">
        <div class="flex flex-col md:flex-row items-center md:items-end -mt-16 gap-6">
          <div class="relative">
            <div class="w-32 h-32 rounded-full border-4 border-white shadow-lg bg-gray-200 overflow-hidden">
                @if($authUser->image)
                    <img src="{{ asset('assets/img/users/' . $authUser->image) }}" class="w-full h-full object-cover">
                @else
                    <i data-lucide="user-circle" class="w-full h-full text-gray-400 p-2"></i>
                @endif
            </div>
            @if($authUser->is_vendor)
              <div class="absolute bottom-2 right-2 bg-airbnb-red text-white text-[10px] font-bold px-2 py-1 rounded-full uppercase">
                Host
              </div>
            @endif
          </div>
          <div class="text-center md:text-left flex-1">
            <h1 class="text-3xl font-bold text-gray-900">
              {{ $authUser->name }}
            </h1>
            <p class="text-gray-500 font-light">{{ $authUser->email }}</p>
          </div>
          <div class="flex gap-3">
            <button @click="isEditing = !isEditing" 
                    class="flex items-center gap-2 px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition shadow-sm font-semibold text-sm">
                <i data-lucide="edit-3" class="w-4 h-4"></i>
                <span x-text="isEditing ? '{{ __('Cancel') }}' : '{{ __('Edit Profile') }}'"></span>
            </button>
            @if(!$authUser->is_vendor)
                <a href="{{ route('vendor.dashboard') }}" class="flex items-center gap-2 px-6 py-2.5 bg-airbnb-red text-white rounded-xl hover:bg-rose-600 transition shadow-md font-semibold text-sm">
                    {{ __('Become a Host') }}
                </a>
            @endif
          </div>
        </div>
      </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('user.perahu_bookings') }}" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 hover:shadow-md transition group">
            <div class="flex flex-col items-center">
                <span class="text-3xl font-bold text-airbnb-red group-hover:scale-110 transition-transform">{{ count($bookings) }}</span>
                <p class="text-gray-500 text-sm mt-1 uppercase tracking-wider font-semibold">{{ __('Total Bookings') }}</p>
            </div>
        </a>
        <a href="{{ route('user.wishlist.perahu') }}" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 hover:shadow-md transition group">
            <div class="flex flex-col items-center">
                <span class="text-3xl font-bold text-airbnb-red group-hover:scale-110 transition-transform">{{ count($roomwishlists) }}</span>
                <p class="text-gray-500 text-sm mt-1 uppercase tracking-wider font-semibold">{{ __('Saved Boats') }}</p>
            </div>
        </a>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 flex flex-col items-center">
            <span class="text-3xl font-bold text-airbnb-red uppercase tracking-tighter">{{ $authUser->is_vendor ? 'Host' : 'Guest' }}</span>
            <p class="text-gray-500 text-sm mt-1 uppercase tracking-wider font-semibold">{{ __('Account Type') }}</p>
        </div>
    </div>

    {{-- Info Sections --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left: Details --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i data-lucide="user" class="w-5 h-5 text-airbnb-red"></i>
                    {{ __('Profile Information') }}
                </h2>

                <form action="{{ route('user.update_profile') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-400 uppercase">{{ __('Full Name') }}</label>
                            <input type="text" name="name" value="{{ $authUser->name }}" :disabled="!isEditing"
                                   class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-gray-900 focus:ring-2 focus:ring-rose-500 disabled:opacity-60 transition">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-400 uppercase">{{ __('Username') }}</label>
                            <input type="text" value="{{ $authUser->username }}" disabled
                                   class="w-full bg-gray-100 border-none rounded-xl px-4 py-3 text-gray-500 cursor-not-allowed">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-400 uppercase">{{ __('Email') }}</label>
                            <input type="email" value="{{ $authUser->email }}" disabled
                                   class="w-full bg-gray-100 border-none rounded-xl px-4 py-3 text-gray-500 cursor-not-allowed">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-400 uppercase">{{ __('Phone') }}</label>
                            <input type="text" name="phone" value="{{ $authUser->phone }}" :disabled="!isEditing"
                                   class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-gray-900 focus:ring-2 focus:ring-rose-500 disabled:opacity-60 transition">
                        </div>
                    </div>

                    <div x-show="isEditing" x-transition class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="bg-gray-900 text-white px-8 py-3 rounded-xl font-bold hover:bg-black transition active:scale-95 shadow-md">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            </div>

            {{-- Recent Bookings --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i data-lucide="anchor" class="w-5 h-5 text-airbnb-red"></i>
                    {{ __('Recent Bookings') }}
                </h2>

                @if(count($bookings) == 0)
                    <div class="py-12 text-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                            <i data-lucide="calendar-x" class="w-8 h-8"></i>
                        </div>
                        <p class="text-gray-500 font-light">{{ __('No bookings found.') }}</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($bookings->take(5) as $booking)
                            <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl border border-transparent hover:border-gray-100 transition group">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-lg bg-rose-50 flex items-center justify-center text-rose-500">
                                        <i data-lucide="package" class="w-6 h-6"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">#{{ $booking->order_number }}</p>
                                        <p class="text-xs text-gray-500">{{ Carbon\Carbon::parse($booking->created_at)->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    @php
                                        $statusClass = 'bg-gray-100 text-gray-600';
                                        if($booking->payment_status == 1) $statusClass = 'bg-green-100 text-green-600';
                                        elseif($booking->payment_status == 2) $statusClass = 'bg-red-100 text-red-600';
                                        elseif($booking->payment_status == 0) $statusClass = 'bg-amber-100 text-amber-600';
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $statusClass }}">
                                        {{ $booking->payment_status == 1 ? __('Success') : ($booking->payment_status == 2 ? __('Failed') : __('Pending')) }}
                                    </span>
                                    <a href="{{ route('user.perahu_booking_details', ['id' => $booking->id]) }}" class="p-2 text-gray-400 hover:text-airbnb-red transition">
                                        <i data-lucide="chevron-right" class="w-5 h-5"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Right: Sidebar Nav --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900">{{ __('Account Settings') }}</h3>
                </div>
                <nav class="p-2 flex flex-col gap-1">
                    <a href="{{ route('user.edit_profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 text-gray-600 hover:text-gray-900 transition font-medium text-sm">
                        <i data-lucide="user-cog" class="w-4 h-4"></i> {{ __('Edit Profile') }}
                    </a>
                    <a href="{{ route('user.change_password') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 text-gray-600 hover:text-gray-900 transition font-medium text-sm">
                        <i data-lucide="lock" class="w-4 h-4"></i> {{ __('Change Password') }}
                    </a>
                    <a href="{{ route('user.support_ticket') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 text-gray-600 hover:text-gray-900 transition font-medium text-sm">
                        <i data-lucide="help-circle" class="w-4 h-4"></i> {{ __('Support Tickets') }}
                    </a>
                    <hr class="my-2 mx-4">
                    <a href="{{ route('user.logout') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-red-50 text-red-600 transition font-bold text-sm">
                        <i data-lucide="log-out" class="w-4 h-4"></i> {{ __('Logout') }}
                    </a>
                </nav>
            </div>
        </div>
    </div>
  </div>
</div>
@endsection
