@extends('frontend.layout-airbnb')

@section('pageHeading')
  {{ __('Host GoFishi Elit - Program Super Host & Standar Kualitas Pelayanan') }}
@endsection

@section('content')
<div class="bg-white">
    
    {{-- Hero Section (Elite Vibe) --}}
    <section class="relative h-[65vh] flex items-center overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 w-full z-10">
            <div class="max-w-2xl bg-white/10 backdrop-blur-xl p-12 rounded-[40px] border border-white/20 shadow-2xl">
                <div class="flex items-center gap-3 text-white mb-6">
                    <div class="w-10 h-10 bg-airbnb-red rounded-full flex items-center justify-center">
                        <i data-lucide="award" class="w-6 h-6 text-white"></i>
                    </div>
                    <span class="font-bold tracking-widest uppercase text-sm">GoFishi Elite Program</span>
                </div>
                <h1 class="text-5xl md:text-7xl font-black text-white leading-tight mb-8">
                    Menjadi Yang<br><span class="text-airbnb-red">Terbaik.</span>
                </h1>
                <p class="text-xl text-white/90 font-light leading-relaxed">
                    Program Super Host GoFishi merayakan dan menghargai para pemilik perahu yang memberikan pengalaman luar biasa bagi setiap Angler.
                </p>
            </div>
        </div>
        {{-- Background --}}
        <img src="{{ asset('assets/img/gofishi_super_host_hero.png') }}" class="absolute inset-0 w-full h-full object-cover -z-10 scale-105">
        <div class="absolute inset-0 bg-gradient-to-r from-black/60 to-transparent -z-10"></div>
    </section>

    {{-- Program Benefits --}}
    <section class="max-w-7xl mx-auto px-6 py-24">
        <div class="text-center max-w-3xl mx-auto mb-20 space-y-4">
            <h2 class="text-4xl font-bold">Keuntungan super bagi Super Host.</h2>
            <p class="text-xl text-airbnb-gray font-light">Lebih dari sekadar lencana, ini adalah tentang pertumbuhan bisnis Anda.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-16">
            <div class="text-center space-y-4">
                <div class="w-20 h-20 bg-neutral-50 rounded-full flex items-center justify-center mx-auto mb-6 group hover:bg-airbnb-red transition duration-500">
                    <i data-lucide="trending-up" class="w-10 h-10 text-airbnb-red group-hover:text-white transition"></i>
                </div>
                <h4 class="text-xl font-bold">Prioritas Pencarian</h4>
                <p class="text-airbnb-gray font-light leading-relaxed">Perahu Anda akan muncul di baris teratas hasil pencarian secara otomatis tanpa biaya tambahan.</p>
            </div>
            <div class="text-center space-y-4">
                <div class="w-20 h-20 bg-neutral-50 rounded-full flex items-center justify-center mx-auto mb-6 group hover:bg-airbnb-red transition duration-500">
                    <i data-lucide="badge-check" class="w-10 h-10 text-airbnb-red group-hover:text-white transition"></i>
                </div>
                <h4 class="text-xl font-bold">Lencana Eksklusif</h4>
                <p class="text-airbnb-gray font-light leading-relaxed">Tampilkan kredibilitas Anda dengan badge Super Host yang terlihat oleh ribuan pemancing.</p>
            </div>
            <div class="text-center space-y-4">
                <div class="w-20 h-20 bg-neutral-50 rounded-full flex items-center justify-center mx-auto mb-6 group hover:bg-airbnb-red transition duration-500">
                    <i data-lucide="gift" class="w-10 h-10 text-airbnb-red group-hover:text-white transition"></i>
                </div>
                <h4 class="text-xl font-bold">Bonus & Reward</h4>
                <p class="text-airbnb-gray font-light leading-relaxed">Dapatkan voucher peralatan mancing dan akses gratis ke pameran maritim tahunan GoFishi.</p>
            </div>
        </div>
    </section>

    {{-- Quality Standards (Airbnb Table Style) --}}
    <section class="bg-neutral-50 py-24">
        <div class="max-w-4xl mx-auto px-6">
            <h2 class="text-4xl font-bold mb-12 text-center">Standar Kualitas Pelayanan</h2>
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                <div class="p-8 border-b border-neutral-100 flex justify-between items-center bg-neutral-900 text-white">
                    <span class="font-bold uppercase tracking-widest text-sm">Kriteria Penilaian</span>
                    <span class="font-bold uppercase tracking-widest text-sm">Target Super Host</span>
                </div>
                {{-- Row 1 --}}
                <div class="p-8 border-b border-neutral-50 flex justify-between items-center group hover:bg-neutral-50 transition">
                    <div class="space-y-1">
                        <h5 class="font-bold text-lg">Rating Keseluruhan</h5>
                        <p class="text-airbnb-gray text-sm">Rata-rata penilaian dari tamu pancing.</p>
                    </div>
                    <div class="text-2xl font-black text-airbnb-red">4.8+</div>
                </div>
                {{-- Row 2 --}}
                <div class="p-8 border-b border-neutral-50 flex justify-between items-center group hover:bg-neutral-50 transition">
                    <div class="space-y-1">
                        <h5 class="font-bold text-lg">Tingkat Respons</h5>
                        <p class="text-airbnb-gray text-sm">Kecepatan membalas pesan dalam 24 jam.</p>
                    </div>
                    <div class="text-2xl font-black text-airbnb-red">90%</div>
                </div>
                {{-- Row 3 --}}
                <div class="p-8 border-b border-neutral-50 flex justify-between items-center group hover:bg-neutral-50 transition">
                    <div class="space-y-1">
                        <h5 class="font-bold text-lg">Tingkat Komitmen</h5>
                        <p class="text-airbnb-gray text-sm">Jumlah pembatalan yang dilakukan Host.</p>
                    </div>
                    <div class="text-2xl font-black text-airbnb-red">&lt; 1%</div>
                </div>
                {{-- Row 4 --}}
                <div class="p-8 flex justify-between items-center group hover:bg-neutral-50 transition">
                    <div class="space-y-1">
                        <h5 class="font-bold text-lg">Jumlah Perjalanan</h5>
                        <p class="text-airbnb-gray text-sm">Minimal trip yang diselesaikan per tahun.</p>
                    </div>
                    <div class="text-2xl font-black text-airbnb-red">15+</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Final Call to Action --}}
    <section class="max-w-7xl mx-auto px-6 py-32 text-center bg-white">
        <div class="bg-airbnb-dark text-white rounded-[60px] p-20 relative overflow-hidden">
            <div class="relative z-10">
                <h2 class="text-5xl font-black mb-8 leading-tight">Mulai Perjalanan Anda Menuju<br>Super Host Hari Ini.</h2>
                <a href="{{ route('vendor.login') }}" class="inline-block bg-airbnb-red hover:bg-[#D70466] text-white text-xl font-bold py-5 px-12 rounded-2xl transition shadow-xl hover:scale-110 transform">
                    Ayo Mulai!
                </a>
            </div>
            {{-- Decorative circles --}}
            <div class="absolute -top-20 -left-20 w-80 h-80 bg-white/5 rounded-full"></div>
            <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-airbnb-red/10 rounded-full"></div>
        </div>
    </section>

</div>
@endsection
