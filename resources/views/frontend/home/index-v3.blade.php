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


    {{-- Listings Grid --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-semibold text-gray-900">
                {{ __('Explore stays') }}
            </h2>
            <p class="text-sm text-gray-600">
                {{ count($room_contents) }} {{ __('total properties') }}
            </p>
        </div>

        @if(count($room_contents) <= 0)
            <div class="text-center py-12">
                <p class="text-gray-600 text-lg mb-2">{{ __('No listings found') }}</p>
                <p class="text-gray-500 text-sm">{{ __('Try adjusting your filters or search criteria') }}</p>
                <a href="{{ route('index') }}" class="mt-4 inline-block px-6 py-2 bg-airbnb-red text-white rounded-lg hover:bg-red-600 transition">
                    {{ __('Clear filters') }}
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($room_contents as $room)
                    @include('frontend.perahu._card', ['room' => $room])
                @endforeach
            </div>
        @endif

        {{-- Simple Pagination Placeholder --}}
        @if($room_contents instanceof \Illuminate\Pagination\LengthAwarePaginator && $room_contents->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $room_contents->links() }}
            </div>
        @endif
    </div>

    {{-- Destinations to explore (Attributes from Lokasi Feature) --}}
    <div class="bg-white border-t border-gray-200 py-16" x-data="{ activeTab: 'popular' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 tracking-tight">{{ __('Destinations to explore') }}</h2>
            
            {{-- Modern Tabs --}}
            <div class="flex items-center gap-8 border-b border-gray-200 mb-8 overflow-x-auto no-scrollbar scroll-smooth">
                <button @click="activeTab = 'popular'" 
                        class="pb-4 text-sm font-semibold transition-all relative whitespace-nowrap"
                        :class="activeTab === 'popular' ? 'text-gray-900 border-b-2 border-gray-900' : 'text-gray-500 hover:text-gray-700 hover:border-b-2 hover:border-gray-300'">
                    {{ __('Lokasi Populer') }}
                </button>
                <button @click="activeTab = 'categories'" 
                        class="pb-4 text-sm font-semibold transition-all relative whitespace-nowrap"
                        :class="activeTab === 'categories' ? 'text-gray-900 border-b-2 border-gray-900' : 'text-gray-500 hover:text-gray-700 hover:border-b-2 hover:border-gray-300'">
                    {{ __('Kategori Lokasi') }}
                </button>
                <button @click="activeTab = 'amenities'" 
                        class="pb-4 text-sm font-semibold transition-all relative whitespace-nowrap"
                        :class="activeTab === 'amenities' ? 'text-gray-900 border-b-2 border-gray-900' : 'text-gray-500 hover:text-gray-700 hover:border-b-2 hover:border-gray-300'">
                    {{ __('Fasilitas Unggulan') }}
                </button>
                <button @click="activeTab = 'inspiration'" 
                        class="pb-4 text-sm font-semibold transition-all relative whitespace-nowrap"
                        :class="activeTab === 'inspiration' ? 'text-gray-900 border-b-2 border-gray-900' : 'text-gray-500 hover:text-gray-700 hover:border-b-2 hover:border-gray-300'">
                    {{ __('Inspirasi Perjalanan') }}
                </button>
            </div>

            {{-- Grid Data --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-y-6 gap-x-4">
                
                {{-- Tabs Content: Lokasi Populer --}}
                <template x-if="activeTab === 'popular'">
                    <div class="contents">
                        @forelse($cities->take(12) as $city)
                        @php $cityName = optional($city)->name; @endphp
                        <a href="{{ route('frontend.perahu', ['location' => $cityName]) }}" class="flex flex-col group p-2 hover:bg-gray-50 rounded-xl transition">
                            <span class="text-sm font-bold text-gray-900 group-hover:text-airbnb-red transition-colors">{{ $cityName }}</span>
                            <span class="text-xs text-gray-500 font-light">{{ __('Jelajahi perahu terbaik') }}</span>
                        </a>
                        @empty
                        <div class="col-span-full py-4 text-gray-400 text-sm font-light italic">Belum ada lokasi yang tersedia.</div>
                        @endforelse
                    </div>
                </template>

                {{-- Tabs Content: Kategori Lokasi --}}
                <template x-if="activeTab === 'categories'">
                    <div class="contents">
                        @forelse($location_categories as $l_cat)
                        <a href="{{ route('frontend.lokasi', ['category' => $l_cat->slug]) }}" class="flex flex-col group p-2 hover:bg-gray-50 rounded-xl transition">
                            <span class="text-sm font-bold text-gray-900 group-hover:text-airbnb-red transition-colors">{{ $l_cat->name }}</span>
                            <span class="text-xs text-gray-500 font-light">{{ __('Tipe Lokasi') }}</span>
                        </a>
                        @empty
                        <div class="col-span-full py-4 text-gray-400 text-sm font-light italic">Belum ada kategori yang tersedia.</div>
                        @endforelse
                    </div>
                </template>

                {{-- Tabs Content: Fasilitas Unggulan --}}
                <template x-if="activeTab === 'amenities'">
                    <div class="contents">
                        @forelse($all_amenities as $amenity)
                        <div class="flex flex-col group p-2 hover:bg-gray-50 rounded-xl transition cursor-default">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="{{ $amenity->icon ?? 'fas fa-star' }} text-rose-500 text-xs"></i>
                                <span class="text-sm font-bold text-gray-900">{{ $amenity->title }}</span>
                            </div>
                            <span class="text-xs text-gray-500 font-light">{{ __('Fasilitas Standar') }}</span>
                        </div>
                        @empty
                        <div class="col-span-full py-4 text-gray-400 text-sm font-light italic">Belum ada fasilitas yang tercatat.</div>
                        @endforelse
                    </div>
                </template>

                {{-- Tabs Content: Inspirasi Perjalanan --}}
                <template x-if="activeTab === 'inspiration'">
                    <div class="contents">
                        @forelse($blogs->take(12) as $blg)
                        <a href="{{ route('frontend.blog_details', ['slug' => $blg->slug, 'id' => $blg->id]) }}" class="flex flex-col group p-2 hover:bg-gray-50 rounded-xl transition">
                            <span class="text-sm font-bold text-gray-900 group-hover:text-airbnb-red transition-colors line-clamp-1">{{ $blg->title }}</span>
                            <span class="text-xs text-gray-500 font-light">{{ __('Baca selengkapnya') }}</span>
                        </a>
                        @empty
                        <div class="col-span-full py-4 text-gray-400 text-sm font-light italic">Belum ada inspirasi tersedia.</div>
                        @endforelse
                    </div>
                </template>
            </div>

            {{-- Breadcrumb like info --}}
            <div class="mt-16 pt-10 border-t border-gray-100 flex flex-wrap gap-x-2 gap-y-1 text-[13px] text-gray-500 font-light">
                <a href="{{ route('index') }}" class="hover:text-gray-900 transition underline decoration-gray-300 underline-offset-4">Airbnb</a>
                <span class="opacity-50">›</span>
                <a href="#" class="hover:text-gray-900 transition underline decoration-gray-300 underline-offset-4">Indonesia</a>
                <span class="opacity-50">›</span>
                <a href="#" class="hover:text-gray-900 transition underline decoration-gray-300 underline-offset-4">Jakarta</a>
                <span class="opacity-50">›</span>
                <span class="font-bold text-gray-900">Jakarta Utara</span>
            </div>
        </div>
    </div>

    {{-- Tips & Cerita Wisata (Blog Section) --}}
    @if(isset($blogs) && count($blogs) > 0)
    <div class="bg-gray-50 py-16 mt-12 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">{{ __('Tips & Cerita Wisata') }}</h2>
                    <p class="text-gray-600 mt-2">{{ __('Panduan lengkap memancing dan cerita seru dari komunitas kami.') }}</p>
                </div>
                <a href="{{ route('frontend.blogs') }}" class="hidden sm:inline-block px-6 py-2 bg-white border border-gray-300 rounded-lg text-sm font-semibold hover:bg-gray-50 transition shadow-sm">
                    {{ __('Baca Artikel') }}
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($blogs as $blog)
                    <a href="{{ route('frontend.blog_details', ['slug' => $blog->slug, 'id' => $blog->id]) }}" class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col">
                        <div class="aspect-[4/3] w-full relative overflow-hidden bg-gray-200">
                            <img src="{{ asset('assets/img/blogs/' . $blog->image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-gray-900 shadow-sm">
                                {{ Carbon\Carbon::parse($blog->created_at)->format('d M Y') }}
                            </div>
                        </div>
                        <div class="p-6 flex flex-col flex-grow">
                            <p class="text-xs font-bold text-airbnb-red uppercase tracking-wider mb-2">{{ $blog->category->name ?? __('Mancing') }}</p>
                            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-airbnb-red transition line-clamp-2">
                                {{ $blog->title }}
                            </h3>
                            <p class="text-gray-500 font-light text-sm line-clamp-3 mb-4 flex-grow">
                                {{ strip_tags($blog->content) }}
                            </p>
                            <div class="flex items-center text-sm font-medium text-gray-900 mt-auto">
                                <i data-lucide="user" class="w-4 h-4 mr-2 text-gray-400"></i>
                                {{ $blog->author ?? 'Admin' }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            
            <div class="mt-8 text-center sm:hidden">
                <a href="{{ route('frontend.blogs') }}" class="inline-block px-6 py-3 w-full bg-white border border-gray-300 rounded-lg text-sm font-semibold hover:bg-gray-50 transition shadow-sm">
                    {{ __('Baca Artikel') }}
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
