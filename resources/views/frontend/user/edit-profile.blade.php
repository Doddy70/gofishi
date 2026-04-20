@extends('frontend.layout-airbnb')

@section('pageHeading')
  {{ __('Edit Profile') }}
@endsection

@section('content')
@php $authUser = auth()->user(); @endphp
<div class="min-h-screen bg-gray-50 py-12" x-data="{ isEditing: true }">
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
            <h1 class="text-3xl font-bold text-gray-900">{{ $authUser->name }}</h1>
            <p class="text-gray-500 font-light">{{ $authUser->email }}</p>
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
        {{-- Left: Edit Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-8 flex items-center gap-2">
                    <i data-lucide="user-cog" class="w-5 h-5 text-airbnb-red"></i>
                    {{ __('Edit Profile Information') }}
                </h2>

                @if (Session::has('success'))
                  <div class="mb-6 p-4 bg-green-50 text-green-600 rounded-xl border border-green-100 text-sm font-medium animate-in fade-in">
                    {{ Session::get('success') }}
                  </div>
                @endif

                <form action="{{ route('user.update_profile') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    
                    {{-- Profile Picture --}}
                    <div class="flex items-center gap-6 p-4 bg-neutral-50 rounded-2xl border border-neutral-100">
                        <div class="w-20 h-20 rounded-full overflow-hidden bg-gray-200 border-2 border-white shadow">
                            <img id="profile-preview" src="{{ $authUser->image ? asset('assets/img/users/' . $authUser->image) : asset('assets/img/blank-user.jpg') }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-bold text-gray-900">{{ __('Profile Picture') }}</label>
                            <input type="file" name="image" onchange="document.getElementById('profile-preview').src = window.URL.createObjectURL(this.files[0])"
                                   class="text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100 transition-all cursor-pointer">
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">{{ __('Recommended Size') }}: 150x150</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1 group">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest group-focus-within:text-airbnb-red transition-colors">{{ __('Full Name') }}</label>
                            <input type="text" name="name" value="{{ old('name', $authUser->name) }}" 
                                   class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-gray-900 focus:ring-2 focus:ring-rose-500 outline-none transition shadow-sm" required>
                            @error('name') <p class="text-rose-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1 group">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest group-focus-within:text-airbnb-red transition-colors">{{ __('Username') }}</label>
                            <input type="text" name="username" value="{{ old('username', $authUser->username) }}" 
                                   class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-gray-900 focus:ring-2 focus:ring-rose-500 outline-none transition shadow-sm" required>
                            @error('username') <p class="text-rose-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1 opacity-60">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('Email Address') }}</label>
                            <input type="email" value="{{ $authUser->email }}" disabled
                                   class="w-full bg-gray-50 border border-transparent rounded-xl px-4 py-3 text-gray-500 cursor-not-allowed">
                        </div>

                        <div class="space-y-1 group">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest group-focus-within:text-airbnb-red transition-colors">{{ __('Phone Number') }}</label>
                            <input type="text" name="phone" value="{{ old('phone', $authUser->phone) }}" 
                                   class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-gray-900 focus:ring-2 focus:ring-rose-500 outline-none transition shadow-sm">
                        </div>

                        <div class="space-y-1 group">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest group-focus-within:text-airbnb-red transition-colors">{{ __('Country') }}</label>
                            <input type="text" name="country" value="{{ old('country', $authUser->country) }}" 
                                   class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-gray-900 focus:ring-2 focus:ring-rose-500 outline-none transition shadow-sm">
                        </div>

                        <div class="space-y-1 group">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest group-focus-within:text-airbnb-red transition-colors">{{ __('City') }}</label>
                            <input type="text" name="city" value="{{ old('city', $authUser->city) }}" 
                                   class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-gray-900 focus:ring-2 focus:ring-rose-500 outline-none transition shadow-sm">
                        </div>
                    </div>

                    <div class="space-y-1 group">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest group-focus-within:text-airbnb-red transition-colors">{{ __('Address') }}</label>
                        <textarea name="address" rows="3" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-gray-900 focus:ring-2 focus:ring-rose-500 outline-none transition shadow-sm">{{ old('address', $authUser->address) }}</textarea>
                    </div>

                    <div class="pt-6 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="bg-neutral-900 text-white px-10 py-3.5 rounded-xl font-bold hover:bg-black transition active:scale-95 shadow-lg">
                            {{ __('Save Profile Changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right Sidebar --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden sticky top-28">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900">{{ __('Settings & Account') }}</h3>
                </div>
                <nav class="p-2 flex flex-col gap-1">
                    <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 text-gray-600 hover:text-gray-900 transition font-medium text-sm">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i> {{ __('Dashboard Overview') }}
                    </a>
                    <a href="{{ route('user.edit_profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-rose-50 text-airbnb-red transition font-bold text-sm">
                        <i data-lucide="user-cog" class="w-4 h-4"></i> {{ __('Edit Profile') }}
                    </a>
                    <a href="{{ route('user.change_password') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 text-gray-600 hover:text-gray-900 transition font-medium text-sm">
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
