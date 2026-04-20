@extends('frontend.layout-airbnb')

@php
  $showCategories = true;
  $categories = [
    ['name' => 'All', 'icon' => '🌍'],
    ['name' => 'Beachfront', 'icon' => '🌊'],
    ['name' => 'Cabins', 'icon' => '🪵'],
    ['name' => 'Villas', 'icon' => '🏛️'],
    ['name' => 'Apartments', 'icon' => '🏙️'],
    ['name' => 'Luxury', 'icon' => '✨'],
    ['name' => 'Budget', 'icon' => '🎯'],
    ['name' => 'Mountain', 'icon' => '🏔️'],
  ];
@endphp

@section('pageHeading')
  {{ __('Home') }}
@endsection

@section('content')
<div class="w-full bg-white" x-data="{ activeCategory: 'All' }">
    {{-- Hero Section with Search --}}
    <div class="bg-gradient-to-b from-gray-50 to-white pt-10 pb-4 border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-extrabold text-center text-gray-900 mb-2 tracking-tight">
                {{ __('Discover Your Perfect Getaway') }}
            </h1>
            <p class="text-center text-gray-500 font-medium mb-10 text-[15px]">
                {{ __('Find the best boats, docks, and fishing spots in Indonesia') }}
            </p>
            
            {{-- Search Bar Partial --}}
            @include('frontend.partials.hero-search')
        </div>
    </div>


    {{-- Listings Grid (Enhanced Premium Grid) --}}
    <div class="bg-gray-50/50 py-20 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between gap-4 mb-12">
                <div style="flex: 1; min-width: 0;">
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tight leading-tight mb-2">
                        {{ __('Jelajahi Armada Populer') }}
                    </h2>
                    <p class="text-gray-500 font-light text-sm sm:text-base leading-snug">{{ __('Pilihan kapal terbaik untuk pengalaman mancing tak terlupakan') }}</p>
                </div>
                <a href="{{ route('frontend.perahu') }}" class="group flex items-center gap-2 text-sm font-bold text-gray-900 hover:text-airbnb-red transition-colors" style="flex-shrink: 0; white-space: nowrap;">
                    {{ __('View all') }}
                    <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full border border-gray-200 flex items-center justify-center group-hover:border-airbnb-red group-hover:bg-airbnb-red group-hover:text-white transition-all">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </div>
                </a>
            </div>

            @if(count($room_contents) <= 0)
                <div class="text-center py-24 bg-white rounded-3xl border border-dashed border-gray-200 shadow-sm">
                    <i data-lucide="ship-wheel" class="w-16 h-16 mx-auto text-gray-200 mb-6 animate-slow-spin"></i>
                    <p class="text-gray-900 text-xl font-bold mb-2">{{ __('Belum ada armada tersedia') }}</p>
                    <p class="text-gray-500 text-[15px]">{{ __('Tim kami sedang menyiapkan armada terbaik untuk Anda.') }}</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-12">
                    @foreach($room_contents->take(8) as $room)
                        @include('frontend.perahu._card', ['room' => $room])
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Destinations to explore (High-Fidelity Card Carousel) --}}
    <div class="bg-white py-16 border-t border-gray-100" 
         x-data="{ 
            activeTab: 'popular',
            scrollLeft() { this.$refs.carousel.scrollBy({ left: -300, behavior: 'smooth' }) },
            scrollRight() { this.$refs.carousel.scrollBy({ left: 300, behavior: 'smooth' }) }
         }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <h2 class="text-[26px] font-bold text-gray-900 tracking-tight group cursor-pointer flex items-center gap-2">
                        {{ __('Destinations to explore') }}
                        <i data-lucide="chevron-right" class="w-6 h-6 text-gray-900 group-hover:translate-x-1 transition-transform"></i>
                    </h2>
                    <p class="text-gray-500 font-light mt-1">{{ __('Temukan spot mancing terbaik di seluruh penjuru negeri') }}</p>
                </div>
                
                {{-- Carousel Controls --}}
                <div class="hidden sm:flex items-center gap-2">
                    <button @click="scrollLeft()" class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50 hover:shadow-md transition bg-white shadow-sm">
                        <i data-lucide="chevron-left" class="w-4 h-4 text-gray-700"></i>
                    </button>
                    <button @click="scrollRight()" class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50 hover:shadow-md transition bg-white shadow-sm">
                        <i data-lucide="chevron-right" class="w-4 h-4 text-gray-700"></i>
                    </button>
                </div>
            </div>

            {{-- 3-Slide Card Carousel for Lokasi --}}
            <div class="relative group" x-data="{ 
                scrollLeft() { this.$refs.lokasiCarousel.scrollBy({ left: -this.$refs.lokasiCarousel.offsetWidth, behavior: 'smooth' }) },
                scrollRight() { this.$refs.lokasiCarousel.scrollBy({ left: this.$refs.lokasiCarousel.offsetWidth, behavior: 'smooth' }) }
            }">
                {{-- Carousel Controls (Hanya muncul jika data lebih dari 3) --}}
                @if(count($cities) > 3)
                <div class="absolute -top-16 right-0 flex items-center gap-2">
                    <button @click="scrollLeft()" class="w-9 h-9 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-900 hover:text-white transition-all bg-white shadow-sm z-10">
                        <i data-lucide="chevron-left" class="w-5 h-5"></i>
                    </button>
                    <button @click="scrollRight()" class="w-9 h-9 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-900 hover:text-white transition-all bg-white shadow-sm z-10">
                        <i data-lucide="chevron-right" class="w-5 h-5"></i>
                    </button>
                </div>
                @endif

                <div x-ref="lokasiCarousel" 
                     class="flex gap-6 overflow-x-auto no-scrollbar scroll-smooth pb-8 snap-x snap-mandatory"
                     style="scroll-behavior: smooth;">
                    
                    @php
                        // Gunakan $hubs yang merupakan data Dermaga (Marina Ancol, dkk.)
                        $activeDestinations = $hubs ?? collect([]);
                    @endphp

                    @forelse($activeDestinations as $hub)
                        @php 
                            $hubName = $hub->name; 
                            // Fullstack Guardian: Fallback image logic for live server safety
                            $imagePath = 'assets/img/hotel/logo/' . $hub->logo;
                            $fullPath = public_path($imagePath);
                            
                            if (!empty($hub->logo) && file_exists($fullPath)) {
                                $hubImg = asset($imagePath);
                            } else {
                                // Premium fallback image if original is missing
                                $hubImg = 'https://images.unsplash.com/photo-1544551763-47a0159f9234?auto=format&fit=crop&w=800&q=80';
                            }
                        @endphp
                        <div class="flex-none w-full sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)] snap-start group">
                            <a href="{{ route('frontend.perahu', ['location' => $hubName]) }}" class="block">
                                <div class="relative aspect-[16/10] sm:aspect-[4/3] rounded-2xl overflow-hidden mb-4 shadow-md border border-gray-100 bg-gray-50">
                                    <img src="{{ $hubImg }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" 
                                         alt="{{ $hubName }}"
                                         width="600" height="400"
                                         onerror="this.src='https://images.unsplash.com/photo-1544551763-47a0159f9234?auto=format&fit=crop&w=800&q=80';">
                                    
                                    {{-- Overlay Gradasi Premium --}}
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-80 group-hover:opacity-60 transition-opacity"></div>
                                    
                                    {{-- Informasi Destinasi --}}
                                    <div class="absolute bottom-6 left-6 right-6 text-white text-center sm:text-left transition-all group-hover:bottom-8">
                                        <h4 class="text-2xl font-extrabold tracking-tight drop-shadow-md">{{ $hubName }}</h4>
                                        <div class="flex items-center justify-center sm:justify-start gap-2 opacity-95 mt-2">
                                            <div class="w-8 h-8 rounded-full bg-airbnb-red flex items-center justify-center shadow-lg">
                                                <i data-lucide="anchor" class="w-4 h-4 text-white fill-white"></i>
                                            </div>
                                            <span class="text-sm font-bold tracking-wide uppercase">{{ __('Dermaga Utama') }}</span>
                                        </div>
                                    </div>
                                    
                                    {{-- Badge Status Interaktif --}}
                                    <div class="absolute top-4 left-4 transform -translate-y-2 group-hover:translate-y-0 transition-transform">
                                        <span class="px-4 py-1.5 bg-white/95 backdrop-blur-md text-gray-900 text-[10px] font-black uppercase tracking-widest rounded-lg shadow-xl border border-white/50">{{ __('Lokasi Aktif') }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="w-full py-24 text-center bg-gray-50 rounded-3xl border border-dashed border-gray-200">
                            <i data-lucide="map-pin" class="w-14 h-14 mx-auto text-gray-300 mb-4"></i>
                            <h4 class="text-gray-400 font-bold text-lg">{{ __('Belum ada lokasi dermaga aktif') }}</h4>
                            <p class="text-gray-400 text-sm mt-1">Gunakan panel admin untuk menambahkan dermaga baru</p>
                        </div>
                    @endforelse

                    {{-- Card Tambahan untuk UX (Hanya jika data kurang) --}}
                    @if(count($activeDestinations) > 0 && count($activeDestinations) < 3)
                        <div class="flex-none w-full sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)] snap-start flex items-center justify-center">
                            <div class="text-center p-8 bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">
                                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('Destinasi Berikutnya') }}</p>
                                <p class="text-sm text-gray-400 mt-2">Segera hadir lebih banyak dermaga terbaik untuk Anda.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Bottom Tabs Section (Consolidated) --}}
            <div class="mt-16 pt-16 border-t border-gray-100">
                <h3 class="text-[22px] font-semibold text-gray-900 mb-8">{{ __('Inspirasi untuk perjalanan Anda') }}</h3>
                
                <div class="flex items-center gap-8 border-b border-gray-200 mb-10 overflow-x-auto no-scrollbar scroll-smooth">
                    @php
                        $tabs = [
                            ['id' => 'popular', 'label' => __('Populer')],
                            ['id' => 'categories', 'label' => __('Kategori Kapal')],
                            ['id' => 'amenities', 'label' => __('Fasilitas')],
                            ['id' => 'inspiration', 'label' => __('Cerita Wisata')]
                        ];
                    @endphp
                    @foreach($tabs as $tab)
                    <button @click="activeTab = '{{ $tab['id'] }}'" 
                            class="pb-4 text-[14px] font-semibold transition-all relative whitespace-nowrap"
                            :class="activeTab === '{{ $tab['id'] }}' ? 'text-gray-900' : 'text-gray-500 hover:text-gray-700'">
                        {{ $tab['label'] }}
                        <div x-show="activeTab === '{{ $tab['id'] }}'" 
                             class="absolute bottom-0 left-0 right-0 h-[2px] bg-gray-900"></div>
                    </button>
                    @endforeach
                </div>

                {{-- Tab Content (Refined for better UX) --}}
                <div class="relative min-h-[160px]">
                    <div x-show="activeTab === 'popular'" 
                         x-transition:enter="transition duration-300 transform"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-y-8 gap-x-4">
                        @foreach($cities->take(12) as $city)
                            <a href="{{ route('frontend.perahu', ['location' => $city->name]) }}" class="flex flex-col group">
                                <span class="text-[14px] font-semibold text-gray-800 group-hover:underline">{{ $city->name }}</span>
                                <span class="text-[14px] text-gray-500 font-light">{{ __('Trip mencing terbaik') }}</span>
                            </a>
                        @endforeach
                    </div>

                    <div x-show="activeTab === 'categories'" 
                         x-transition:enter="transition duration-300 transform"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-y-8 gap-x-4">
                        @foreach($location_categories->take(12) as $l_cat)
                            <a href="{{ route('frontend.lokasi', ['category' => $l_cat->slug]) }}" class="flex flex-col group">
                                <span class="text-[14px] font-semibold text-gray-800 group-hover:underline">{{ $l_cat->name }}</span>
                                <span class="text-[14px] text-gray-500 font-light">{{ __('Tipe Perjalanan') }}</span>
                            </a>
                        @endforeach
                    </div>

                    <div x-show="activeTab === 'amenities'" 
                         x-transition:enter="transition duration-300 transform"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-y-8 gap-x-4">
                        @foreach($all_amenities->take(12) as $amenity)
                            <div class="flex flex-col group cursor-default">
                                <span class="text-[14px] font-semibold text-gray-800">{{ $amenity->title }}</span>
                                <span class="text-[14px] text-gray-500 font-light">{{ __('Fasilitas Kapal') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div x-show="activeTab === 'inspiration'" 
                         x-transition:enter="transition duration-300 transform"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-y-8 gap-x-6">
                        @foreach($blogs->take(4) as $blg)
                            <a href="{{ route('frontend.blog_details', ['slug' => $blg->slug, 'id' => $blg->id]) }}" class="flex gap-4 group">
                                <div class="w-12 h-12 rounded-lg overflow-hidden shrink-0 bg-gray-100">
                                    <img src="{{ asset('assets/img/blogs/' . $blg->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300" width="48" height="48">
                                </div>
                                <div class="flex flex-col justify-center min-w-0">
                                    <span class="text-[14px] font-semibold text-gray-800 group-hover:underline line-clamp-1">{{ $blg->title }}</span>
                                    <span class="text-[13px] text-gray-500 font-light">{{ __('Tips Wisata') }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Breadcrumb like info --}}
            <div class="mt-20 pt-10 border-t border-gray-100 flex flex-wrap gap-x-2 gap-y-1 text-[13px] text-gray-500 font-light">
                <a href="{{ route('index') }}" class="hover:text-gray-900 transition underline decoration-gray-300 underline-offset-4">GoFishi</a>
                <span class="opacity-30">›</span>
                <span class="font-bold text-gray-900 uppercase tracking-wider">Jakarta Utara</span>
            </div>
        </div>
    </div>

    {{-- Tips & Cerita Wisata (High-Fidelity Modern Section) --}}
    @if(isset($blogs) && count($blogs) > 0)
    <div class="bg-white py-24 mt-12 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tight group cursor-pointer flex items-center gap-2">
                        {{ __('Tips & Panduan Mancing') }}
                        <i data-lucide="chevron-right" class="w-7 h-7 text-gray-900 group-hover:translate-x-1 transition-transform"></i>
                    </h2>
                    <p class="text-gray-500 font-light mt-2">{{ __('Dapatkan inspirasi dan tips terbaik dari para ahli mancing kami.') }}</p>
                </div>
                <a href="{{ route('frontend.blogs') }}" class="hidden sm:inline-flex items-center gap-2 text-[15px] font-bold text-gray-900 hover:underline underline-offset-4 decoration-2 decoration-airbnb-red transition">
                    {{ __('Lihat semua artikel') }}
                    <i data-lucide="arrow-up-right" class="w-4 h-4 text-airbnb-red"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
                @foreach($blogs->take(3) as $blog)
                    <article class="group relative flex flex-col cursor-pointer" onclick="window.location.href='{{ route('frontend.blog_details', ['slug' => $blog->slug, 'id' => $blog->id]) }}'">
                        <div class="aspect-[4/5] w-full relative overflow-hidden rounded-3xl bg-gray-100 mb-6 shadow-sm">
                            <img src="{{ asset('assets/img/blogs/' . $blog->image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" width="400" height="500">
                            
                            {{-- Category Badge --}}
                            <div class="absolute top-5 left-5 bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-full z-10 shadow-sm border border-white/20">
                                <span class="text-[11px] font-black text-airbnb-red uppercase tracking-widest">{{ $blog->category->name ?? __('Travel') }}</span>
                            </div>

                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-8">
                                <p class="text-white text-[15px] font-medium leading-relaxed line-clamp-3">
                                    {{ strip_tags($blog->content) }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex flex-col gap-2">
                            <h3 class="text-xl font-bold text-gray-900 leading-snug group-hover:text-airbnb-red transition-colors line-clamp-2">
                                {{ $blog->title }}
                            </h3>
                            <div class="flex items-center gap-3 text-[13px] text-gray-500 font-medium">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                    {{ $blog->created_at->format('d M Y') }}
                                </div>
                                <span>·</span>
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                    5 min read
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            
            <div class="mt-12 text-center sm:hidden">
                <a href="{{ route('frontend.blogs') }}" class="inline-flex items-center justify-center w-full py-4 bg-gray-900 text-white font-bold rounded-2xl hover:bg-black transition shadow-lg">
                    {{ __('Baca Semua Artikel') }}
                </a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('script')
  <script>
    document.addEventListener('DOMContentLoaded', () => {
        console.log('New Template Home Loaded');
    });
  </script>
@endsection
