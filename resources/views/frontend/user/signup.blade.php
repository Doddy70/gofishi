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
  <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-lg border border-gray-100">
    <div>
      <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
        {{ __('Create your account') }}
      </h2>
      <p class="mt-2 text-center text-sm text-gray-600">
        {{ __('Already have an account?') }} 
        <a href="{{ route('user.login') }}" class="font-medium text-airbnb-red hover:text-red-600 transition">
          {{ __('Sign in') }}
        </a>
      </p>
    </div>

    @if (Session::has('success'))
      <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
        <p class="text-sm text-green-700">{{ __(Session::get('success')) }}</p>
      </div>
    @endif

    <form class="mt-8 space-y-6" action="{{ route('user.signup_submit') }}" method="POST">
      @csrf
      <div class="space-y-4">
        <div>
          <label for="username" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Username') }}</label>
          <input
            id="username"
            name="username"
            type="text"
            required
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
            required
            value="{{ old('email') }}"
            class="appearance-none rounded-md relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-airbnb-red focus:border-airbnb-red sm:text-sm transition"
            placeholder="john.doe@example.com"
          >
          @error('email')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
              <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Password') }}</label>
              <input
                id="password"
                name="password"
                type="password"
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
            value="{{ old('dob') }}"
            class="appearance-none rounded-md relative block w-full px-4 py-3 border border-gray-300 text-gray-900 focus:outline-none focus:ring-airbnb-red focus:border-airbnb-red sm:text-sm transition"
          >
          @error('dob')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div class="flex items-start">
          <div class="flex items-center h-5">
            <input
              id="age_agreement"
              name="age_agreement"
              type="checkbox"
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
      </div>

      <div>
        <button
          type="submit"
          class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-airbnb-red hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-airbnb-red transition-all shadow-md active:scale-95"
        >
          {{ __('Create account') }}
        </button>
      </div>

      <p class="text-xs text-center text-gray-500 leading-relaxed">
        {{ __('By signing up, you agree to our') }} 
        <a href="#" class="text-airbnb-red hover:underline">{{ __('Terms of Service') }}</a> 
        {{ __('and') }} 
        <a href="#" class="text-airbnb-red hover:underline">{{ __('Privacy Policy') }}</a>
      </p>
    </form>
  </div>
</div>
@endsection
