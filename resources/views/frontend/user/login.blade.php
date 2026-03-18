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
          <input
            id="username"
            name="username"
            type="text"
            required
            value="{{ old('username') }}"
            class="appearance-none rounded-none rounded-t-md relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-airbnb-red focus:border-airbnb-red focus:z-10 sm:text-sm transition"
            placeholder="{{ __('Username or Email address') }}"
          >
          @error('username')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label for="password" class="sr-only">{{ __('Password') }}</label>
          <input
            id="password"
            name="password"
            type="password"
            required
            class="appearance-none rounded-none rounded-b-md relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-airbnb-red focus:border-airbnb-red focus:z-10 sm:text-sm transition"
            placeholder="{{ __('Password') }}"
          >
          @error('password')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <input
            id="remember-me"
            name="remember-me"
            type="checkbox"
            class="h-4 w-4 text-airbnb-red focus:ring-airbnb-red border-gray-300 rounded cursor-pointer"
          >
          <label for="remember-me" class="ml-2 block text-sm text-gray-900 cursor-pointer">
            {{ __('Remember me') }}
          </label>
        </div>

        <div class="text-sm">
          <a href="{{ route('user.forget_password') }}" class="font-medium text-airbnb-red hover:text-red-600 transition">
            {{ __('Forgot password?') }}
          </a>
        </div>
      </div>

      <div>
        <button
          type="submit"
          class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-airbnb-red hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-airbnb-red transition-all shadow-md active:scale-95"
        >
          {{ __('Sign in') }}
        </button>
      </div>
    </form>

    <div class="relative my-8">
        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
        <div class="relative flex justify-center text-sm"><span class="px-2 bg-white text-gray-500">{{ __('or') }}</span></div>
    </div>

    <a href="#" class="w-full rounded-lg flex items-center justify-center gap-4 py-3 bg-white border border-black hover:bg-gray-50 transition active:scale-95">
        <i class="fab fa-github text-xl"></i>
        <span class="font-semibold text-gray-900">{{ __('Continue with Github') }}</span>
    </a>
  </div>
</div>
@endsection
