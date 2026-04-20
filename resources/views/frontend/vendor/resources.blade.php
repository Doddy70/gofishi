@extends('frontend.layout-airbnb')

@section('pageHeading')
  {{ __('Host Resources - Pusat Alat Bantu & Panduan Host GoFishi') }}
@endsection

@section('content')
<div class="bg-white">
    
    {{-- Hero Section (Business Vibe) --}}
    <section class="relative h-[60vh] flex items-center overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 w-full grid grid-cols-1 md:grid-cols-2 gap-12 z-10">
            <div class="bg-white/90 backdrop-blur-md p-10 rounded-3xl shadow-2xl border border-white/20 max-w-xl">
                <span class="text-airbnb-red font-bold tracking-widest uppercase text-sm">Professional Hosting</span>
                <h1 class="text-4xl md:text-6xl font-black mt-4 mb-6 leading-tight">Kelola dan Kembangkan Bisnis Anda.</h1>
                <p class="text-xl text-airbnb-gray font-light">
                    Kuasai setiap alat bantu yang GoFishi berikan untuk memaksimalkan potensi armada Anda di atas air.
                </p>
                <div class="pt-8">
                    <a href="{{ route('vendor.login') }}" class="bg-airbnb-dark text-white font-bold py-4 px-8 rounded-xl hover:bg-black transition">
                        Buka Dashboard Host
                    </a>
                </div>
            </div>
        </div>
        {{-- Background --}}
        <img src="{{ asset('assets/img/gofishi_host_resources_hero.png') }}" class="absolute inset-0 w-full h-full object-cover -z-10 brightness-90">
    </section>

    {{-- Dashboard Tools (The Grid) --}}
    <section class="max-w-7xl mx-auto px-6 py-24">
        <h2 class="text-4xl font-bold mb-16">Alat bantu untuk sukses.</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            {{-- Tool 1 --}}
            <div class="group cursor-pointer">
                <div class="aspect-video mb-6 overflow-hidden rounded-2xl bg-neutral-100">
                    <img src="{{ asset('assets/img/fishing_spot.png') }}" class="w-full h-full object-cover hover:scale-110 transition duration-500">
                </div>
                <h4 class="text-xl font-bold mb-2 group-hover:text-airbnb-red transition">Kalender Pintar</h4>
                <p class="text-airbnb-gray leading-relaxed">Atur jadwal keberangkatan, blokir tanggal sibuk, dan kelola ketersediaan armada dalam hitungan detik.</p>
            </div>
            {{-- Tool 2 --}}
            <div class="group cursor-pointer">
                <div class="aspect-video mb-6 overflow-hidden rounded-2xl bg-neutral-100">
                    <img src="{{ asset('assets/img/luxury_yacht.png') }}" class="w-full h-full object-cover hover:scale-110 transition duration-500">
                </div>
                <h4 class="text-xl font-bold mb-2 group-hover:text-airbnb-red transition">Analitik Penghasilan</h4>
                <p class="text-airbnb-gray leading-relaxed">Lihat laporan pendapatan bulanan, harga rata-rata pasar, dan tren memancing di area dermaga Anda.</p>
            </div>
            {{-- Tool 3 --}}
            <div class="group cursor-pointer">
                <div class="aspect-video mb-6 overflow-hidden rounded-2xl bg-neutral-100">
                    <img src="{{ asset('assets/img/speedboat.png') }}" class="w-full h-full object-cover hover:scale-110 transition duration-500">
                </div>
                <h4 class="text-xl font-bold mb-2 group-hover:text-airbnb-red transition">Pesan Langsung (Inbox)</h4>
                <p class="text-airbnb-gray leading-relaxed">Balas pesan dan tanya jawab dengan calon pemancing secara langsung lewat dashboard terpusat kami.</p>
            </div>
        </div>
    </section>

    {{-- Marketing Support --}}
    <section class="bg-neutral-900 text-white py-24">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-20 items-center">
            <div class="space-y-8">
                <h2 class="text-4xl font-bold leading-tight">Pemasaran & Fotografi Kelas Atas.</h2>
                <p class="text-lg text-neutral-400 font-light leading-relaxed">Kami tidak hanya memberikan website, kami memberikan platform pemasaran. Setiap perahu populer di GoFishi mendapatkan bantuan optimasi pencarian (SEO) agar selalu muncul di halaman pertama mesin pencari.</p>
                <div class="space-y-4">
                    <div class="flex gap-4 items-start">
                        <i data-lucide="camera" class="w-6 h-6 text-airbnb-red shrink-0"></i>
                        <div>
                            <h5 class="font-bold">Fotografi Profesional</h5>
                            <p class="text-neutral-500">Kami bisa mencocokkan Anda dengan fotografer khusus perahu untuk mendongkrak pesanan hingga 40%.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 items-start">
                        <i data-lucide="trending-up" class="w-6 h-6 text-airbnb-red shrink-0"></i>
                        <div>
                            <h5 class="font-bold">Iklan Berbayar</h5>
                            <p class="text-neutral-500">Host terbaik mendapatkan akses fitur "Boost" untuk muncul di baris teratas pencarian pemancing.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="rounded-3xl overflow-hidden shadow-2xl skew-y-2 hover:skew-y-0 transition duration-700">
                <img src="{{ asset('assets/img/gofishi_host_hero.png') }}" class="w-full h-full object-cover">
            </div>
        </div>
    </section>

    {{-- Learning Hub Call to Action --}}
    <section class="max-w-7xl mx-auto px-6 py-32 text-center">
        <h2 class="text-4xl font-bold mb-6">Pusat Pembelajaran Host</h2>
        <p class="text-xl text-airbnb-gray mb-12 max-w-2xl mx-auto">Ingin jadi Host bintang 5? Baca panduan lengkap kami tentang cara melayani tamu pemancing dengan sempurna.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
            <div class="p-10 border border-neutral-200 rounded-3xl hover:border-airbnb-red transition group">
                <h4 class="text-2xl font-bold mb-4">Cara Memaksimalkan Deskripsi Perahu</h4>
                <p class="text-airbnb-gray mb-6">Gunakan kata-kata yang memikat dan tonjolkan fasilitas unik perahu Anda.</p>
                <a href="#" class="text-airbnb-red font-bold flex items-center gap-2 group-hover:translate-x-2 transition">
                    Baca Selengkapnya
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
            <div class="p-10 border border-neutral-200 rounded-3xl hover:border-airbnb-red transition group">
                <h4 class="text-2xl font-bold mb-4">Menjaga Kebersihan & Kesiapan Perahu</h4>
                <p class="text-airbnb-gray mb-6">Daftar periksa (checklist) sebelum keberangkatan untuk kepuasan pemancing.</p>
                <a href="#" class="text-airbnb-red font-bold flex items-center gap-2 group-hover:translate-x-2 transition">
                    Baca Selengkapnya
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- Footer CTA --}}
    <footer class="bg-neutral-50 py-24 text-center border-t border-neutral-200">
        <h3 class="text-3xl font-bold mb-8 italic text-airbnb-red underline decoration-neutral-900 underline-offset-8">GoFishi Professional Dashboard</h3>
        <p class="text-lg text-airbnb-gray mb-10">Siap mengekspansi armada Anda hari ini?</p>
        <a href="{{ route('vendor.login') }}" class="inline-block bg-airbnb-red hover:bg-[#D70466] text-white text-lg font-bold py-4 px-10 rounded-xl transition">
            Buka Dashboard Host
        </a>
    </footer>

</div>
@endsection
