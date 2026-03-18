<!-- Authentication Modals -->
<div x-data="{ 
        loginOpen: false, 
        registerOpen: false,
        toggleLogin() { this.loginOpen = !this.loginOpen; this.registerOpen = false; },
        toggleRegister() { this.registerOpen = !this.registerOpen; this.loginOpen = false; }
     }" 
     @open-login.window="loginOpen = true; registerOpen = false"
     @open-register.window="registerOpen = true; loginOpen = false"
     x-cloak>

    <!-- Login Modal -->
    <div x-show="loginOpen" 
         class="fixed inset-0 z-[100] overflow-y-auto" 
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay -->
            <div x-show="loginOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 @click="loginOpen = false"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Panel -->
            <div x-show="loginOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-6 border-b pb-4">
                        <button @click="loginOpen = false" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-times"></i>
                        </button>
                        <h3 class="text-lg font-bold text-center flex-1">{{ __('Log in') }}</h3>
                        <div class="w-6"></div>
                    </div>

                    <div class="mt-3">
                        <h2 class="text-2xl font-bold mb-6">{{ __('Welcome to Gofishi') }}</h2>
                        
                        <form action="{{ route('user.login_submit') }}" method="POST">
                            @csrf
                            <div class="space-y-4">
                                <div class="relative">
                                    <input type="text" name="username" required
                                           class="peer w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-transparent focus:outline-none focus:ring-2 focus:ring-[#FF385C] focus:border-transparent" 
                                           placeholder="Email/Username" id="login_username">
                                    <label for="login_username" class="absolute left-4 -top-2.5 bg-white px-1 text-xs text-gray-600 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:top-3 peer-focus:-top-2.5 peer-focus:text-xs">
                                        {{ __('Email or Username') }}
                                    </label>
                                </div>

                                <div class="relative">
                                    <input type="password" name="password" required
                                           class="peer w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-transparent focus:outline-none focus:ring-2 focus:ring-[#FF385C] focus:border-transparent" 
                                           placeholder="Password" id="login_password">
                                    <label for="login_password" class="absolute left-4 -top-2.5 bg-white px-1 text-xs text-gray-600 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:top-3 peer-focus:-top-2.5 peer-focus:text-xs">
                                        {{ __('Password') }}
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="mt-6 w-full bg-[#FF385C] text-white py-3 rounded-lg font-bold text-lg hover:bg-[#D90B63] transition">
                                {{ __('Continue') }}
                            </button>
                        </form>

                        {{-- <div class="relative my-8">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-gray-500">or</span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <a href="{{ route('user.login.provider', ['provider' => 'facebook']) }}" class="flex items-center justify-between w-full px-4 py-3 border border-gray-900 rounded-lg hover:bg-gray-50 transition">
                                <i class="fab fa-facebook text-[#1877F2] text-xl"></i>
                                <span class="font-bold flex-1 text-center">{{ __('Continue with Facebook') }}</span>
                                <div class="w-5"></div>
                            </a>
                            <a href="{{ route('user.login.provider', ['provider' => 'google']) }}" class="flex items-center justify-between w-full px-4 py-3 border border-gray-900 rounded-lg hover:bg-gray-50 transition">
                                <i class="fab fa-google text-xl"></i>
                                <span class="font-bold flex-1 text-center">{{ __('Continue with Google') }}</span>
                                <div class="w-5"></div>
                            </a>
                        </div> --}}
                        
                        <div class="mt-6 text-center text-sm">
                            {{ __("Don't have an account?") }}
                            <button @click="toggleRegister()" class="font-bold underline">{{ __('Sign up') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div x-show="registerOpen" 
         class="fixed inset-0 z-[100] overflow-y-auto" 
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay -->
            <div x-show="registerOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 @click="registerOpen = false"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Panel -->
            <div x-show="registerOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-6 border-b pb-4">
                        <button @click="registerOpen = false" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-times"></i>
                        </button>
                        <h3 class="text-lg font-bold text-center flex-1">{{ __('Sign up') }}</h3>
                        <div class="w-6"></div>
                    </div>

                    <div class="mt-3 overflow-y-auto max-h-[70vh]">
                        <h2 class="text-2xl font-bold mb-6">{{ __('Create your account') }}</h2>
                        
                        <form action="{{ route('user.signup_submit') }}" method="POST">
                            @csrf
                            <div class="space-y-4">
                                <div class="relative">
                                    <input type="text" name="username" required
                                           class="peer w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-transparent focus:outline-none focus:ring-2 focus:ring-[#FF385C] focus:border-transparent" 
                                           placeholder="Username" id="reg_username">
                                    <label for="reg_username" class="absolute left-4 -top-2.5 bg-white px-1 text-xs text-gray-600 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:top-3 peer-focus:-top-2.5 peer-focus:text-xs">
                                        {{ __('Username') }}
                                    </label>
                                </div>

                                <div class="relative">
                                    <input type="email" name="email" required
                                           class="peer w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-transparent focus:outline-none focus:ring-2 focus:ring-[#FF385C] focus:border-transparent" 
                                           placeholder="Email" id="reg_email">
                                    <label for="reg_email" class="absolute left-4 -top-2.5 bg-white px-1 text-xs text-gray-600 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:top-3 peer-focus:-top-2.5 peer-focus:text-xs">
                                        {{ __('Email') }}
                                    </label>
                                </div>

                                <div class="relative">
                                    <input type="password" name="password" required
                                           class="peer w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-transparent focus:outline-none focus:ring-2 focus:ring-[#FF385C] focus:border-transparent" 
                                           placeholder="Password" id="reg_password">
                                    <label for="reg_password" class="absolute left-4 -top-2.5 bg-white px-1 text-xs text-gray-600 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:top-3 peer-focus:-top-2.5 peer-focus:text-xs">
                                        {{ __('Password') }}
                                    </label>
                                </div>

                                <div class="relative">
                                    <input type="password" name="password_confirmation" required
                                           class="peer w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-transparent focus:outline-none focus:ring-2 focus:ring-[#FF385C] focus:border-transparent" 
                                           placeholder="Confirm Password" id="reg_confirm">
                                    <label for="reg_confirm" class="absolute left-4 -top-2.5 bg-white px-1 text-xs text-gray-600 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:top-3 peer-focus:-top-2.5 peer-focus:text-xs">
                                        {{ __('Confirm Password') }}
                                    </label>
                                </div>

                                <div class="relative pt-2">
                                    <label class="block text-sm font-bold text-gray-700 mb-1">{{ __('Birthdate') }}</label>
                                    <input type="date" name="dob" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF385C]">
                                    <p class="text-xs text-gray-500 mt-1">{{ __('To register, you must be at least 17 years old.') }}</p>
                                </div>

                                <div class="flex items-start gap-3 pt-2">
                                    <input type="checkbox" name="age_agreement" id="age_agreement" required class="mt-1">
                                    <label for="age_agreement" class="text-xs text-gray-600">
                                        {{ __('I agree to the Terms of Service, Payments Terms of Service, and Nondiscrimination Policy and acknowledge the Privacy Policy.') }}
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="mt-6 w-full bg-[#FF385C] text-white py-3 rounded-lg font-bold text-lg hover:bg-[#D90B63] transition">
                                {{ __('Agree and continue') }}
                            </button>
                        </form>
                        
                        <div class="mt-6 text-center text-sm border-t pt-4">
                            {{ __("Already have an account?") }}
                            <button @click="toggleLogin()" class="font-bold underline">{{ __('Log in') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
