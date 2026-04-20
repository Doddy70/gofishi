@extends('frontend.layout-airbnb')

@php
    $showCategories = true;
@endphp

@section('pageHeading')
    {{ __('Armada Terdekat') }}
@endsection

@section('content')
<div class="h-[calc(100vh-80px)] overflow-hidden" 
     x-data="perahuSearch()" 
     x-init="initMap()">
    
    <div class="flex h-full no-gutters">
        {{-- List Column (Left) --}}
        <div class="w-full lg:w-[60%] h-full overflow-y-auto no-scrollbar border-r border-gray-100 px-4 md:px-8 py-8 bg-white" 
             id="list-scroll-container">
            
            {{-- Search & Filter Section --}}
            <div class="mb-8">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
                    <div>
                        <div class="text-[14px] text-gray-500 font-light mb-1" x-text="listingCount + ' {{ __('listings found') }}'">
                            {{ $room_contents->total() }} {{ __('listings found') }}
                        </div>
                        <h1 class="text-[28px] font-bold text-gray-900 tracking-tight">
                            @if(request('location'))
                                {{ __('Perahu di') }} {{ request('location') }}
                            @elseif(isset($hub))
                                {{ __('Armada di') }} {{ $hub->title }}
                            @elseif(request('category'))
                                {{ __('Kategori') }} {{ request('category') }}
                            @else
                                {{ __('Semua Armada GoFishi') }}
                            @endif
                        </h1>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center space-x-2">
                        {{-- Search Near Me Button --}}
                        <button @click="searchNearMe()" 
                                :disabled="loading"
                                class="flex items-center space-x-2 px-4 py-2.5 rounded-full border border-gray-200 hover:border-black hover:bg-gray-50 transition shadow-sm bg-white">
                            <i data-lucide="target" class="w-4 h-4 text-rose-500" :class="loading ? 'animate-pulse' : ''"></i>
                            <span class="text-sm font-semibold">{{ __('Cari dekat saya') }}</span>
                        </button>
                    </div>
                </div>

                {{-- AI Search Bar --}}
                <div class="relative group w-full mb-8">
                    <form action="{{ route('frontend.perahu.ai_search') }}" method="GET" @submit.prevent="handleAiSearch($event)">
                        <div class="relative flex items-center bg-white border border-gray-200 rounded-full py-3 px-6 hover:shadow-md transition-shadow group-focus-within:border-black">
                            <i data-lucide="sparkles" class="w-5 h-5 text-rose-500 mr-3"></i>
                            <input type="text" name="q" 
                                   placeholder="{{ __('Ke mana rencana berlayar Anda?') }}" 
                                   class="w-full bg-transparent border-none focus:ring-0 text-[15px] placeholder-gray-400" 
                                   value="{{ request('q') }}">
                            <button type="submit" class="bg-rose-500 text-white p-2 rounded-full absolute right-2 hover:bg-rose-600 transition">
                                <i data-lucide="search" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- List Items Container --}}
            <div id="list-container" class="transition-opacity duration-300" :class="loading ? 'opacity-40 pointer-events-none' : 'opacity-100'">
                @include('frontend.perahu._list_items')
            </div>

            {{-- Mobile Map Toggle --}}
            <div class="lg:hidden fixed bottom-10 left-1/2 -translate-x-1/2 z-[45]">
                <button @click="mobileShowMap = !mobileShowMap" 
                        class="bg-gray-900 text-white px-5 py-3 rounded-full font-bold flex items-center space-x-3 shadow-2xl">
                    <span x-text="mobileShowMap ? '{{ __('Tampilkan Daftar') }}' : '{{ __('Tampilkan Peta') }}'"></span>
                    <i :data-lucide="mobileShowMap ? 'list' : 'map'" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        {{-- Map Column (Right - Desktop) --}}
        <div class="hidden lg:block lg:w-[40%] h-full bg-gray-100 relative">
            <div id="main-map" class="w-full h-full"></div>
            
            {{-- Map Loader --}}
            <div x-show="loading" class="absolute inset-0 bg-white/20 backdrop-blur-[1px] flex items-center justify-center z-20">
                <div class="w-10 h-10 border-4 border-rose-500/20 border-t-rose-500 rounded-full animate-spin"></div>
            </div>
        </div>

        {{-- Mobile Map Overlay --}}
        <div x-show="mobileShowMap" 
             x-cloak
             class="fixed inset-0 z-[40] bg-white lg:hidden">
            <div id="mobile-map" class="w-full h-full"></div>
            <div class="absolute top-4 left-4 z-50">
                <button @click="mobileShowMap = false" class="bg-white p-2 rounded-full shadow-lg border border-gray-200">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
  @if ($hotelbs && $hotelbs->google_map_api_key_status == 1 && !empty(config('google.maps_api_key')))
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('google.maps_api_key') }}&libraries=places"></script>
  @endif
  
  <script>
    function perahuSearch() {
        return {
            loading: false,
            mobileShowMap: false,
            listingCount: {{ $room_contents->total() }},
            map: null,
            markers: [],
            rooms: {!! json_encode($room_contents->items()) !!},

            initMap() {
                setTimeout(() => {
                    const mapEl = document.getElementById('main-map') || document.getElementById('mobile-map');
                    if (!mapEl || typeof google === 'undefined') return;

                    this.map = new google.maps.Map(mapEl, {
                        center: { lat: -6.1214, lng: 106.8302 },
                        zoom: 12,
                        disableDefaultUI: true,
                        zoomControl: true,
                        styles: [
                            { "featureType": "poi", "elementType": "all", "stylers": [{"visibility": "off"}] },
                            { "featureType": "water", "elementType": "all", "stylers": [{"color": "#c8d7d4"}] }
                        ]
                    });

                    this.renderMarkers();
                }, 100);
            },

            renderMarkers() {
                // Clear existing
                this.markers.forEach(m => m.setMap(null));
                this.markers = [];

                const bounds = new google.maps.LatLngBounds();
                let hasCoords = false;

                this.rooms.forEach(room => {
                    if (room.latitude && room.longitude) {
                        const pos = { lat: parseFloat(room.latitude), lng: parseFloat(room.longitude) };
                        bounds.extend(pos);
                        hasCoords = true;

                        // removed price from map labels for premium aesthetic
                        // InfoWindow for detail on click
                        const infoWindow = new google.maps.InfoWindow({
                            content: `
                                <div class="p-2 min-w-[200px]">
                                    <div class="aspect-video mb-2">
                                        <img src="/assets/img/perahu/featureImage/${room.feature_image}" class="w-full h-full object-cover rounded-lg">
                                    </div>
                                    <h5 class="font-bold text-sm mb-1">${room.title}</h5>
                                    <p class="text-xs text-gray-500 mb-2"><i data-lucide="map-pin" class="inline w-3 h-3"></i> ${room.hotel_name || ''}</p>
                                    <div class="flex items-center justify-between mt-2">
                                        <a href="/perahu/${room.slug}/${room.id}" class="w-full text-center text-xs bg-gray-900 text-white px-3 py-2 rounded-lg hover:bg-black transition font-bold">{{ __('Cek Jadwal & Detail') }}</a>
                                    </div>
                                </div>
                            `
                        });

                        const marker = new google.maps.Marker({
                            position: pos,
                            map: this.map,
                            label: null, // removed price for premium aesthetic
                            // Memberi scale > 0 agar marker bisa di-klik. 
                            // Gunakan path lingkaran transparan sebagai area klik.
                            icon: { 
                                path: google.maps.SymbolPath.CIRCLE, 
                                scale: 20,
                                fillOpacity: 0,
                                strokeOpacity: 0,
                                labelOrigin: new google.maps.Point(0, 0)
                            }
                        });

                        marker.addListener("click", () => {
                            // Menutup InfoWindow lain jika ada
                            if (window.currentInfoWindow) window.currentInfoWindow.close();
                            
                            infoWindow.open(this.map, marker);
                            window.currentInfoWindow = infoWindow;

                            // Re-init lucide icons inside infowindow
                            setTimeout(() => {
                                if (window.lucide) lucide.createIcons();
                            }, 100);
                        });
                        this.markers.push(marker);
                    }
                });

                if (hasCoords) this.map.fitBounds(bounds);
            },

            async searchNearMe() {
                if (!navigator.geolocation) {
                    alert('Geolocation tidak didukung browser Anda');
                    return;
                }

                this.loading = true;
                navigator.geolocation.getCurrentPosition(
                    async (pos) => {
                        const lat = pos.coords.latitude;
                        const lng = pos.coords.longitude;
                        await this.fetchRooms({ lat, lng });
                    },
                    (err) => {
                        this.loading = false;
                        alert('Gagal mendapatkan lokasi: ' + err.message);
                    }
                );
            },

            async fetchRooms(params = {}) {
                this.loading = true;
                const url = new URL(window.location.href);
                Object.keys(params).forEach(key => url.searchParams.set(key, params[key]));

                try {
                    const response = await fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await response.json();
                    
                    document.getElementById('list-container').innerHTML = data.html;
                    this.rooms = data.items;
                    this.listingCount = this.rooms.length;
                    
                    // Re-init lucide icons for new content
                    if (window.lucide) lucide.createIcons();
                    
                    this.renderMarkers();
                    
                    // Scroll to top of list
                    document.getElementById('list-scroll-container').scrollTo(0, 0);
                    
                    // Update URL without reload
                    window.history.pushState({}, '', url);
                } catch (e) {
                    console.error('Fetch error:', e);
                } finally {
                    this.loading = false;
                }
            },

            async handleAiSearch(e) {
                const form = e.target;
                const q = form.querySelector('[name="q"]').value;
                await this.fetchRooms({ q });
            }
        }
    }
  </script>

  <style>
    .map-price-label-v3 {
        background: white !important;
        border: 1px solid #ddd !important;
        border-radius: 20px !important;
        padding: 5px 12px !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
        cursor: pointer !important;
    }
    .map-price-label-v3:hover {
        background: #000 !important;
        color: #fff !important;
        z-index: 1000;
    }
    .sticky-map {
        position: sticky;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }
  </style>
@endsection
