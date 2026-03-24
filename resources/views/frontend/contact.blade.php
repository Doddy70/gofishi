@extends('frontend.layout-airbnb')

@section('pageHeading')
  {{ !empty($pageHeading) ? $pageHeading->contact_page_title : __('Contact') }}
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-20">
        
        {{-- Contact Form Side --}}
        <div>
            <h1 class="text-4xl font-extrabold text-gray-900 mb-6 tracking-tight">
                {{ __('Hubungi Kami') }}
            </h1>
            <p class="text-lg text-gray-500 mb-12 font-light leading-relaxed">
                {{ __('Kami siap membantu Anda dalam merencanakan perjalanan memancing yang tak terlupakan. Kirimkan pesan melalui formulir di bawah ini.') }}
            </p>

            <form id="contactForm" action="{{ route('contact.send_mail') }}" method="post" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">{{ __('Nama Lengkap') }}</label>
                        <input type="text" name="name" value="{{ old('name') }}" 
                               class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-airbnb-red/20 focus:border-airbnb-red outline-none transition" 
                               placeholder="{{ __('E.g. Nama Anda') }}">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">{{ __('Email') }}</label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                               class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-airbnb-red/20 focus:border-airbnb-red outline-none transition" 
                               placeholder="{{ __('E.g. email@example.com') }}">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700">{{ __('Subjek') }}</label>
                    <input type="text" name="subject" value="{{ old('subject') }}" 
                           class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-airbnb-red/20 focus:border-airbnb-red outline-none transition" 
                           placeholder="{{ __('Apa yang ingin Anda bicarakan?') }}">
                    @error('subject') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700">{{ __('Pesan') }}</label>
                    <textarea name="message" rows="6" 
                              class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-airbnb-red/20 focus:border-airbnb-red outline-none transition" 
                              placeholder="{{ __('Tuliskan pesan Anda di sini...') }}"></textarea>
                    @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                @if ($info->google_recaptcha_status == 1)
                    <div class="py-2">
                        {!! NoCaptcha::renderJs() !!}
                        {!! NoCaptcha::display() !!}
                        @error('g-recaptcha-response') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                @endif

                <button type="submit" class="w-full py-4 bg-airbnb-red text-white font-bold rounded-2xl hover:bg-rose-600 shadow-lg transition transform hover:scale-[1.01] active:scale-95">
                    {{ __('Kirim Pesan') }}
                </button>
            </form>
        </div>

        {{-- Info & Map Side --}}
        <div class="space-y-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="p-8 bg-white border border-gray-100 rounded-3xl shadow-sm hover:shadow-md transition group">
                    <div class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center text-airbnb-red mb-6 group-hover:scale-110 transition">
                        <i data-lucide="phone" class="w-6 h-6"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">{{ __('Hubungi Kami') }}</h3>
                    <p class="text-gray-500 text-sm font-light">+62 856-9877-491 (Rudi)</p>
                </div>
                <div class="p-8 bg-white border border-gray-100 rounded-3xl shadow-sm hover:shadow-md transition group">
                    <div class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center text-airbnb-red mb-6 group-hover:scale-110 transition">
                        <i data-lucide="mail" class="w-6 h-6"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">{{ __('Email Resmi') }}</h3>
                    <p class="text-gray-500 text-sm font-light">{{ $info->email_address }}</p>
                </div>
            </div>

            <div class="bg-gray-50 rounded-3xl p-10 border border-gray-100 flex flex-col justify-center space-y-8 shadow-sm">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ __('Terhubung Dengan Kami') }}</h3>
                    <p class="text-gray-500 font-light">{{ __('Punya pertanyaan mendesak atau butuh inspirasi liburan memancing berikutnya? Pantau terus jaringan sosial kami atau hubungi tim lapangan secara langsung.') }}</p>
                </div>

                <a href="https://wa.me/628569877491" target="_blank" class="flex items-center space-x-6 p-6 bg-white rounded-2xl shadow-sm border border-gray-100 hover:border-green-500/30 hover:shadow-md transition group">
                    <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center text-green-600 shrink-0 group-hover:scale-110 group-hover:bg-green-500 group-hover:text-white transition duration-300">
                        <i data-lucide="phone-call" class="w-7 h-7"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">{{ __('Nomer Telepon Lapangan') }}</p>
                        <p class="text-xl text-gray-900 font-bold group-hover:text-green-600 transition">+62 856-9877-491 <span class="text-sm font-medium text-gray-500">(Rudi)</span></p>
                    </div>
                </a>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <a href="https://www.youtube.com/" target="_blank" class="flex flex-col items-center justify-center p-6 bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 hover:border-red-200 transition group relative overflow-hidden">
                        <div class="absolute inset-0 bg-red-600 opacity-0 group-hover:opacity-5 transition duration-300"></div>
                        <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center text-red-600 mb-4 group-hover:bg-red-600 group-hover:text-white transition duration-300 z-10">
                            <i data-lucide="youtube" class="w-6 h-6"></i>
                        </div>
                        <span class="font-bold text-gray-900 group-hover:text-red-600 transition z-10">{{ __('YouTube Channel') }}</span>
                    </a>

                    <a href="https://www.instagram.com/gofishi/" target="_blank" class="flex flex-col items-center justify-center p-6 bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 hover:border-pink-200 transition group relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-500 opacity-0 group-hover:opacity-[0.03] transition duration-300"></div>
                        <div class="w-12 h-12 bg-pink-50 rounded-full flex items-center justify-center text-pink-600 mb-4 group-hover:bg-gradient-to-tr group-hover:from-yellow-400 group-hover:via-pink-500 group-hover:to-purple-500 group-hover:text-white transition duration-300 z-10">
                            <i data-lucide="instagram" class="w-6 h-6"></i>
                        </div>
                        <span class="font-bold text-gray-900 group-hover:text-pink-600 transition z-10">{{ __('Instagram') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
