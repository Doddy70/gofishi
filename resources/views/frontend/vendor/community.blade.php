@extends('frontend.layout-airbnb')

@section('pageHeading')
  {{ __('Forum Komunitas Host GoFishi - Wadah Berbagi & Tumbuh Bersama') }}
@endsection

@section('content')
<div class="bg-white">
    
    {{-- Hero Section (Community Vibe) --}}
    <section class="relative h-[70vh] flex items-center justify-center text-center overflow-hidden">
        <div class="relative z-10 max-w-4xl mx-auto px-6">
            <h1 class="text-5xl md:text-7xl font-black text-white leading-tight drop-shadow-2xl">
                Bersama Kita<br>Melempar Kail.
            </h1>
            <p class="text-xl md:text-2xl text-white font-light mt-6 drop-shadow-lg">
                Temukan tip, inspirasi, dan dukungan dari ribuan Host berpengalaman di seluruh Indonesia.
            </p>
            <div class="mt-10">
                <a href="#join" class="bg-white text-airbnb-dark font-bold py-5 px-12 rounded-2xl hover:bg-neutral-100 transition shadow-2xl hover:scale-110 transform inline-block">
                    Gabung Komunitas
                </a>
            </div>
        </div>
        {{-- Background with Gradient Overlay --}}
        <img src="{{ asset('assets/img/gofishi_community_hero.png') }}" class="absolute inset-0 w-full h-full object-cover -z-10 brightness-75">
        <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-transparent to-white -z-10"></div>
    </section>

    {{-- Stats Section --}}
    <section class="py-16 bg-neutral-50 border-b border-neutral-200">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <p class="text-4xl font-black text-airbnb-red">2,500+</p>
                <p class="text-airbnb-gray uppercase text-xs font-bold mt-2">Host Aktif</p>
            </div>
            <div>
                <p class="text-4xl font-black text-airbnb-red">45,000+</p>
                <p class="text-airbnb-gray uppercase text-xs font-bold mt-2">Diskusi Terjawab</p>
            </div>
            <div>
                <p class="text-4xl font-black text-airbnb-red">120+</p>
                <p class="text-airbnb-gray uppercase text-xs font-bold mt-2">Event Lokal</p>
            </div>
            <div>
                <p class="text-4xl font-black text-airbnb-red">15,000+</p>
                <p class="text-airbnb-gray uppercase text-xs font-bold mt-2">Cuan Terbagikan</p>
            </div>
        </div>
    </section>

    {{-- Meet the Super Hosts --}}
    <section class="max-w-7xl mx-auto px-6 py-24">
        <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
            <div class="max-w-xl">
                <h2 class="text-4xl font-bold mb-4">Belajar dari yang terbaik.</h2>
                <p class="text-lg text-airbnb-gray font-light leading-relaxed">Dengarkan cerita sukses dari para Super Host kami yang telah berhasil membangun armada impian mereka lewat GoFishi.</p>
            </div>
            <a href="#" class="text-airbnb-dark font-bold underline underline-offset-8 decoration-airbnb-red hover:text-airbnb-red transition">Lihat Semua Cerita</a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            {{-- Super Host 1 --}}
            <div class="bg-white rounded-3xl overflow-hidden shadow-xl border border-neutral-100 group cursor-pointer hover:-translate-y-2 transition duration-500">
                <div class="relative h-64">
                    <img src="{{ asset('assets/img/fishing_spot.png') }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition duration-500">
                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur rounded-full px-4 py-1 flex items-center gap-2 shadow-sm">
                        <i data-lucide="star" class="w-4 h-4 text-airbnb-red fill-current"></i>
                        <span class="text-xs font-bold">SUPER HOST</span>
                    </div>
                </div>
                <div class="p-8">
                    <h4 class="text-xl font-bold mb-2">Capt. Bambang, Bali</h4>
                    <p class="text-airbnb-gray font-light">"Kunci sukses saya adalah merespons pesan kurang dari 5 menit dan selalu menyediakan live bait yang segar."</p>
                </div>
            </div>
            {{-- Super Host 2 --}}
            <div class="bg-white rounded-3xl overflow-hidden shadow-xl border border-neutral-100 group cursor-pointer hover:-translate-y-2 transition duration-500">
                <div class="relative h-64">
                    <img src="{{ asset('assets/img/luxury_yacht.png') }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition duration-500">
                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur rounded-full px-4 py-1 flex items-center gap-2 shadow-sm">
                        <i data-lucide="star" class="w-4 h-4 text-airbnb-red fill-current"></i>
                        <span class="text-xs font-bold">SUPER HOST</span>
                    </div>
                </div>
                <div class="p-8">
                    <h4 class="text-xl font-bold mb-2">Ibu Sarah, Jakarta</h4>
                    <p class="text-airbnb-gray font-light">"Sewa yacht mewah meningkat tajam setelah saya mengikuti tip fotografi dari tim GoFishi Resources."</p>
                </div>
            </div>
            {{-- Super Host 3 --}}
            <div class="bg-white rounded-3xl overflow-hidden shadow-xl border border-neutral-100 group cursor-pointer hover:-translate-y-2 transition duration-500">
                <div class="relative h-64">
                    <img src="{{ asset('assets/img/speedboat.png') }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition duration-500">
                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur rounded-full px-4 py-1 flex items-center gap-2 shadow-sm">
                        <i data-lucide="star" class="w-4 h-4 text-airbnb-red fill-current"></i>
                        <span class="text-xs font-bold">SUPER HOST</span>
                    </div>
                </div>
                <div class="p-8">
                    <h4 class="text-xl font-bold mb-2">Mas Jawir, Karimun Jawa</h4>
                    <p class="text-airbnb-gray font-light">"Komunitas GoFishi membantu saya mendapatkan kapten pengganti di saat darurat. Luar biasa solid!"</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Discussion Topics --}}
    <section class="bg-neutral-900 text-white py-24 overflow-hidden relative">
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <h2 class="text-4xl font-bold mb-16">Topik Diskusi Terhangat.</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-8 bg-white/5 border border-white/10 rounded-3xl hover:bg-white/10 transition leading-relaxed">
                    <div class="text-airbnb-red font-bold text-sm mb-2">#TIPS-MANCING</div>
                    <h4 class="text-2xl font-bold mb-4">Optimalisasi Umpan Hidup untuk Giant Trevally di Laut Selatan</h4>
                    <p class="text-neutral-400">142 Komentar • 2 jam yang lalu</p>
                </div>
                <div class="p-8 bg-white/5 border border-white/10 rounded-3xl hover:bg-white/10 transition leading-relaxed">
                    <div class="text-airbnb-red font-bold text-sm mb-2">#PERAWATAN-KAPAL</div>
                    <h4 class="text-2xl font-bold mb-4">Rekomendasi Merk Oli Mesin Ganda yang Tahan Korosi Air Laut</h4>
                    <p class="text-neutral-400">89 Komentar • 5 jam yang lalu</p>
                </div>
                <div class="p-8 bg-white/5 border border-white/10 rounded-3xl hover:bg-white/10 transition leading-relaxed">
                    <div class="text-airbnb-red font-bold text-sm mb-2">#HOSPITALITY</div>
                    <h4 class="text-2xl font-bold mb-4">Cara Menangani Tamu yang Mabuk Laut dengan Sopan & Profesional</h4>
                    <p class="text-neutral-400">234 Komentar • Kemarin</p>
                </div>
                <div class="p-8 bg-white/5 border border-white/10 rounded-3xl hover:bg-white/10 transition leading-relaxed">
                    <div class="text-airbnb-red font-bold text-sm mb-2">#REGULASI</div>
                    <h4 class="text-2xl font-bold mb-4">Update Perizinan Melaut Terbaru untuk Pemilik Perahu Wisata</h4>
                    <p class="text-neutral-400">112 Komentar • Kemarin</p>
                </div>
            </div>
        </div>
        {{-- Decorative Icon Background --}}
        <i data-lucide="users" class="absolute -right-20 -bottom-20 w-[500px] h-[500px] text-white/5 rotate-12 -z-0"></i>
    </section>

    {{-- Final CTA Area --}}
    <section id="join" class="max-w-5xl mx-auto px-6 py-32 text-center space-y-12">
        <h2 class="text-5xl font-black">Siap terhubung dengan ribuan nahkoda lainnya?</h2>
        <p class="text-2xl text-airbnb-gray font-light">Komunitas GoFishi selalu terbuka untuk Host baru yang ingin belajar dan berbagi.</p>
        <div class="flex flex-col md:flex-row gap-6 justify-center">
            <a href="https://wa.me/gofishisupport" target="_blank" class="bg-[#25D366] text-white font-bold py-5 px-10 rounded-2xl flex items-center justify-center gap-3 hover:scale-105 transition shadow-lg">
                <i class="fab fa-whatsapp text-2xl"></i>
                Gabung Grup WhatsApp
            </a>
            <a href="https://t.me/gofishicommunity" target="_blank" class="bg-[#24A1DE] text-white font-bold py-5 px-10 rounded-2xl flex items-center justify-center gap-3 hover:scale-105 transition shadow-lg">
                <i class="fab fa-telegram-plane text-2xl"></i>
                Gabung Channel Telegram
            </a>
        </div>
    </section>

</div>
@endsection
