@extends('frontend.layout-airbnb')

@php
    $showCategories = true;
@endphp

@section('pageHeading')
    {{ __('Jelajahi Dermaga') }}
@endsection

@section('content')
{{-- Dual Panel Container --}}
<div class="flex flex-col lg:flex-row h-[calc(100vh-172px)] overflow-hidden">
    
    {{-- Left Panel: Listing List --}}
    <div class="w-full lg:w-[50%] xl:w-[60%] overflow-y-auto no-scrollbar px-4 sm:px-8 lg:px-12 py-8 bg-white flex flex-col border-r border-gray-100">
        
        {{-- Search Summary --}}
        <div class="mb-10">
            <div class="text-[14px] text-gray-500 font-light mb-2">
                {{ $currentPageData->total() }} {{ __('dermaga ditemukan') }}
            </div>
            <h1 class="text-[32px] font-bold text-gray-900 tracking-tight leading-none mb-6">
                @if(request('location'))
                    {{ __('Dermaga di') }} {{ request('location') }}
                @else
                    {{ __('Pusat Keberangkatan GoFishi') }}
                @endif
            </h1>

            {{-- Smart Destination Search --}}
            <div class="relative group max-w-2xl">
                <form action="{{ route('frontend.lokasi') }}" method="GET">
                    <div class="relative flex items-center bg-white border border-gray-300 rounded-full py-4 px-6 hover:shadow-lg focus-within:shadow-lg focus-within:border-transparent focus-within:ring-2 focus-within:ring-rose-500 transition-all">
                        <i data-lucide="map-pin" class="w-5 h-5 text-rose-500 mr-3"></i>
                        <input type="text" name="location" placeholder="{{ __('Masukan lokasi: Pantai Mutiara, Ancol, Muara Baru...') }}" class="w-full bg-transparent border-none focus:ring-0 text-[16px] text-gray-900 placeholder-gray-400" value="{{ request('location') }}">
                        <button type="submit" class="bg-gray-900 text-white py-2 px-6 rounded-full font-bold text-sm ml-2 hover:bg-black transition">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Listing Grid --}}
        @if($currentPageData->total() < 1)
            <div class="flex-grow flex flex-col items-center justify-center py-20 text-center">
                <div class="bg-gray-100 p-8 rounded-full mb-6">
                    <i data-lucide="map-pin-off" class="w-16 h-16 text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ __('Lokasi belum tersedia') }}</h3>
                <p class="text-gray-500 max-w-sm">{{ __('Kami sedang bekerja sama dengan banyak pemilik dermaga di area ini. Silakan cek area lain.') }}</p>
                <a href="{{ route('frontend.lokasi') }}" class="mt-8 font-bold underline">{{ __('Semua Lokasi') }}</a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-x-6 gap-y-12">
                @foreach($currentPageData as $hotel)
                    @include('frontend.lokasi._location_card', ['hotel' => $hotel])
                @endforeach
            </div>

            {{-- Pagination Airbnb Style --}}
            <div class="mt-16 pt-12 border-t border-gray-100 flex flex-col items-center gap-6 mb-12">
                {{ $currentPageData->appends(request()->input())->links('pagination::tailwind') }}
                <p class="text-sm text-gray-500 font-light">
                    {{ __('Menampilkan') }} {{ $currentPageData->firstItem() }} – {{ $currentPageData->lastItem() }} {{ __('dari') }} {{ $currentPageData->total() }} {{ __('dermaga') }}
                </p>
            </div>
        @endif
        
    </div>

    {{-- Right Panel: Interactive Map --}}
    <div class="hidden lg:block lg:flex-grow bg-[#E5E3DF] relative">
        <div id="main-map" class="absolute inset-0"></div>
        
        {{-- Map Overlay Info --}}
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 z-10 flex items-center gap-2 bg-white/95 backdrop-blur-sm border border-gray-200 py-3 px-8 rounded-2xl shadow-2xl">
            <i data-lucide="help-circle" class="w-4 h-4 text-rose-500"></i>
            <span class="text-xs font-bold text-gray-900 tracking-tight">{{ __('Klik ikon pin dermaga untuk melihat detail dermaga') }}</span>
        </div>
    </div>

</div>
@endsection

@section('script')
  @if ($hotelbs && $hotelbs->google_map_api_key_status == 1 && !empty(config('google.maps_api_key')))
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('google.maps_api_key') }}&libraries=places&callback=initHotelMap" async defer></script>
  @endif
  
  <script>
    "use strict";
    var hotel_contents = {!! json_encode($currentPageData->items()) !!};
    
    function initHotelMap() {
        if (typeof google === 'undefined') return;
        
        const mapElement = document.getElementById('main-map');
        const map = new google.maps.Map(mapElement, {
            center: { 
                lat: {{ request('lat') ?? -6.1214 }}, 
                lng: {{ request('lng') ?? 106.8302 }} 
            },
            zoom: 12,
            maxZoom: 18,
            minZoom: 5,
            disableDefaultUI: true,
            zoomControl: true,
            styles: [
                { "featureType": "administrative", "elementType": "labels.text.fill", "stylers": [{"color": "#444444"}] },
                { "featureType": "landscape", "elementType": "all", "stylers": [{"color": "#f2f2f2"}] },
                { "featureType": "road", "elementType": "all", "stylers": [{"saturation": -100}, {"lightness": 45}] },
                { "featureType": "water", "elementType": "all", "stylers": [{"color": "#c8d7d4"}, {"visibility": "on"}] }
            ]
        });

        const bounds = new google.maps.LatLngBounds();

        // Add Premium Markers for Piers
        hotel_contents.forEach(h => {
            if (h.latitude && h.longitude) {
                const pos = { lat: parseFloat(h.latitude), lng: parseFloat(h.longitude) };
                bounds.extend(pos);

                const marker = new google.maps.Marker({
                    position: pos,
                    map: map,
                    icon: {
                        url: "{{ asset('assets/img/hotel/logo/') }}/" + h.logo, // Try to use pier logo
                        scaledSize: new google.maps.Size(40, 40),
                        origin: new google.maps.Point(0,0),
                        anchor: new google.maps.Point(20, 20),
                        labelOrigin: new google.maps.Point(20, 50)
                    },
                    label: {
                        text: h.title,
                        color: 'black',
                        fontSize: '12px',
                        fontWeight: 'bold',
                        className: 'map-label-pier'
                    }
                });

                marker.addListener("click", () => {
                   window.location.href = `/lokasi/${h.slug}/${h.id}`;
                });
            }
        });

        if (hotel_contents.length > 0) {
            map.fitBounds(bounds);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        if(window.google) initHotelMap();
    });
  </script>

  <style>
    .map-label-pier {
        background: white !important;
        border: 1px solid #DDDDDD !important;
        border-radius: 8px !important;
        padding: 4px 10px !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
        white-space: nowrap !important;
    }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
  </style>
@endsection
