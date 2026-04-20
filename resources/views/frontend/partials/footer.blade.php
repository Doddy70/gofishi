<!-- Simple & Clean Airbnb Style Footer -->
<footer class="bg-neutral-50 border-t border-neutral-200 pt-12 pb-8 mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 pb-12 border-b border-neutral-200">
            
            {{-- Column 1 - Support --}}
            <div class="flex flex-col gap-4">
                <h4 class="text-sm font-bold text-neutral-900">{{ __('Dukungan') }}</h4>
                <ul class="flex flex-col gap-3 text-sm text-neutral-600 font-light">
                    @if(count($supportPages) > 0)
                        @foreach($supportPages->whereIn('title', ['Pusat Bantuan', 'Kebijakan Privasi', 'Dukungan & Bantuan', 'Kebijakan Pembatalan']) as $page)
                            <li><a href="{{ route('frontend.custom_page', $page->slug) }}" class="hover:underline">{{ __($page->title) }}</a></li>
                        @endforeach
                    @else
                        <li><a href="#" class="hover:underline">{{ __('Pusat Bantuan') }}</a></li>
                        <li><a href="#" class="hover:underline">{{ __('Kebijakan Privasi') }}</a></li>
                        <li><a href="#" class="hover:underline">{{ __('Dukungan & Bantuan') }}</a></li>
                    @endif
                </ul>
            </div>

            {{-- Column 2 - Hosting --}}
            <div class="flex flex-col gap-4">
                <h4 class="text-sm font-bold text-neutral-900">{{ __('Hosting') }}</h4>
                <ul class="flex flex-col gap-3 text-sm text-neutral-600 font-light">
                    <li><a href="{{ route('frontend.host.landing') }}" class="hover:underline">{{ __('Menjadi Host GoFishi') }}</a></li>
                    <li><a href="{{ route('frontend.host.resources') }}" class="hover:underline">{{ __('Host Resources') }}</a></li>
                    <li><a href="{{ route('frontend.host.community') }}" class="hover:underline">{{ __('Forum Komunitas') }}</a></li>
                    <li><a href="{{ route('frontend.host.featured') }}" class="hover:underline">{{ __('Host GoFishi') }}</a></li>
                </ul>
            </div>

            {{-- Column 3 - Company --}}
            <div class="flex flex-col gap-4">
                <h4 class="text-sm font-bold text-neutral-900">{{ __('GoFishi') }}</h4>
                <ul class="flex flex-col gap-3 text-sm text-neutral-600 font-light">
                    @if(count($companyPages) > 0)
                        @foreach($companyPages->whereNotIn('title', ['Ketentuan', 'Peta Situs']) as $page)
                            <li><a href="{{ route('frontend.custom_page', $page->slug) }}" class="hover:underline">{{ __($page->title) }}</a></li>
                        @endforeach
                    @else
                        <li><a href="#" class="hover:underline">{{ __('Tips Wisata Gofishi') }}</a></li>
                        <li><a href="#" class="hover:underline">{{ __('Fitur Baru Gofishi') }}</a></li>
                        <li><a href="#" class="hover:underline">{{ __('Karier di Gofishi') }}</a></li>
                    @endif
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
                <span>© {{ date('Y') }} GoFishi, Inc.</span>
                <span class="hidden md:inline">·</span>
                <a href="{{ route('frontend.custom_page', 'kebijakan-privasi') }}" class="hover:underline">{{ __('Privasi') }}</a>
                <span class="hidden md:inline">·</span>
                <a href="{{ route('frontend.custom_page', 'ketentuan') }}" class="hover:underline">{{ __('Ketentuan') }}</a>
                <span class="hidden md:inline">·</span>
                <a href="{{ route('frontend.custom_page', 'peta-situs') }}" class="hover:underline">{{ __('Peta Situs') }}</a>
            </div>

            <div class="flex items-center gap-6">
                <div class="flex items-center gap-4 text-neutral-900 border-r border-neutral-200 pr-6 mr-1">
                    <a href="https://www.facebook.com/gofishi/" target="_blank" class="hover:scale-110 transition-transform"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.youtube.com/" target="_blank" class="hover:scale-110 transition-transform"><i class="fab fa-youtube"></i></a>
                    <a href="https://www.instagram.com/gofishi/" target="_blank" class="hover:scale-110 transition-transform"><i class="fab fa-instagram"></i></a>
                </div>
                <div class="flex items-center gap-2 text-sm font-semibold text-neutral-900 pr-1">
                    <i class="fas fa-globe text-xs"></i>
                    <span class="hover:underline cursor-pointer">Bahasa Indonesia (ID)</span>
                </div>
                <div class="flex items-center gap-2 text-sm font-semibold text-neutral-900">
                    <span class="hover:underline cursor-pointer">IDR</span>
                </div>
            </div>
        </div>
    </div>
</footer>
