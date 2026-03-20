<!-- Simple & Clean Airbnb Style Footer -->
<footer class="bg-neutral-50 border-t border-neutral-200 pt-12 pb-8 mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 pb-12 border-b border-neutral-200">
            
            {{-- Column 1 --}}
            <div class="flex flex-col gap-4">
                <h4 class="text-sm font-bold text-neutral-900">{{ __('Dukungan') }}</h4>
                <ul class="flex flex-col gap-3 text-sm text-neutral-600 font-light">
                    <li><a href="#" class="hover:underline">{{ __('Pusat Bantuan') }}</a></li>
                    <li><a href="#" class="hover:underline">{{ __('AirCover') }}</a></li>
                    <li><a href="#" class="hover:underline">{{ __('Anti-diskriminasi') }}</a></li>
                    <li><a href="#" class="hover:underline">{{ __('Dukungan Disabilitas') }}</a></li>
                    <li><a href="#" class="hover:underline">{{ __('Opsi Pembatalan') }}</a></li>
                </ul>
            </div>

            {{-- Column 2 --}}
            <div class="flex flex-col gap-4">
                <h4 class="text-sm font-bold text-neutral-900">{{ __('Hosting') }}</h4>
                <ul class="flex flex-col gap-3 text-sm text-neutral-600 font-light">
                    <li><a href="{{ route('vendor.dashboard') }}" class="hover:underline">{{ __('Gofishi-kan perahu Anda') }}</a></li>
                    <li><a href="#" class="hover:underline">{{ __('AirCover untuk Host') }}</a></li>
                    <li><a href="#" class="hover:underline">{{ __('Sumber Daya Hosting') }}</a></li>
                    <li><a href="#" class="hover:underline">{{ __('Forum Komunitas') }}</a></li>
                    <li><a href="#" class="hover:underline">{{ __('Hosting Bertanggung Jawab') }}</a></li>
                </ul>
            </div>

            {{-- Column 3 --}}
            <div class="flex flex-col gap-4">
                <h4 class="text-sm font-bold text-neutral-900">{{ __('Gofishi') }}</h4>
                <ul class="flex flex-col gap-3 text-sm text-neutral-600 font-light">
                    <li><a href="#" class="hover:underline">{{ __('Ruang Berita') }}</a></li>
                    <li><a href="#" class="hover:underline">{{ __('Fitur Baru') }}</a></li>
                    <li><a href="#" class="hover:underline">{{ __('Karier') }}</a></li>
                    <li><a href="#" class="hover:underline">{{ __('Investor') }}</a></li>
                    <li><a href="#" class="hover:underline">{{ __('Gift Cards') }}</a></li>
                </ul>
            </div>

            {{-- Column 4 - Newsletter --}}
            <div class="flex flex-col gap-4">
                <h4 class="text-sm font-bold text-neutral-900">{{ __('Langganan') }}</h4>
                <p class="text-sm text-neutral-600 font-light leading-relaxed">
                    {{ __('Dapatkan info terbaru tentang spot memancing terbaik.') }}
                </p>
                <form action="{{ route('store_subscriber') }}" method="POST" class="mt-2 relative">
                    @csrf
                    <input type="email" name="email_id" placeholder="{{ __('Alamat email') }}" 
                           class="w-full py-3 px-4 rounded-xl border border-neutral-300 focus:border-black focus:ring-0 text-sm font-light outline-none transition">
                    <button type="submit" class="absolute right-2 top-2 bottom-2 bg-neutral-900 text-white px-4 rounded-lg text-xs font-bold hover:bg-black transition">
                        {{ __('Ikut') }}
                    </button>
                </form>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 text-sm text-neutral-600 font-light">
                <span>© {{ date('Y') }} Gofishi, Inc.</span>
                <span class="hidden md:inline">·</span>
                <a href="#" class="hover:underline">{{ __('Privasi') }}</a>
                <span class="hidden md:inline">·</span>
                <a href="#" class="hover:underline">{{ __('Ketentuan') }}</a>
                <span class="hidden md:inline">·</span>
                <a href="#" class="hover:underline">{{ __('Peta Situs') }}</a>
            </div>

            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2 text-sm font-semibold text-neutral-900">
                    <i class="fas fa-globe"></i>
                    <span class="hover:underline cursor-pointer">Bahasa Indonesia (ID)</span>
                </div>
                <div class="flex items-center gap-2 text-sm font-semibold text-neutral-900">
                    <span class="hover:underline cursor-pointer">IDR</span>
                </div>
                <div class="flex items-center gap-4 text-neutral-900">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>
</footer>
