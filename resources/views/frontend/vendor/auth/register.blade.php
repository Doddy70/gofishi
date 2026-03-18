@extends('frontend.layout-airbnb')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->vendor_signup_page_title ? $pageHeading->vendor_signup_page_title : __('Become a Host') }}
  @else
    {{ __('Become a Host') }}
  @endif
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keywords_vendor_signup }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_vendor_signup }}
  @endif
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 py-12" x-data="{ step: 1 }">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="text-center mb-12">
      <h1 class="text-4xl font-extrabold text-gray-900 mb-4">{{ __('Become a Host') }}</h1>
      <p class="text-lg text-gray-600 font-light">{{ __('Share your boat and earn extra income with Go Fishi.') }}</p>
    </div>

    {{-- Progress Stepper --}}
    <div class="mb-12">
      <div class="flex items-center justify-between max-w-2xl mx-auto">
        <template x-for="i in [1, 2, 3]" :key="i">
            <div class="flex items-center flex-1 last:flex-none">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-colors duration-300"
                         :class="step >= i ? 'bg-airbnb-red text-white' : 'bg-gray-200 text-gray-400'">
                        <span x-show="step <= i" x-text="i"></span>
                        <i x-show="step > i" data-lucide="check" class="w-5 h-5"></i>
                    </div>
                    <span class="mt-2 text-xs font-semibold uppercase tracking-wider" 
                          :class="step >= i ? 'text-gray-900' : 'text-gray-400'"
                          x-text="i == 1 ? 'Account' : (i == 2 ? 'Documents' : 'Finalize')"></span>
                </div>
                <div x-show="i < 3" class="flex-1 h-1 mx-4 -mt-6 transition-colors duration-300"
                     :class="step > i ? 'bg-airbnb-red' : 'bg-gray-200'"></div>
            </div>
        </template>
      </div>
    </div>

    {{-- Form Container --}}
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
      <form action="{{ route('vendor.signup_submit') }}" method="POST" enctype="multipart/form-data" class="p-8 sm:p-12">
        @csrf
        
        {{-- Step 1: Account Details --}}
        <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0">
            <h2 class="text-2xl font-bold text-gray-900 mb-8 flex items-center gap-3">
                <i data-lucide="user" class="text-airbnb-red"></i>
                {{ __('Account Information') }}
            </h2>
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-sm font-bold text-gray-700">{{ __('Username') }}*</label>
                        <input type="text" name="username" value="{{ old('username') }}" required
                               class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 focus:ring-2 focus:ring-rose-500 transition"
                               placeholder="captain_jack">
                        @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-bold text-gray-700">{{ __('Email') }}*</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 focus:ring-2 focus:ring-rose-500 transition"
                               placeholder="jack@example.com">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-sm font-bold text-gray-700">{{ __('Password') }}*</label>
                        <input type="password" name="password" required
                               class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 focus:ring-2 focus:ring-rose-500 transition"
                               placeholder="••••••••">
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-bold text-gray-700">{{ __('Confirm Password') }}*</label>
                        <input type="password" name="password_confirmation" required
                               class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 focus:ring-2 focus:ring-rose-500 transition"
                               placeholder="••••••••">
                    </div>
                </div>
            </div>
            <div class="mt-12 flex justify-end">
                <button type="button" @click="step = 2" class="bg-gray-900 text-white px-10 py-3 rounded-xl font-bold hover:bg-black transition active:scale-95 shadow-lg">
                    {{ __('Next Step') }}
                </button>
            </div>
        </div>

        {{-- Step 2: Documents --}}
        <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
            <h2 class="text-2xl font-bold text-gray-900 mb-8 flex items-center gap-3">
                <i data-lucide="file-text" class="text-airbnb-red"></i>
                {{ __('Legal Documents') }}
            </h2>
            <p class="text-gray-500 mb-8 font-light italic text-sm">{{ __('We need these documents to verify your boat and captain license.') }}</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700">{{ __('KTP (Identity Card)') }}*</label>
                    <div class="relative border-2 border-dashed border-gray-200 rounded-2xl p-4 hover:border-rose-300 transition group">
                        <input type="file" name="ktp_file" class="absolute inset-0 opacity-0 cursor-pointer z-10" required>
                        <div class="text-center">
                            <i data-lucide="upload-cloud" class="w-8 h-8 mx-auto text-gray-300 group-hover:text-rose-400 transition"></i>
                            <p class="text-xs text-gray-400 mt-2">{{ __('Click or drag image/pdf') }}</p>
                        </div>
                    </div>
                    @error('ktp_file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700">{{ __('Boat Ownership Proof') }}*</label>
                    <div class="relative border-2 border-dashed border-gray-200 rounded-2xl p-4 hover:border-rose-300 transition group">
                        <input type="file" name="boat_ownership_file" class="absolute inset-0 opacity-0 cursor-pointer z-10" required>
                        <div class="text-center">
                            <i data-lucide="ship" class="w-8 h-8 mx-auto text-gray-300 group-hover:text-rose-400 transition"></i>
                            <p class="text-xs text-gray-400 mt-2">{{ __('Click or drag image/pdf') }}</p>
                        </div>
                    </div>
                    @error('boat_ownership_file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700">{{ __('Captain License') }}*</label>
                    <div class="relative border-2 border-dashed border-gray-200 rounded-2xl p-4 hover:border-rose-300 transition group">
                        <input type="file" name="driving_license_file" class="absolute inset-0 opacity-0 cursor-pointer z-10" required>
                        <div class="text-center">
                            <i data-lucide="badge-check" class="w-8 h-8 mx-auto text-gray-300 group-hover:text-rose-400 transition"></i>
                            <p class="text-xs text-gray-400 mt-2">{{ __('Click or drag image/pdf') }}</p>
                        </div>
                    </div>
                    @error('driving_license_file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700">{{ __('Selfie with Documents') }}*</label>
                    <div class="relative border-2 border-dashed border-gray-200 rounded-2xl p-4 hover:border-rose-300 transition group">
                        <input type="file" name="self_photo_file" class="absolute inset-0 opacity-0 cursor-pointer z-10" required>
                        <div class="text-center">
                            <i data-lucide="camera" class="w-8 h-8 mx-auto text-gray-300 group-hover:text-rose-400 transition"></i>
                            <p class="text-xs text-gray-400 mt-2">{{ __('Click or drag photo') }}</p>
                        </div>
                    </div>
                    @error('self_photo_file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-12 flex justify-between">
                <button type="button" @click="step = 1" class="text-gray-500 font-bold hover:text-black transition">
                    {{ __('Back') }}
                </button>
                <button type="button" @click="step = 3" class="bg-gray-900 text-white px-10 py-3 rounded-xl font-bold hover:bg-black transition active:scale-95 shadow-lg">
                    {{ __('Continue') }}
                </button>
            </div>
        </div>

        {{-- Step 3: Finalize --}}
        <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
            <h2 class="text-2xl font-bold text-gray-900 mb-8 flex items-center gap-3">
                <i data-lucide="check-circle" class="text-airbnb-red"></i>
                {{ __('Final Steps') }}
            </h2>

            <div class="space-y-6 bg-gray-50 p-8 rounded-2xl border border-gray-100">
                <div class="flex items-start gap-4">
                    <input type="checkbox" name="terms_and_conditions" id="terms_and_conditions" required class="mt-1 h-5 w-5 text-airbnb-red focus:ring-rose-500 border-gray-300 rounded cursor-pointer">
                    <label for="terms_and_conditions" class="text-sm text-gray-600 leading-relaxed cursor-pointer">
                        {{ __('Saya menyatakan bahwa semua data yang saya berikan adalah benar dan saya menyetujui') }} 
                        <a href="#" class="text-airbnb-red font-bold hover:underline">{{ __('Syarat & Ketentuan') }}</a> 
                        {{ __('serta') }} 
                        <a href="#" class="text-airbnb-red font-bold hover:underline">{{ __('Kebijakan Privasi') }}</a> {{ __('Go Fishi.') }}
                    </label>
                </div>
                @error('terms_and_conditions') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mt-12 flex justify-between items-center">
                <button type="button" @click="step = 2" class="text-gray-500 font-bold hover:text-black transition">
                    {{ __('Back') }}
                </button>
                <button type="submit" class="bg-airbnb-red text-white px-12 py-4 rounded-2xl font-black text-lg hover:bg-rose-600 transition active:scale-95 shadow-xl">
                    {{ __('Submit Application') }}
                </button>
            </div>
        </div>

      </form>
    </div>

    {{-- Footer Info --}}
    <div class="mt-8 text-center">
        <p class="text-sm text-gray-500">
            {{ __('Already a member?') }} 
            <a href="{{ route('vendor.login') }}" class="text-airbnb-red font-bold hover:underline">{{ __('Login Now') }}</a>
        </p>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
@endsection
