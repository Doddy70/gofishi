@extends('frontend.layout-airbnb')

@section('pageHeading')
  {{ __('Change Password') }}
@endsection

@section('content')
@php $authUser = auth()->user(); @endphp
<div class="min-h-screen bg-gray-50 py-12">
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
          </div>
          <div class="text-center md:text-left flex-1">
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Security Settings') }}</h1>
            <p class="text-gray-500 font-light">{{ __('Manage your account password and security') }}</p>
          </div>
          <div class="flex gap-3">
            <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2 px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition shadow-sm font-semibold text-sm">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                {{ __('Back to Dashboard') }}
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left: Change Password Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-8 flex items-center gap-2">
                    <i data-lucide="lock" class="w-5 h-5 text-airbnb-red"></i>
                    {{ __('Update Your Password') }}
                </h2>

                @if (Session::has('success'))
                  <div class="mb-6 p-4 bg-green-50 text-green-600 rounded-xl border border-green-100 text-sm font-medium animate-in fade-in">
                    {{ Session::get('success') }}
                  </div>
                @endif

                <form action="{{ route('user.update_password') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <div class="space-y-6">
                        <div class="space-y-1 group">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest group-focus-within:text-airbnb-red transition-colors">{{ __('Current Password') }}</label>
                            <input type="password" name="current_password" required
                                   class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-gray-900 focus:ring-2 focus:ring-rose-500 outline-none transition shadow-sm">
                            @error('current_password') <p class="text-rose-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1 group">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest group-focus-within:text-airbnb-red transition-colors">{{ __('New Password') }}</label>
                            <input type="password" name="new_password" required
                                   class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-gray-900 focus:ring-2 focus:ring-rose-500 outline-none transition shadow-sm">
                            @error('new_password') <p class="text-rose-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1 group">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest group-focus-within:text-airbnb-red transition-colors">{{ __('Confirm New Password') }}</label>
                            <input type="password" name="new_password_confirmation" required
                                   class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-gray-900 focus:ring-2 focus:ring-rose-500 outline-none transition shadow-sm">
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="bg-neutral-900 text-white px-10 py-3.5 rounded-xl font-bold hover:bg-black transition active:scale-95 shadow-lg">
                            {{ __('Update Password') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right Sidebar --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden sticky top-28">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900">{{ __('Security & Privacy') }}</h3>
                </div>
                <nav class="p-2 flex flex-col gap-1">
                    <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 text-gray-600 hover:text-gray-900 transition font-medium text-sm">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i> {{ __('Dashboard Overview') }}
                    </a>
                    <a href="{{ route('user.edit_profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 text-gray-600 hover:text-gray-900 transition font-medium text-sm">
                        <i data-lucide="user-cog" class="w-4 h-4"></i> {{ __('Edit Profile') }}
                    </a>
                    <a href="{{ route('user.change_password') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-rose-50 text-airbnb-red transition font-bold text-sm">
                        <i data-lucide="lock" class="w-4 h-4"></i> {{ __('Change Password') }}
                    </a>
                    <a href="{{ route('user.support_ticket.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 text-gray-600 hover:text-gray-900 transition font-medium text-sm">
                        <i data-lucide="help-circle" class="w-4 h-4"></i> {{ __('Support Tickets') }}
                    </a>
                    <hr class="my-2 mx-4 border-neutral-100">
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
