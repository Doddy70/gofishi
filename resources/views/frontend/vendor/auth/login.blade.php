@extends('frontend.layout-airbnb')

@section('pageHeading')
  {{ !empty($pageHeading) ? $pageHeading->vendor_login_page_title : __('Login') }}
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keywords_vendor_login }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_vendor_login }}
  @endif
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-16">
  <div class="w-full max-w-md px-4">
    {{-- Card --}}
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-10">
      {{-- Header --}}
      <div class="text-center mb-8">
        <div class="w-14 h-14 bg-rose-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
          <i data-lucide="ship" class="w-7 h-7 text-airbnb-red"></i>
        </div>
        <h1 class="text-2xl font-extrabold text-gray-900">{{ __('Selamat Datang') }}</h1>
        <p class="text-gray-500 text-sm mt-1">{{ __('Login ke akun Host Go Fishi Anda') }}</p>
      </div>

      {{-- Alerts --}}
      @if (Session::has('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">{{ __(Session::get('success')) }}</div>
      @endif
      @if (Session::has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm">{{ __(Session::get('error')) }}</div>
      @endif
      @if (Session::has('warning'))
        <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 text-yellow-700 rounded-xl text-sm">{{ __(Session::get('warning')) }}</div>
      @endif

      {{-- Form --}}
      <form action="{{ route('vendor.login_submit') }}" method="POST" class="space-y-5">
        @csrf

        <div>
          <label class="block text-sm font-bold text-gray-700 mb-1">{{ __('Username') }} <span class="text-red-500">*</span></label>
          <input type="text" name="username" value="{{ old('username') }}" required
            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 transition"
            placeholder="username_anda">
          @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-bold text-gray-700 mb-1">{{ __('Password') }} <span class="text-red-500">*</span></label>
          <input type="password" name="password" required
            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 transition"
            placeholder="••••••••">
          @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        @if (!empty($bs) && $bs->google_recaptcha_status == 1)
          <div>{!! NoCaptcha::renderJs() !!}{!! NoCaptcha::display() !!}</div>
          @error('g-recaptcha-response') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        @endif

        <button type="submit"
          class="w-full bg-airbnb-red text-white font-bold py-3 rounded-xl hover:bg-rose-600 transition active:scale-95 shadow-md">
          {{ __('Login') }}
        </button>
      </form>

      {{-- Footer links --}}
      <div class="flex justify-between mt-6 text-sm text-gray-500">
        <a href="{{ route('vendor.forget.password') }}" class="hover:text-rose-500 transition">{{ __('Lupa password?') }}</a>
        <span>{{ __('Belum punya akun?') }} <a href="{{ route('vendor.signup') }}" class="text-airbnb-red font-bold hover:underline">{{ __('Daftar') }}</a></span>
      </div>
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
