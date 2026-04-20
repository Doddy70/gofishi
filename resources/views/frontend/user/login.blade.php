@extends('frontend.layout-airbnb')

@section('pageHeading')
  {{ !empty($pageHeading) ? $pageHeading->login_page_title : __('Login') }}
@endsection

@section('content')
  <div class="min-h-[80vh] flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-lg border border-gray-100">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          {{ __('Welcome back') }}
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          {{ __('Or') }}
          <a href="{{ route('user.signup') }}" class="font-medium text-airbnb-red hover:text-red-600 transition">
            {{ __('create a new account') }}
          </a>
        </p>
      </div>

      @if (Session::has('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
          <p class="text-sm text-green-700">{{ __(Session::get('success')) }}</p>
        </div>
      @endif
      @if (Session::has('error'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
          <p class="text-sm text-red-700">{{ __(Session::get('error')) }}</p>
        </div>
      @endif

      <form class="mt-8 space-y-6" action="{{ route('user.login_submit') }}" method="POST">
        @csrf
        <div class="rounded-md shadow-sm -space-y-px">
          <div>
            <label for="username" class="sr-only">{{ __('Username or Email') }}</label>
            <input id="username" name="username" type="text" required value="{{ old('username') }}"
              class="appearance-none rounded-none rounded-t-md relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-airbnb-red focus:border-airbnb-red focus:z-10 sm:text-sm transition"
              placeholder="{{ __('Username or Email address') }}">
            @error('username')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label for="password" class="sr-only">{{ __('Password') }}</label>
            <input id="password" name="password" type="password" required
              class="appearance-none rounded-none rounded-b-md relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-airbnb-red focus:border-airbnb-red focus:z-10 sm:text-sm transition"
              placeholder="{{ __('Password') }}">
            @error('password')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input id="remember-me" name="remember-me" type="checkbox"
              class="h-4 w-4 text-airbnb-red focus:ring-airbnb-red border-gray-300 rounded cursor-pointer">
            <label for="remember-me" class="ml-2 block text-sm text-gray-900 cursor-pointer">
              {{ __('Remember me') }}
            </label>
          </div>

          <div class="text-sm">
            <a href="{{ route('user.forget_password') }}"
              class="font-medium text-airbnb-red hover:text-red-600 transition">
              {{ __('Forgot password?') }}
            </a>
          </div>
        </div>

        <div>
          <button type="submit"
            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-airbnb-red hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-airbnb-red transition-all shadow-md active:scale-95">
            {{ __('Sign in') }}
          </button>
        </div>
      </form>

      <div class="relative my-8">
        <div class="absolute inset-0 flex items-center">
          <div class="w-full border-t border-gray-200"></div>
        </div>
        <div class="relative flex justify-center text-sm"><span class="px-2 bg-white text-gray-500">{{ __('or') }}</span>
        </div>
      </div>

      @if (!empty($bs) && ($bs->facebook_login_status == 1 || $bs->google_login_status == 1))
      <div class="flex flex-col gap-4">
        @if ($bs->facebook_login_status == 1)
        <a href="{{ route('user.login.provider', ['provider' => 'facebook']) }}"
          class="flex items-center justify-between w-full p-4 border-2 border-neutral-200 rounded-2xl hover:border-black transition-all group">
          <i class="fab fa-facebook text-[#1BC7F2] text-2xl group-hover:scale-110 transition-transform"></i>
          <span
            class="font-bold text-[15px] flex-1 text-center text-neutral-800">{{ __('Lanjutkan dengan Facebook') }}</span>
          <div class="w-8"></div>
        </a>
        @endif

        @if ($bs->google_login_status == 1)
        <a href="{{ route('user.login.provider', ['provider' => 'google']) }}"
          class="flex items-center justify-between w-full p-4 border-2 border-neutral-200 rounded-2xl hover:border-black transition-all group">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 18 18"
            class="group-hover:scale-110 transition-transform">
            <path fill="#4285F4"
              d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.874 2.684-6.615z" />
            <path fill="#34A853"
              d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.258c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18z" />
            <path fill="#FBBC05"
              d="M3.964 10.707c-.18-.54-.282-1.117-.282-1.707s.102-1.167.282-1.707V4.96H.957A8.996 8.996 0 0 0 0 9c0 1.491.366 2.9 1.008 4.141l2.956-2.434z" />
            <path fill="#EA4335"
              d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.582C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.958L3.964 7.29c.708-2.127 2.692-3.71 5.036-3.71z" />
          </svg>
          <span
            class="font-bold text-[15px] flex-1 text-center text-neutral-800">{{ __('Lanjutkan dengan Google') }}</span>
          <div class="w-8"></div>
        </a>
        @endif
      </div>
      @endif
    </div>
  </div>
@endsection