@extends('frontend.layout-airbnb')

@php
    $showCategories = true;
@endphp

@section('pageHeading')
    {{ __('Search Results') }}
@endsection

@section('content')
{{-- Categories Navigation (Sticky) --}}
@include('frontend.partials.categories-slider')

{{-- Main Grid List --}}
<div class="max-w-[2520px] mx-auto xl:px-20 md:px-10 sm:px-2 px-4 py-8" x-data="{ showMap: false }">
    
    {{-- Search & Filter Summary --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <div class="text-[14px] text-gray-500 font-light mb-1">
                {{ $room_contents->total() }} {{ __('listings found') }}
            </div>
            <h1 class="text-[28px] font-bold text-gray-900 tracking-tight">
                @if(request('location'))
                    {{ __('Perahu di') }} {{ request('location') }}
                @elseif(request('category'))
                    {{ __('Kategori') }} {{ request('category') }}
                @else
                    {{ __('Semua Armada Go Fishi') }}
                @endif
            </h1>
        </div>
        
        {{-- AI Search Bar (Narrower for Airbnb look) --}}
        <div class="relative group w-full md:w-[400px]">
            <form action="{{ route('frontend.perahu.ai_search') }}" method="GET">
                <div class="relative flex items-center bg-white border border-gray-200 rounded-full py-2.5 px-5 hover:shadow-md transition-shadow">
                    <i data-lucide="sparkles" class="w-4 h-4 text-rose-500 mr-2"></i>
                    <input type="text" name="q" placeholder="{{ __('Tanya AI...') }}" class="w-full bg-transparent border-none focus:ring-0 text-sm" value="{{ request('q') }}">
                </div>
            </form>
        </div>
    </div>

    {{-- Listing Grid: Full Width columns --}}
    @if($room_contents->total() < 1)
        <div class="py-20 text-center flex flex-col items-center">
            <div class="bg-gray-50 p-10 rounded-full mb-6">
                <i data-lucide="search-x" class="w-16 h-16 text-gray-300"></i>
            </div>
            <h3 class="text-xl font-bold">{{ __('Tidak menemukan hasil') }}</h3>
            <p class="text-gray-500">{{ __('Coba hapus filter atau gunakan pencarian lain.') }}</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-x-6 gap-y-10 transition-all duration-500">
            @foreach($room_contents as $room)
                @include('frontend.perahu._card', ['room' => $room])
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-16 pt-12 border-t border-gray-100 flex flex-col items-center gap-4">
            {{ $room_contents->appends(request()->input())->links('pagination::tailwind') }}
            <p class="text-sm text-gray-400">
                {{ __('Menampilkan') }} {{ $room_contents->total() }} {{ __('armada terbaik.') }}
            </p>
        </div>
    @endif

    {{-- Floating Map Toggle Button --}}
    <div class="fixed bottom-10 left-1/2 -translate-x-1/2 z-[45]">
        <button @click="showMap = !showMap" 
                class="bg-gray-900 text-white px-5 py-3.5 rounded-full font-bold flex items-center space-x-3 shadow-2xl hover:scale-105 active:scale-95 transition backdrop-blur-md">
            <span x-text="showMap ? 'Tampilkan Daftar' : 'Tampilkan Peta'"></span>
            <i :data-lucide="showMap ? 'list' : 'map'" class="w-5 h-5"></i>
        </button>
    </div>

    {{-- Full Screen Map Overlay --}}
    <div x-show="showMap" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-10"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-10"
         class="fixed inset-0 z-[40] bg-white">
        <div id="main-map" class="w-full h-full"></div>
    </div>
</div>
@endsection

@section('script')
  @if ($hotelbs && $hotelbs->google_map_api_key_status == 1 && !empty(config('google.maps_api_key')))
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('google.maps_api_key') }}&libraries=places&callback=initGeoSearchMap" async defer></script>
  @endif
  
  <script>
    "use strict";
    var room_contents = {!! json_encode($room_contents->items()) !!};
    
    function initGeoSearchMap() {
        if (typeof google === 'undefined') return;
        
        const mapElement = document.getElementById('main-map');
        const map = new google.maps.Map(mapElement, {
            center: { 
                lat: {{ request('lat') ?? -6.1214 }}, 
                lng: {{ request('lng') ?? 106.8302 }} 
            },
            zoom: 13,
            maxZoom: 18,
            minZoom: 5,
            disableDefaultUI: true,
            zoomControl: true,
            gestureHandling: 'greedy',
            styles: [
                { "featureType": "administrative", "elementType": "labels.text.fill", "stylers": [{"color": "#444444"}] },
                { "featureType": "landscape", "elementType": "all", "stylers": [{"color": "#f2f2f2"}] },
                { "featureType": "poi", "elementType": "all", "stylers": [{"visibility": "off"}] },
                { "featureType": "road", "elementType": "all", "stylers": [{"saturation": -100}, {"lightness": 45}] },
                { "featureType": "transit", "elementType": "all", "stylers": [{"visibility": "off"}] },
                { "featureType": "water", "elementType": "all", "stylers": [{"color": "#c8d7d4"}, {"visibility": "on"}] }
            ]
        });

        const bounds = new google.maps.LatLngBounds();

        // Add Premium Price Markers
        room_contents.forEach(room => {
            if (room.latitude && room.longitude) {
                const pos = { lat: parseFloat(room.latitude), lng: parseFloat(room.longitude) };
                bounds.extend(pos);

                const price = (room.price_day_1 / 1000000).toFixed(1) + 'jt';
                
                // Content for Custom Marker Overlay (Simple implementation using Standard Marker with Label)
                const marker = new google.maps.Marker({
                    position: pos,
                    map: map,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 0 // Hide default icon
                    },
                    label: {
                        text: 'Rp' + price,
                        color: 'black',
                        fontSize: '14px',
                        fontWeight: 'bold',
                        className: 'map-price-label-v2'
                    }
                });

                marker.addListener("click", () => {
                   window.location.href = `/perahu/${room.slug}/${room.id}`;
                });
            }
        });

        if (room_contents.length > 0 && !{{ request('lat') ? 'true' : 'false' }}) {
            map.fitBounds(bounds);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        if(window.google) initGeoSearchMap();
    });
  </script>

  <style>
    /* Premium Map Labels */
    .map-price-label-v2 {
        background: white !important;
        border: 1px solid #DDDDDD !important;
        border-radius: 28px !important;
        padding: 6px 14px !important;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1) !important;
        transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
        cursor: pointer !important;
    }
    .map-price-label-v2:hover {
        transform: scale(1.1) !important;
        z-index: 1000 !important;
        background: #000 !important;
        color: #fff !important;
        border-color: #000 !important;
        box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important;
    }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
  </style>
@endsection
