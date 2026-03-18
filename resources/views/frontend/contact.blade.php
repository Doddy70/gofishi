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
                    <p class="text-gray-500 text-sm font-light">{{ $info->contact_number }}</p>
                </div>
                <div class="p-8 bg-white border border-gray-100 rounded-3xl shadow-sm hover:shadow-md transition group">
                    <div class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center text-airbnb-red mb-6 group-hover:scale-110 transition">
                        <i data-lucide="mail" class="w-6 h-6"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">{{ __('Email Resmi') }}</h3>
                    <p class="text-gray-500 text-sm font-light">{{ $info->email_address }}</p>
                </div>
            </div>

            <div class="relative rounded-3xl overflow-hidden shadow-2xl h-[450px] border-8 border-white group">
                @if (!empty($info->latitude) && !empty($info->longitude))
                    <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
                            class="grayscale hover:grayscale-0 transition duration-1000"
                            src="https://maps.google.com/maps?q={{ $info->latitude }},{{ $info->longitude }}+({{ urlencode($websiteInfo->website_title) }})&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed">
                    </iframe>
                @endif
                <div class="absolute bottom-6 left-6 right-6 bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-xl flex items-center space-x-4">
                    <div class="w-10 h-10 bg-airbnb-red rounded-full flex items-center justify-center text-white shrink-0">
                        <i data-lucide="map-pin" class="w-5 h-5"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-800">{{ $info->address }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
