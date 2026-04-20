@extends('frontend.layout-airbnb')

@section('pageHeading')
    {{ __('Kebijakan Pembatalan') }}
@endsection

@section('content')
<div class="bg-white pt-24 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Breadcrumbs --}}
        <nav class="flex text-sm text-neutral-500 font-light mb-8 overflow-x-auto no-scrollbar whitespace-nowrap">
            <a href="{{ url('/pusat-bantuan') }}" class="hover:underline">Pusat Bantuan</a>
            <span class="mx-2 flex items-center"><i data-lucide="chevron-right" class="w-3 h-3"></i></span>
            <a href="#" class="hover:underline">Pemesanan & perjalanan</a>
            <span class="mx-2 flex items-center"><i data-lucide="chevron-right" class="w-3 h-3"></i></span>
            <span class="text-neutral-900 font-normal">Membatalkan reservasi</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
            
            {{-- Main Content --}}
            <div class="lg:col-span-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
                <header class="mb-12 border-b border-neutral-100 pb-12">
                    <h1 class="text-[32px] md:text-[48px] font-bold text-[#222222] mb-4 tracking-tighter leading-tight">
                        Kebijakan pembatalan untuk penginapan Anda
                    </h1>
                    <p class="text-neutral-500 font-light text-[15px]">Terakhir diperbarui: 4 April 2026</p>
                </header>

                <div class="space-y-12 text-[#222222]">
                    
                    {{-- Section 1 --}}
                    <section>
                        <h2 class="text-[24px] font-semibold mb-6 tracking-tight">Cara kerjanya</h2>
                        <div class="prose prose-neutral max-w-none font-light leading-relaxed text-[16px] space-y-4">
                            <p>Setiap tuan rumah di GoFishi memilih kebijakan pembatalan untuk tamu mereka—mulai dari fleksibel hingga ketat. Anda dapat menemukan perincian tentang pembatalan dari iklan tuan rumah dan selama proses pemesanan sebelum Anda membayar.</p>
                            <p>Jika Anda perlu membatalkan, pengembalian dana Anda didasarkan pada kebijakan pembatalan tuan rumah Anda dan seberapa jauh Anda membatalkannya sebelum check-in.</p>
                        </div>
                    </section>

                    {{-- Section 2 --}}
                    <section class="bg-neutral-50 rounded-2xl p-8 border border-neutral-100">
                        <h2 class="text-[24px] font-semibold mb-6 tracking-tight">Tingkat Pengembalian Dana</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="flex gap-4">
                                <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center shrink-0">
                                    <i data-lucide="clock" class="w-5 h-5 text-rose-500"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold mb-2">Fleksibel</h4>
                                    <p class="text-sm text-neutral-600 font-light">Bebas membatalkan hingga 24 jam sebelum check-in untuk mendapatkan pengembalian dana penuh.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="w-10 h-10 rounded-full bg-neutral-200 flex items-center justify-center shrink-0">
                                    <i data-lucide="alert-circle" class="w-5 h-5 text-neutral-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold mb-2">Moderat</h4>
                                    <p class="text-sm text-neutral-600 font-light">Bebas membatalkan hingga 5 hari sebelum check-in untuk mendapatkan pengembalian dana penuh.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- Section 3 --}}
                    <section>
                        <h2 class="text-[24px] font-semibold mb-6 tracking-tight">Apa yang terjadi jika tuan rumah membatalkan?</h2>
                        <div class="prose prose-neutral max-w-none font-light leading-relaxed text-[16px]">
                            <p>Jika tuan rumah membatalkan reservasi Anda sebelum check-in, Anda akan secara otomatis menerima pengembalian dana penuh. Jika pembatalan terjadi dalam kurun waktu 30 hari setelah check-in, kami juga akan membantu Anda memesan tempat penginapan baru yang sebanding atau lebih baik.</p>
                        </div>
                    </section>

                    {{-- Helpfulness Interaction --}}
                    <div class="mt-20 pt-12 border-t border-neutral-100">
                        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                            <span class="text-[18px] font-semibold">Apakah artikel ini membantu?</span>
                            <div class="flex gap-3">
                                <button class="px-6 py-2 rounded-full border border-neutral-300 hover:border-black transition-all font-semibold text-sm">Ya</button>
                                <button class="px-6 py-2 rounded-full border border-neutral-300 hover:border-black transition-all font-semibold text-sm">Tidak</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Sidebar --}}
            <aside class="lg:col-span-4 self-start">
                <div class="p-8 border border-neutral-200 rounded-2xl sticky top-24 shadow-sm bg-white">
                    <h3 class="text-[18px] font-bold mb-6 tracking-tight">Terkait dengan artikel ini</h3>
                    <ul class="space-y-6">
                        <li>
                            <a href="#" class="group flex flex-col gap-1">
                                <span class="font-semibold text-sm underline group-hover:text-rose-500 transition-colors">Cara kerja pengembalian dana</span>
                                <span class="text-xs text-neutral-500 font-light leading-relaxed">Pelajari perincian waktu dan metode pengembalian dana.</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="group flex flex-col gap-1">
                                <span class="font-semibold text-sm underline group-hover:text-rose-500 transition-colors">Kebijakan Keadaan Luar Biasa</span>
                                <span class="text-xs text-neutral-500 font-light leading-relaxed">Panduan pembatalan untuk kejadian mendesak.</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="group flex flex-col gap-1">
                                <span class="font-semibold text-sm underline group-hover:text-rose-500 transition-colors">Menemukan perincian pengembalian dana</span>
                                <span class="text-xs text-neutral-500 font-light leading-relaxed">Lacak status dana Anda di akun GoFishi.</span>
                            </a>
                        </li>
                    </ul>

                    <div class="mt-10 pt-8 border-t border-neutral-100 flex items-center gap-4 text-neutral-900 group cursor-pointer">
                        <div class="w-10 h-10 rounded-full bg-neutral-100 flex items-center justify-center">
                            <i data-lucide="message-square" class="w-5 h-5"></i>
                        </div>
                        <span class="text-sm font-semibold underline group-hover:no-underline">Hubungi kami</span>
                    </div>
                </div>
            </aside>

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
