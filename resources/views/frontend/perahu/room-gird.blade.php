@extends('frontend.layout-airbnb')

@php
    $showCategories = true;
@endphp

@section('pageHeading')
    {{ __('Search Results') }}
@endsection

@section('content')
{{-- Categories Navigation --}}
@include('frontend.partials.categories-slider')

{{-- Dual Panel Container --}}
<div class="flex flex-col lg:flex-row h-[calc(100vh-172px)] overflow-hidden">
    
    {{-- Left Panel: Listing List --}}
    <div class="w-full lg:w-[50%] xl:w-[60%] overflow-y-auto no-scrollbar px-4 sm:px-8 lg:px-12 py-8 bg-white flex flex-col border-r border-gray-100">
        
        {{-- Search Summary --}}
        <div class="mb-10">
            <div class="text-[14px] text-gray-500 font-light mb-2">
                {{ $room_contents->total() }} {{ __('listings found') }}
            </div>
            <h1 class="text-[32px] font-bold text-gray-900 tracking-tight leading-none mb-6">
                @if(request('location'))
                    {{ __('Perahu di') }} {{ request('location') }}
                @elseif(request('category'))
                    {{ __('Kategori') }} {{ request('category') }}
                @else
                    {{ __('Semua Armada Go Fishi') }}
                @endif
            </h1>

            {{-- Smart AI Quick Query --}}
            <div class="relative group max-w-2xl">
                <form action="{{ route('frontend.perahu.ai_search') }}" method="GET">
                    <div class="relative flex items-center bg-white border border-gray-300 rounded-full py-4 px-6 hover:shadow-lg focus-within:shadow-lg focus-within:border-transparent focus-within:ring-2 focus-within:ring-airbnb-red transition-all">
                        <i data-lucide="sparkles" class="w-5 h-5 text-airbnb-red mr-3 group-hover:animate-pulse"></i>
                        <input type="text" name="q" placeholder="{{ __('Tanya AI: Saya ingin mancing 10 orang di Jakarta...') }}" class="w-full bg-transparent border-none focus:ring-0 text-[16px] text-gray-900 placeholder-gray-400" value="{{ request('q') }}">
                        <button type="submit" class="bg-airbnb-red text-white py-2 px-6 rounded-full font-bold text-sm ml-2 hover:brightness-110 transition">
                            AI Search
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-start gap-4 text-emerald-800 text-sm">
                <i data-lucide="bot" class="w-6 h-6 text-emerald-600 shrink-0"></i>
                <div class="leading-relaxed">
                    <span class="font-bold underline">Go Fishi Assistant:</span> {!! session('success') !!}
                </div>
            </div>
        @endif

        {{-- Listing Grid --}}
        @if($room_contents->total() < 1)
            <div class="flex-grow flex flex-col items-center justify-center py-20 text-center">
                <div class="bg-gray-100 p-8 rounded-full mb-6">
                    <i data-lucide="search-x" class="w-16 h-16 text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ __('Tidak menemukan yang pas?') }}</h3>
                <p class="text-gray-500 max-w-sm">{{ __('Coba gunakan fitur tanya AI di atas atau bersihkan filter pencarian Anda.') }}</p>
                <a href="{{ route('frontend.perahu') }}" class="mt-8 font-bold underline">{{ __('Lihat semua armada') }}</a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-x-6 gap-y-12">
                @foreach($room_contents as $room)
                    @include('frontend.perahu._card', ['room' => $room])
                @endforeach
            </div>

            {{-- Pagination Airbnb Style --}}
            <div class="mt-16 pt-12 border-t border-gray-100 flex flex-col items-center gap-6 mb-12">
                {{ $room_contents->appends(request()->input())->links('pagination::tailwind') }}
                <p class="text-sm text-gray-500 font-light">
                    {{ __('Menampilkan') }} {{ $room_contents->firstItem() }} – {{ $room_contents->lastItem() }} {{ __('dari') }} {{ $room_contents->total() }} {{ __('armada') }}
                </p>
            </div>
        @endif
        
    </div>

    {{-- Right Panel: Interactive Map --}}
    <div class="hidden lg:block lg:flex-grow bg-gray-50 relative group/map">
        <div id="main-map" class="absolute inset-0"></div>
        
        {{-- Map Controls --}}
        <div class="absolute top-6 left-1/2 -translate-x-1/2 z-10 flex items-center gap-4">
            <button id="search-area-btn" class="bg-white border border-gray-300 rounded-full px-6 py-2.5 text-[14px] font-bold shadow-xl hover:bg-gray-50 transition active:scale-95 flex items-center gap-2">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                {{ __('Cari di area ini') }}
            </button>
        </div>

        {{-- Floating Price Tooltip --}}
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 z-10 flex items-center gap-2 bg-white/90 backdrop-blur-sm border border-gray-200 py-2.5 px-6 rounded-2xl shadow-2xl opacity-0 group-hover/map:opacity-100 transition-opacity">
            <i data-lucide="info" class="w-4 h-4 text-airbnb-red"></i>
            <span class="text-xs font-bold text-gray-800 tracking-tight">{{ __('Klik marker untuk detail harga armada') }}</span>
        </div>
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
