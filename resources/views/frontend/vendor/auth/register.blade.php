@extends('frontend.layout-airbnb')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->vendor_signup_page_title ? $pageHeading->vendor_signup_page_title : __('Jadi Host') }}
  @else
    {{ __('Jadi Host') }}
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
<div class="min-h-screen bg-gray-50 py-12" x-data="{
    step: @if($errors->any())
            @if($errors->hasAny(['username', 'email', 'phone', 'password', 'dob', 'password_confirmation'])) 1
            @elseif($errors->hasAny(['fname', 'lname', 'address', 'city', 'state', 'zip_code', 'photo'])) 2
            @elseif($errors->hasAny(['ktp_file', 'boat_ownership_file', 'driving_license_file', 'self_photo_file'])) 3
            @else 4 @endif
          @else 1 @endif,
    formData: {
        username: '{{ old('username') }}',
        email: '{{ old('email') }}',
        phone: '{{ old('phone') }}',
        dob: '{{ old('dob') }}',
        password: '',
        password_confirmation: '',
        fname: '{{ old('fname') }}',
        lname: '{{ old('lname') }}',
        address: '{{ old('address') }}',
        city: '{{ old('city') }}',
        state: '{{ old('state') }}'
    },
    errors: {},
    validateField(name) {
        const value = this.formData[name];
        this.errors[name] = '';

        if (!value && name !== 'zip_code') {
            this.errors[name] = '{{ __('Field ini wajib diisi') }}';
            return;
        }

        if (name === 'email' && !/^\S+@\S+\.\S+$/.test(value)) {
            this.errors[name] = '{{ __('Format email tidak valid') }}';
        }

        if (name === 'password' && value.length < 8) {
            this.errors[name] = '{{ __('Kata sandi minimal 8 karakter') }}';
        }

        if (name === 'password_confirmation' && value !== this.formData.password) {
            this.errors[name] = '{{ __('Konfirmasi kata sandi tidak cocok') }}';
        }

        if (name === 'phone' && !/^[0-9+]{10,15}$/.test(value)) {
            this.errors[name] = '{{ __('Nomor telepon tidak valid') }}';
        }
    },
    isStepValid(currentStep) {
        const fields = {
            1: ['username', 'email', 'phone', 'password', 'password_confirmation', 'dob'],
            2: ['fname', 'lname', 'address', 'city', 'state'],
            3: ['ktp_file', 'boat_ownership_file', 'driving_license_file', 'self_photo_file']
        };
        
        if (!fields[currentStep]) return true;
        
        const form = this.$refs.signupForm;
        return fields[currentStep].every(field => {
            if (currentStep === 3) {
                return form.elements[field].files.length > 0;
            }
            this.validateField(field);
            return this.formData[field] && !this.errors[field];
        });
    },
    nextStep() {
        if (this.isStepValid(this.step)) {
            this.step++;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }
}">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="text-center mb-12">
      <h1 class="text-4xl font-extrabold text-gray-900 mb-4">{{ __('Daftar sebagai Host') }}</h1>
      <p class="text-lg text-gray-600 font-light">{{ __('Bagikan kapal Anda dan kembangkan bisnis sewa di GoFishi.') }}</p>
    </div>

    @if (!empty($bs) && ($bs->google_login_status == 1 || $bs->facebook_login_status == 1))
      <div class="max-w-2xl mx-auto mb-10">
        <p class="text-center text-sm text-gray-500 mb-4">{{ __('Daftar lebih cepat menggunakan akun sosial Anda.') }}</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
          @if ($bs->google_login_status == 1)
            <a href="{{ route('vendor.login.provider', ['provider' => 'google']) }}" class="w-full inline-flex items-center justify-center gap-3 rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
              <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" class="h-5 w-5" />
              {{ __('Lanjut dengan Google') }}
            </a>
          @endif
          @if ($bs->facebook_login_status == 1)
            <a href="{{ route('vendor.login.provider', ['provider' => 'facebook']) }}" class="w-full inline-flex items-center justify-center gap-3 rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
              <img src="https://www.svgrepo.com/show/452223/facebook-color.svg" alt="Facebook" class="h-5 w-5" />
              {{ __('Lanjut dengan Facebook') }}
            </a>
          @endif
        </div>
      </div>
    @endif

    {{-- Progress Stepper --}}
    <div class="mb-12">
      <div class="flex items-center justify-between max-w-2xl mx-auto">
        <template x-for="i in [1, 2, 3, 4]" :key="i">
            <div class="flex items-center flex-1 last:flex-none">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-colors duration-300"
                         :class="step >= i ? 'bg-airbnb-red text-white' : 'bg-gray-200 text-gray-400'">
                        <span x-show="step <= i" x-text="i"></span>
                        <i x-show="step > i" data-lucide="check" class="w-5 h-5"></i>
                    </div>
                    <span class="mt-2 text-[10px] md:text-xs font-semibold uppercase tracking-wider text-center"
                          :class="step >= i ? 'text-gray-900' : 'text-gray-400'"
                          x-text="i == 1 ? 'Akun' : (i == 2 ? 'Profil' : (i == 3 ? 'Dokumen' : 'Selesai'))"></span>
                </div>
                <div x-show="i < 4" class="flex-1 h-1 mx-4 -mt-6 transition-colors duration-300"
                     :class="step > i ? 'bg-airbnb-red' : 'bg-gray-200'"></div>
            </div>
        </template>
      </div>
    </div>

    {{-- Form Container --}}
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
      @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 m-8 mb-0 rounded-r-xl">
           <div class="flex items-center gap-3">
              <i data-lucide="alert-circle" class="text-red-500 w-5 h-5"></i>
              <p class="text-sm text-red-700 font-bold">{{ __('Ada beberapa masalah pada input Anda. Silakan periksa kembali setiap langkah.') }}</p>
           </div>
        </div>
      @endif

      <form x-ref="signupForm" action="{{ route('vendor.signup_submit') }}" method="POST" enctype="multipart/form-data" class="p-8 sm:p-12">
        @csrf
        
        {{-- Step 1: Account Details --}}
        <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0">
            <h2 class="text-2xl font-bold text-gray-900 mb-8 flex items-center gap-3">
                <i data-lucide="user" class="text-airbnb-red"></i>
                {{ __('Informasi Akun') }}
            </h2>
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-sm font-bold text-gray-700">{{ __('Nama Pengguna') }}*</label>
                        <input type="text" name="username" x-model="formData.username" @input="validateField('username')"
                               :class="errors.username ? 'ring-2 ring-red-500' : 'focus:ring-2 focus:ring-rose-500'"
                               class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 transition"
                               placeholder="captain_jack">
                        <p x-show="errors.username" x-text="errors.username" class="text-red-500 text-xs mt-1" x-cloak></p>
                        @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-bold text-gray-700">{{ __('Alamat Email') }}*</label>
                        <input type="email" name="email" x-model="formData.email" @input="validateField('email')"
                               :class="errors.email ? 'ring-2 ring-red-500' : 'focus:ring-2 focus:ring-rose-500'"
                               class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 transition"
                               placeholder="jack@example.com">
                        <p x-show="errors.email" x-text="errors.email" class="text-red-500 text-xs mt-1" x-cloak></p>
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-sm font-bold text-gray-700">{{ __('Nomor Telepon') }}*</label>
                        <input type="tel" name="phone" x-model="formData.phone" @input="validateField('phone')"
                               :class="errors.phone ? 'ring-2 ring-red-500' : 'focus:ring-2 focus:ring-rose-500'"
                               class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 transition"
                               placeholder="08123456789">
                        <p x-show="errors.phone" x-text="errors.phone" class="text-red-500 text-xs mt-1" x-cloak></p>
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-bold text-gray-700">{{ __('Tanggal Lahir') }}*</label>
                        <input type="date" name="dob" x-model="formData.dob" @input="validateField('dob')"
                               :class="errors.dob ? 'ring-2 ring-red-500' : 'focus:ring-2 focus:ring-rose-500'"
                               class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 transition">
                        <p x-show="errors.dob" x-text="errors.dob" class="text-red-500 text-xs mt-1" x-cloak></p>
                        @error('dob') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-sm font-bold text-gray-700">{{ __('Kata Sandi') }}*</label>
                        <input type="password" name="password" x-model="formData.password" @input="validateField('password')"
                               :class="errors.password ? 'ring-2 ring-red-500' : 'focus:ring-2 focus:ring-rose-500'"
                               class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 transition"
                               placeholder="••••••••">
                        <p x-show="errors.password" x-text="errors.password" class="text-red-500 text-xs mt-1" x-cloak></p>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-bold text-gray-700">{{ __('Konfirmasi Kata Sandi') }}*</label>
                        <input type="password" name="password_confirmation" x-model="formData.password_confirmation" @input="validateField('password_confirmation')"
                               :class="errors.password_confirmation ? 'ring-2 ring-red-500' : 'focus:ring-2 focus:ring-rose-500'"
                               class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 transition"
                               placeholder="••••••••">
                        <p x-show="errors.password_confirmation" x-text="errors.password_confirmation" class="text-red-500 text-xs mt-1" x-cloak></p>
                    </div>
                </div>
            </div>
            <div class="mt-12 flex justify-end">
                <button type="button" @click="nextStep()" class="bg-gray-900 text-white px-10 py-3 rounded-xl font-bold hover:bg-black transition active:scale-95 shadow-lg">
                    {{ __('Langkah Berikutnya') }}
                </button>
            </div>
        </div>

        {{-- Step 2: Profile Details --}}
        <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
            <h2 class="text-2xl font-bold text-gray-900 mb-8 flex items-center gap-3">
                <i data-lucide="image" class="text-airbnb-red"></i>
                {{ __('Profil Anda') }}
            </h2>
            <p class="text-gray-500 mb-8 font-light italic text-sm">{{ __('Beritahu tamu lokasi kapal Anda dan buat profil profesional Anda.') }}</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1">
                    <label class="text-sm font-bold text-gray-700">{{ __('First Name') }}*</label>
                    <input type="text" name="fname" x-model="formData.fname" @input="validateField('fname')"
                           :class="errors.fname ? 'ring-2 ring-red-500' : 'focus:ring-2 focus:ring-rose-500'"
                           class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 transition"
                           placeholder="Jack">
                    <p x-show="errors.fname" x-text="errors.fname" class="text-red-500 text-xs mt-1" x-cloak></p>
                    @error('fname') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label class="text-sm font-bold text-gray-700">{{ __('Last Name') }}*</label>
                    <input type="text" name="lname" x-model="formData.lname" @input="validateField('lname')"
                           :class="errors.lname ? 'ring-2 ring-red-500' : 'focus:ring-2 focus:ring-rose-500'"
                           class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 transition"
                           placeholder="Sparrow">
                    <p x-show="errors.lname" x-text="errors.lname" class="text-red-500 text-xs mt-1" x-cloak></p>
                    @error('lname') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div class="space-y-1">
                    <label class="text-sm font-bold text-gray-700">{{ __('Alamat Lokasi Kapal') }}*</label>
                    <textarea name="address" x-model="formData.address" @input="validateField('address')"
                              :class="errors.address ? 'ring-2 ring-red-500' : 'focus:ring-2 focus:ring-rose-500'"
                              rows="3" class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 transition"
                              placeholder="Jalan Pantai Indah No. 15"></textarea>
                    <p x-show="errors.address" x-text="errors.address" class="text-red-500 text-xs mt-1" x-cloak></p>
                    @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label class="text-sm font-bold text-gray-700">{{ __('Kota') }}*</label>
                    <input type="text" name="city" x-model="formData.city" @input="validateField('city')"
                           :class="errors.city ? 'ring-2 ring-red-500' : 'focus:ring-2 focus:ring-rose-500'"
                           class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 transition"
                           placeholder="Bali">
                    <p x-show="errors.city" x-text="errors.city" class="text-red-500 text-xs mt-1" x-cloak></p>
                    @error('city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div class="space-y-1">
                    <label class="text-sm font-bold text-gray-700">{{ __('Provinsi / Wilayah') }}*</label>
                    <input type="text" name="state" x-model="formData.state" @input="validateField('state')"
                           :class="errors.state ? 'ring-2 ring-red-500' : 'focus:ring-2 focus:ring-rose-500'"
                           class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 transition"
                           placeholder="Nusa Tenggara Barat">
                    <p x-show="errors.state" x-text="errors.state" class="text-red-500 text-xs mt-1" x-cloak></p>
                    @error('state') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label class="text-sm font-bold text-gray-700">{{ __('Kode Pos') }}</label>
                    <input type="text" name="zip_code" x-model="formData.zip_code"
                           class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 focus:ring-2 focus:ring-rose-500 transition"
                           placeholder="12345">
                    @error('zip_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>       </div>
            </div>

            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700">{{ __('Profile Photo') }}</label>
                    <div class="relative border-2 border-dashed border-gray-200 rounded-2xl p-4 hover:border-rose-300 transition group">
                        <input type="file" name="photo" class="absolute inset-0 opacity-0 cursor-pointer z-10" accept="image/*">
                        <div class="text-center">
                            <i data-lucide="camera" class="w-8 h-8 mx-auto text-gray-300 group-hover:text-rose-400 transition"></i>
                            <p class="text-xs text-gray-400 mt-2">{{ __('Opsional: unggah foto profil') }}</p>
                        </div>
                    </div>
                    @error('photo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-12 flex justify-between">
                <button type="button" @click="step = 1" class="text-gray-500 font-bold hover:text-black transition">
                    {{ __('Kembali') }}
                </button>
                <button type="button" @click="nextStep()" class="bg-gray-900 text-white px-10 py-3 rounded-xl font-bold hover:bg-black transition active:scale-95 shadow-lg">
                    {{ __('Langkah Berikutnya') }}
                </button>
            </div>
        </div>

        {{-- Step 3: Documents --}}
        <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
            <h2 class="text-2xl font-bold text-gray-900 mb-8 flex items-center gap-3">
                <i data-lucide="file-text" class="text-airbnb-red"></i>
                {{ __('Verification Documents') }}
            </h2>
            <p class="text-gray-500 mb-8 font-light italic text-sm">{{ __('Upload the documents required to verify your boat and captain credentials.') }}</p>
            
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
                <button type="button" @click="step = 2" class="text-gray-500 font-bold hover:text-black transition">
                    {{ __('Kembali') }}
                </button>
                <button type="button" @click="nextStep()" class="bg-gray-900 text-white px-10 py-3 rounded-xl font-bold hover:bg-black transition active:scale-95 shadow-lg">
                    {{ __('Langkah Berikutnya') }}
                </button>
            </div>
        </div>

        {{-- Step 4: Finalize --}}
        <div x-show="step === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
            <h2 class="text-2xl font-bold text-gray-900 mb-8 flex items-center gap-3">
                <i data-lucide="check-circle" class="text-airbnb-red"></i>
                {{ __('Langkah Terakhir') }}
            </h2>

            <div class="space-y-6 bg-gray-50 p-8 rounded-2xl border border-gray-100">
                <div class="flex items-start gap-4">
                    <input type="checkbox" name="age_agreement" id="age_agreement" required class="mt-1 h-5 w-5 text-airbnb-red focus:ring-rose-500 border-gray-300 rounded cursor-pointer">
                    <label for="age_agreement" class="text-sm text-gray-700 font-bold cursor-pointer">
                        {{ __('Saya menyatakan bahwa saya berusia 17 tahun atau lebih.') }}*
                    </label>
                </div>
                @error('age_agreement') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                <hr class="border-gray-200">

                <div class="flex items-start gap-4">
                    <input type="checkbox" name="terms_and_conditions" id="terms_and_conditions" required class="mt-1 h-5 w-5 text-airbnb-red focus:ring-rose-500 border-gray-300 rounded cursor-pointer">
                    <label for="terms_and_conditions" class="text-sm text-gray-600 leading-relaxed cursor-pointer">
                        {{ __('Saya menyetujui') }} 
                        <a href="#" class="text-airbnb-red font-bold hover:underline">{{ __('Syarat & Ketentuan') }}</a> 
                        {{ __('dan') }} 
                        <a href="#" class="text-airbnb-red font-bold hover:underline">{{ __('Kebijakan Privasi') }}</a> {{ __('GoFishi.') }}*
                    </label>
                </div>
                @error('terms_and_conditions') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mt-12 flex justify-between items-center">
                <button type="button" @click="step = 3" class="text-gray-500 font-bold hover:text-black transition">
                    {{ __('Back') }}
                </button>
                <button type="submit" class="bg-airbnb-red text-white px-12 py-4 rounded-2xl font-black text-lg hover:bg-rose-600 transition active:scale-95 shadow-xl">
                    {{ __('Kirim Permintaan') }}
                </button>
            </div>
        </div>

      </form>
    </div>

    {{-- Footer Info --}}
    <div class="mt-8 text-center">
        <p class="text-sm text-gray-500">
            {{ __('Sudah punya akun?') }} 
            <a href="{{ route('vendor.login') }}" class="text-airbnb-red font-bold hover:underline">{{ __('Masuk Sekarang') }}</a>
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
