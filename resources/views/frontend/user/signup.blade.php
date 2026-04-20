@extends('frontend.layout-airbnb')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->signup_page_title ? $pageHeading->signup_page_title : __('Signup') }}
  @else
    {{ __('Signup') }}
  @endif
@endsection

@section('content')
<div class="min-h-[80vh] flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
  @php
    if ($errors->hasAny(['username', 'email'])) {
      $signupInitialStep = 1;
    } elseif ($errors->hasAny(['password', 'password_confirmation', 'dob'])) {
      $signupInitialStep = 2;
    } elseif ($errors->has('age_agreement')) {
      $signupInitialStep = 3;
    } else {
      $signupInitialStep = 1;
    }
  @endphp
  <div
    class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-lg border border-gray-100"
    x-data="{ step: {{ $signupInitialStep }}, username: @json(old('username', '')), email: @json(old('email', '')), password: '', password_confirmation: '', dob: @json(old('dob', '')), ageAgreement: @json(old('age_agreement', false)) }"
  >
    <div>
      <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
        {{ __('Create your account') }}
      </h2>
      <p class="mt-2 text-center text-sm text-gray-600">
        {{ __('Already have an account?') }}
        <a href="{{ route('user.login', request()->query()) }}" class="font-medium text-airbnb-red hover:text-red-600 transition">
          {{ __('Sign in') }}
        </a>
      </p>
    </div>

    @if (Session::has('success'))
      <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
        <p class="text-sm text-green-700">{{ __(Session::get('success')) }}</p>
      </div>
    @endif

    <form
      class="mt-8 space-y-6"
      action="{{ route('user.signup_submit') }}"
      method="POST"
      @submit="if (step !== 3) { $event.preventDefault(); }"
    >
      @csrf

      <div class="grid grid-cols-3 gap-3 text-center">
        <button type="button" @click="step = 1" class="rounded-full py-3 font-semibold transition" :class="step === 1 ? 'bg-airbnb-red text-white' : 'bg-gray-200 text-gray-600'">1</button>
        <button type="button" @click="step = 2" class="rounded-full py-3 font-semibold transition" :class="step === 2 ? 'bg-airbnb-red text-white' : 'bg-gray-200 text-gray-600'">2</button>
        <button type="button" @click="step = 3" class="rounded-full py-3 font-semibold transition" :class="step === 3 ? 'bg-airbnb-red text-white' : 'bg-gray-200 text-gray-600'">3</button>
      </div>

      <div class="flex items-center justify-between text-sm text-gray-500">
        <span x-text="step === 1 ? '{{ __('Akun') }}' : (step === 2 ? '{{ __('Detail') }}' : '{{ __('Konfirmasi') }}')"></span>
        <span>{{ __('Step') }} <span x-text="step"></span> / 3</span>
      </div>

      <div x-show="step === 1" class="space-y-4" x-cloak>
        <p class="text-sm text-gray-500">{{ __('Langkah 1 dari 3: Isi username dan email Anda') }}</p>
        <div>
          <label for="username" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Username') }}</label>
          <input
            id="username"
            name="username"
            type="text"
            autocomplete="username"
            x-model="username"
            value="{{ old('username') }}"
            class="appearance-none rounded-md relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-airbnb-red focus:border-airbnb-red sm:text-sm transition"
            placeholder="johndoe"
          >
          @error('username')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email address') }}</label>
          <input
            id="email"
            name="email"
            type="email"
            autocomplete="email"
            x-model="email"
            value="{{ old('email') }}"
            class="appearance-none rounded-md relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-airbnb-red focus:border-airbnb-red sm:text-sm transition"
            placeholder="john.doe@example.com"
          >
          @error('email')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <button
          type="button"
          @click="
            if (!username || !email) { alert(@json(__('Isi username dan email terlebih dahulu.'))); return; }
            step = 2;
          "
          class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-airbnb-red hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-airbnb-red transition-all shadow-md"
        >
          {{ __('Lanjutkan') }}
        </button>
      </div>

      {{-- Fase 2: password dan tanggal lahir --}}
      <div x-show="step === 2" class="space-y-4" x-cloak>
        <p class="text-sm text-gray-500">{{ __('Langkah 2 dari 3: Buat password dan isi tanggal lahir') }}</p>

        <div class="grid grid-cols-2 gap-4">
            <div>
              <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Password') }}</label>
              <input
                id="password"
                name="password"
                type="password"
                autocomplete="new-password"
                x-model="password"
                required
                class="appearance-none rounded-md relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-airbnb-red focus:border-airbnb-red sm:text-sm transition"
                placeholder="••••••••"
              >
            </div>
            <div>
              <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Confirm') }}</label>
              <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                autocomplete="new-password"
                x-model="password_confirmation"
                required
                class="appearance-none rounded-md relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-airbnb-red focus:border-airbnb-red sm:text-sm transition"
                placeholder="••••••••"
              >
            </div>
        </div>
        @error('password')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror

        <div>
          <label for="dob" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Date of Birth') }}</label>
          <input
            id="dob"
            name="dob"
            type="date"
            required
            x-model="dob"
            value="{{ old('dob') }}"
            class="appearance-none rounded-md relative block w-full px-4 py-3 border border-gray-300 text-gray-900 focus:outline-none focus:ring-airbnb-red focus:border-airbnb-red sm:text-sm transition"
          >
          @error('dob')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div class="flex gap-3">
          <button
            type="button"
            @click="step = 1"
            class="flex-1 py-3 px-4 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition"
          >
            {{ __('Kembali') }}
          </button>
          <button
            type="button"
            @click="if (!password || !password_confirmation || !dob) { alert(@json(__('Isi semua bidang sebelum melanjutkan.'))); return; } if (password !== password_confirmation) { alert(@json(__('Password dan konfirmasi password harus sama.'))); return; } step = 3;"
            class="flex-[2] py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-airbnb-red hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-airbnb-red transition-all shadow-md"
          >
            {{ __('Lanjutkan') }}
          </button>
        </div>
      </div>

      <div x-show="step === 3" class="space-y-4" x-cloak>
        <p class="text-sm text-gray-500">{{ __('Langkah 3 dari 3: Konfirmasi data Anda dan kirim.') }}</p>

        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5 space-y-4">
          <div>
            <h3 class="font-semibold text-gray-900">{{ __('Ringkasan Akun') }}</h3>
            <p class="text-sm text-gray-600">{{ __('Periksa kembali informasi yang sudah dimasukkan sebelum melanjutkan.') }}</p>
          </div>
          <div class="grid grid-cols-1 gap-3">
            <div class="flex justify-between text-sm text-gray-700">
              <span>{{ __('Username') }}</span>
              <span x-text="username || '-'" class="font-semibold"></span>
            </div>
            <div class="flex justify-between text-sm text-gray-700">
              <span>{{ __('Email') }}</span>
              <span x-text="email || '-'" class="font-semibold"></span>
            </div>
            <div class="flex justify-between text-sm text-gray-700">
              <span>{{ __('Tanggal Lahir') }}</span>
              <span x-text="dob || '-'" class="font-semibold"></span>
            </div>
          </div>
        </div>

        <div class="flex items-start">
          <div class="flex items-center h-5">
            <input
              id="age_agreement"
              name="age_agreement"
              type="checkbox"
              value="1"
              x-model="ageAgreement"
              required
              class="h-4 w-4 text-airbnb-red focus:ring-airbnb-red border-gray-300 rounded cursor-pointer"
            >
          </div>
          <div class="ml-3 text-sm">
            <label for="age_agreement" class="font-light text-gray-600 cursor-pointer">
              {{ __('Saya menyatakan bahwa saya berusia 17 tahun atau lebih dan menyetujui syarat & ketentuan.') }}
            </label>
          </div>
        </div>
        @error('age_agreement')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror

        <div class="flex justify-between gap-3">
          <button
            type="button"
            @click="step = 2"
            class="flex-1 py-3 px-4 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition"
          >
            {{ __('Kembali') }}
          </button>
          <button
            type="submit"
            class="flex-[2] py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-airbnb-red hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-airbnb-red transition-all shadow-md"
          >
            {{ __('Create account') }}
          </button>
        </div>
      </div>
    </form>

    <p class="text-xs text-center text-gray-500 leading-relaxed">
        {{ __('By signing up, you agree to our') }}
        <a href="#" class="text-airbnb-red hover:underline">{{ __('Terms of Service') }}</a>
        {{ __('and') }}
        <a href="#" class="text-airbnb-red hover:underline">{{ __('Privacy Policy') }}</a>
    </p>
  </div>
</div>
@endsection
