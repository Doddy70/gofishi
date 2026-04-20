@extends('frontend.layout-airbnb')

@section('pageHeading')
    {{ __('Pusat Bantuan') }}
@endsection

@section('content')
<div class="bg-white" x-data="{ activeTab: 'tamu' }">
    
    {{-- Hero Section --}}
    <div class="pt-24 pb-12 px-4 text-center max-w-4xl mx-auto">
        <h1 class="text-[32px] md:text-[44px] font-bold text-[#222222] mb-8 tracking-tight leading-tight">
            Halo, bagaimana kami bisa membantu?
        </h1>
        
        <div class="relative max-w-2xl mx-auto group">
            <div class="absolute left-6 top-1/2 -translate-y-1/2 text-neutral-400">
                <i data-lucide="search" class="w-5 h-5"></i>
            </div>
            <input type="text" 
                   placeholder="Cari petunjuk cara dan lainnya" 
                   class="w-full pl-14 pr-16 py-4 rounded-full border border-neutral-300 shadow-sm focus:shadow-md focus:border-neutral-900 focus:outline-none text-[16px] font-light placeholder:text-neutral-500 transition-all">
            <button class="absolute right-2 top-1/2 -translate-y-1/2 bg-[#FF385C] hover:bg-[#E31C5F] text-white p-2.5 rounded-full transition-colors">
                <i data-lucide="search" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    {{-- Tabs Section --}}
    <div class="border-b border-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex overflow-x-auto no-scrollbar gap-8 items-end justify-start md:justify-center -mb-[1px]">
                <button @click="activeTab = 'tamu'" 
                        :class="activeTab === 'tamu' ? 'border-neutral-900 text-neutral-900' : 'border-transparent text-neutral-500 hover:text-neutral-700'"
                        class="pb-4 border-b-2 font-semibold text-[14px] whitespace-nowrap transition-all">
                    Tamu
                </button>
                <button @click="activeTab = 'host'" 
                        :class="activeTab === 'host' ? 'border-neutral-900 text-neutral-900' : 'border-transparent text-neutral-500 hover:text-neutral-700'"
                        class="pb-4 border-b-2 font-semibold text-[14px] whitespace-nowrap transition-all">
                    Host Perahu
                </button>
                <button @click="activeTab = 'pengalaman'" 
                        :class="activeTab === 'pengalaman' ? 'border-neutral-900 text-neutral-900' : 'border-transparent text-neutral-500 hover:text-neutral-700'"
                        class="pb-4 border-b-2 font-semibold text-[14px] whitespace-nowrap transition-all">
                    Host Pengalaman
                </button>
                <button @click="activeTab = 'layanan'" 
                        :class="activeTab === 'layanan' ? 'border-neutral-900 text-neutral-900' : 'border-transparent text-neutral-500 hover:text-neutral-700'"
                        class="pb-4 border-b-2 font-semibold text-[14px] whitespace-nowrap transition-all">
                    Host Layanan
                </button>
                <button @click="activeTab = 'admin'" 
                        :class="activeTab === 'admin' ? 'border-neutral-900 text-neutral-900' : 'border-transparent text-neutral-500 hover:text-neutral-700'"
                        class="pb-4 border-b-2 font-semibold text-[14px] whitespace-nowrap transition-all">
                    Admin Perjalanan
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        {{-- Login Card --}}
        <div class="mb-16 bg-white border border-neutral-200 rounded-[12px] p-8 flex flex-col md:flex-row justify-between items-center gap-6 shadow-sm">
            <div class="max-w-2xl text-center md:text-left">
                <h2 class="text-[22px] font-semibold text-[#222222] mb-1 leading-tight tracking-tight">Kami siap membantu Anda</h2>
                <p class="text-[16px] text-neutral-500 font-light">Masuk untuk mendapatkan bantuan terkait reservasi, akun Anda, dan banyak lagi.</p>
            </div>
            <a href="{{ route('user.login') }}" class="w-full md:w-auto px-8 py-3 bg-[#FF385C] hover:bg-[#E31C5F] text-white font-semibold rounded-[8px] transition-all text-center">
                Masuk atau mendaftar
            </a>
        </div>

        {{-- Guest Content --}}
        <template x-if="activeTab === 'tamu'">
            <div class="animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="flex justify-between items-end mb-8">
                    <h2 class="text-[26px] font-bold text-[#222222] tracking-tight">Panduan untuk memulai</h2>
                    <a href="#" class="text-neutral-900 font-semibold text-sm underline flex items-center gap-2">
                        Telusuri semua topik <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
                    <div class="group cursor-pointer">
                        <div class="aspect-[4/3] rounded-2xl overflow-hidden bg-neutral-900 mb-4 relative flex items-center justify-center p-8">
                            <div class="text-center">
                                <h3 class="text-[60px] font-bold text-[#FF385C] mb-1 tracking-tighter leading-none">aircover</h3>
                                <p class="text-white text-[18px] font-semibold">untuk tamu</p>
                            </div>
                        </div>
                        <h4 class="text-[16px] font-semibold text-neutral-900 underline group-hover:text-black transition-colors">AirCover untuk tamu</h4>
                    </div>
                    <div class="group cursor-pointer">
                        <div class="aspect-[4/3] rounded-2xl overflow-hidden bg-neutral-100 mb-4">
                            <img src="https://images.unsplash.com/photo-1540331547168-8b63109225b7?auto=format&fit=crop&q=80&w=800" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                 alt="Getting started">
                        </div>
                        <h4 class="text-[16px] font-semibold text-neutral-900 underline group-hover:text-black transition-colors">Sumber informasi penting untuk Host baru</h4>
                    </div>
                </div>

                <h2 class="text-[26px] font-bold text-[#222222] mb-8 tracking-tight">Artikel terpopuler</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-12 gap-y-10">
                    <div>
                        <h5 class="text-[16px] font-semibold text-neutral-900 mb-2 underline cursor-pointer hover:text-neutral-600">Membatalkan reservasi penginapan</h5>
                        <p class="text-[14px] text-neutral-500 font-light leading-relaxed">Ketentuan pembatalan Anda selalu ditetapkan oleh Host Anda...</p>
                    </div>
                    <div>
                        <h5 class="text-[16px] font-semibold text-neutral-900 mb-2 underline cursor-pointer hover:text-neutral-600">Mengubah reservasi penginapan</h5>
                        <p class="text-[14px] text-neutral-500 font-light leading-relaxed">Anda dapat mengajukan permohonan untuk mengubah reservasi Anda...</p>
                    </div>
                    <div>
                        <h5 class="text-[16px] font-semibold text-neutral-900 mb-2 underline cursor-pointer hover:text-neutral-600">Jika terjadi masalah selama penginapan</h5>
                        <p class="text-[14px] text-neutral-500 font-light leading-relaxed">Host Anda adalah orang terbaik untuk membantu jika terjadi masalah...</p>
                    </div>
                    <div>
                        <h5 class="text-[16px] font-semibold text-neutral-900 mb-2 underline cursor-pointer hover:text-neutral-600">Menemukan tanda terima pajak Anda</h5>
                        <p class="text-[14px] text-neutral-500 font-light leading-relaxed">Anda dapat mengakses tanda terima melalui perincian reservasi Anda...</p>
                    </div>
                    <div>
                        <h5 class="text-[16px] font-semibold text-neutral-900 mb-2 underline cursor-pointer hover:text-neutral-600">Mendapatkan harga yang lebih rendah</h5>
                        <p class="text-[14px] text-neutral-500 font-light leading-relaxed">Pelajari cara mencari penawaran mingguan atau bulanan yang hemat...</p>
                    </div>
                </div>
            </div>
        </template>

        {{-- Host Content --}}
        <template x-if="activeTab === 'host'">
            <div class="animate-in fade-in slide-in-from-bottom-4 duration-500">
                <h2 class="text-[26px] font-bold text-[#222222] mb-8 tracking-tight">Topik Host Perahu</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-16">
                    <div class="p-6 border border-neutral-200 rounded-xl hover:shadow-md transition-shadow cursor-pointer flex flex-col gap-3">
                        <i data-lucide="plus-circle" class="w-8 h-8 text-neutral-900"></i>
                        <span class="font-semibold text-sm">Mulai menerima tamu</span>
                    </div>
                    <div class="p-6 border border-neutral-200 rounded-xl hover:shadow-md transition-shadow cursor-pointer flex flex-col gap-3">
                        <i data-lucide="shield-check" class="w-8 h-8 text-neutral-900"></i>
                        <span class="font-semibold text-sm">Keselamatan tamu</span>
                    </div>
                    <div class="p-6 border border-neutral-200 rounded-xl hover:shadow-md transition-shadow cursor-pointer flex flex-col gap-3">
                        <i data-lucide="calendar" class="w-8 h-8 text-neutral-900"></i>
                        <span class="font-semibold text-sm">Kalender & harga</span>
                    </div>
                    <div class="p-6 border border-neutral-200 rounded-xl hover:shadow-md transition-shadow cursor-pointer flex flex-col gap-3">
                        <i data-lucide="dollar-sign" class="w-8 h-8 text-neutral-900"></i>
                        <span class="font-semibold text-sm">Pembayaran & pajak</span>
                    </div>
                </div>
            </div>
        </template>

        {{-- Help Categories Section --}}
        <div class="mt-20 border-t border-neutral-200 pt-16">
            <h2 class="text-[26px] font-bold text-[#222222] mb-10 tracking-tight">Kategori bantuan lainnya</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-12 gap-y-12">
                <div class="flex gap-4">
                    <i data-lucide="credit-card" class="w-6 h-6 shrink-0 mt-1"></i>
                    <div>
                        <h4 class="text-[18px] font-semibold text-neutral-900 mb-2">Pemesanan & perjalanan</h4>
                        <p class="text-sm text-neutral-500 font-light">Mencari petunjuk cara memesan, membatalkan, dan lainnya.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <i data-lucide="user" class="w-6 h-6 shrink-0 mt-1"></i>
                    <div>
                        <h4 class="text-[18px] font-semibold text-neutral-900 mb-2">Mengelola akun Anda</h4>
                        <p class="text-sm text-neutral-500 font-light">Update profil, email, dan pengaturan privasi Anda.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <i data-lucide="shield" class="w-6 h-6 shrink-0 mt-1"></i>
                    <div>
                        <h4 class="text-[18px] font-semibold text-neutral-900 mb-2">Keselamatan & aksesibilitas</h4>
                        <p class="text-sm text-neutral-500 font-light">Informasi pelaporan masalah dan standar komunitas.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) {
            lucide.createIcons();
        }
    });
</script>
@endsection
