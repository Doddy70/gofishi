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

    {{-- Airbnb Style Categories Bar --}}
    @if($showCategories)
    <div class="sticky top-[80px] z-40 bg-white border-b border-gray-200 shadow-sm hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center space-x-8 overflow-x-auto no-scrollbar py-4" x-data="{ active: 'All' }">
                @foreach([
                    ['name' => 'All Boats', 'icon' => 'fas fa-ship', 'color' => 'text-gray-800'],
                    ['name' => 'Fishing', 'icon' => 'fas fa-fish', 'color' => 'text-blue-500'],
                    ['name' => 'Luxury', 'icon' => 'fas fa-gem', 'color' => 'text-yellow-500'],
                    ['name' => 'Speedboats', 'icon' => 'fas fa-tachometer-alt', 'color' => 'text-red-500'],
                    ['name' => 'Sailboats', 'icon' => 'fab fa-first-order-alt', 'color' => 'text-indigo-500'],
                    ['name' => 'Party Boats', 'icon' => 'fas fa-glass-cheers', 'color' => 'text-purple-500'],
                    ['name' => 'Catamarans', 'icon' => 'fas fa-water', 'color' => 'text-teal-500'],
                    ['name' => 'Diving', 'icon' => 'fas fa-swimmer', 'color' => 'text-cyan-500'],
                    ['name' => 'Yachts', 'icon' => 'fas fa-anchor', 'color' => 'text-rose-500'],
                    ['name' => 'Overnight', 'icon' => 'fas fa-bed', 'color' => 'text-slate-500'],
                    ['name' => 'Jet Skis', 'icon' => 'fas fa-motorcycle', 'color' => 'text-orange-500']
                ] as $cat)
                <button @click="active = '{{ $cat['name'] }}'" 
                        class="flex flex-col items-center justify-center min-w-[70px] gap-2 transition group"
                        :class="active === '{{ $cat['name'] }}' ? 'text-gray-900 border-b-2 border-gray-900 pb-2 -mb-[18px]' : 'text-gray-500 hover:text-gray-900 hover:border-b-2 hover:border-gray-300 pb-2 -mb-[18px] opacity-70 hover:opacity-100'">
                    <i class="{{ $cat['icon'] }} {{ $cat['color'] }} text-[22px] group-hover:scale-110 transition-transform"></i>
                    <span class="text-[12px] font-semibold whitespace-nowrap">{{ $cat['name'] }}</span>
                </button>
                @endforeach
                
                {{-- Filters Button --}}
                <div class="ml-auto pl-6 border-l border-gray-200 py-1">
                    <button class="flex items-center space-x-2 border border-gray-300 rounded-xl px-4 py-3 hover:border-gray-900 transition">
                        <i class="fas fa-sliders-h text-sm"></i>
                        <span class="text-xs font-bold">{{ __('Filters') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

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

    {{-- Destinations to explore (Benchmark from Screenshot) --}}
    <div class="bg-white border-t border-gray-200 py-16" x-data="{ activeTab: 'nearby' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">{{ __('Destinations to explore') }}</h2>
            
            {{-- Tabs --}}
            <div class="flex items-center gap-8 border-b border-gray-200 mb-8 overflow-x-auto no-scrollbar">
                <button @click="activeTab = 'nearby'" 
                        class="pb-4 text-sm font-medium transition-all relative"
                        :class="activeTab === 'nearby' ? 'text-gray-900 border-b-2 border-gray-900' : 'text-gray-500 hover:text-gray-700'">
                    {{ __('Nearby destinations') }}
                </button>
                <button @click="activeTab = 'other'" 
                        class="pb-4 text-sm font-medium transition-all relative"
                        :class="activeTab === 'other' ? 'text-gray-900 border-b-2 border-gray-900' : 'text-gray-500 hover:text-gray-700'">
                    {{ __('Other types of stays') }}
                </button>
                <button @click="activeTab = 'sights'" 
                        class="pb-4 text-sm font-medium transition-all relative"
                        :class="activeTab === 'sights' ? 'text-gray-900 border-b-2 border-gray-900' : 'text-gray-500 hover:text-gray-700'">
                    {{ __('Nearby Top Sights') }}
                </button>
                <button @click="activeTab = 'inspiration'" 
                        class="pb-4 text-sm font-medium transition-all relative"
                        :class="activeTab === 'inspiration' ? 'text-gray-900 border-b-2 border-gray-900' : 'text-gray-500 hover:text-gray-700'">
                    {{ __('Travel tips & inspiration') }}
                </button>
            </div>

            {{-- Grid Locations --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-y-6 gap-x-4">
                <template x-if="activeTab === 'nearby'">
                    <div class="contents">
                        @foreach($cities->take(12) as $city)
                        @php $cityName = optional($city)->name; @endphp
                        @if(!empty($cityName))
                        <a href="{{ route('frontend.perahu', ['location' => $cityName]) }}" class="flex flex-col group">
                            <span class="text-sm font-semibold text-gray-900 group-hover:underline">{{ $cityName }}</span>
                            <span class="text-sm text-gray-500">{{ __('Vacation rentals') }}</span>
                        </a>
                        @endif
                        @endforeach
                    </div>
                </template>
                <template x-if="activeTab === 'other'">
                    <div class="contents">
                        <a href="#" class="flex flex-col group">
                            <span class="text-sm font-semibold text-gray-900 group-hover:underline">Lakehouse rentals</span>
                            <span class="text-sm text-gray-500">{{ __('United States') }}</span>
                        </a>
                        <a href="#" class="flex flex-col group">
                            <span class="text-sm font-semibold text-gray-900 group-hover:underline">Villa rentals</span>
                            <span class="text-sm text-gray-500">{{ __('United Kingdom') }}</span>
                        </a>
                    </div>
                </template>
            </div>

            {{-- Breadcrumb like info --}}
            <div class="mt-12 pt-8 border-t border-gray-100 flex gap-2 text-sm text-gray-500">
                <a href="{{ route('index') }}" class="hover:underline">Airbnb</a>
                <span>›</span>
                <a href="#" class="hover:underline">Indonesia</a>
                <span>›</span>
                <a href="#" class="hover:underline">Jakarta</a>
                <span>›</span>
                <span class="font-semibold text-gray-900">Jakarta Utara</span>
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
