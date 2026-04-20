@extends('frontend.layout-airbnb')

@php
    $langId = $currentLanguageInfo ? $currentLanguageInfo->id : get_lang()->id;
    
    // Safety check for $hotel
    if (!isset($hotel) || !$hotel) {
        return; 
    }

    $mainImage = !empty($hotel->logo) ? asset('assets/img/hotel/logo/' . $hotel->logo) : 'https://images.unsplash.com/photo-1559136555-9303baea8ebd?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80';
    $rating = round($hotel->average_rating ?? 4.8, 1);
    $reviewsCount = $numOfReview ?? 0;
@endphp

@section('pageHeading')
    {{ optional($hotel)->title }}
@endsection

@section('content')
{{-- AIRBNB JAY PEAK STYLE REPLICA --}}
<div class="bg-white min-h-screen">
    
    {{-- 1. HERO & SEARCH BOX --}}
    <div class="relative max-w-[1440px] mx-auto px-6 lg:px-20 pt-8 pb-10">
        {{-- Breadcrumbs --}}
        <div class="text-[14px] text-gray-500 mb-4 font-medium">
            Airbnb &nbsp;<span class="mx-1">></span>&nbsp; {{ __('Indonesia') }} &nbsp;<span class="mx-1">></span>&nbsp; {{ optional($hotel)->city ?? 'Jakarta' }} &nbsp;<span class="mx-1">></span>&nbsp; <span class="text-gray-900">{{ optional($hotel)->title }}</span>
        </div>
        
        {{-- Main Hero Image --}}
        <div class="relative h-[480px] lg:h-[500px] w-full rounded-2xl overflow-hidden shadow-sm">
            <img src="{{ $mainImage }}" class="w-full h-full object-cover">
            
            {{-- Floating Search Card --}}
            <div class="absolute top-1/2 -translate-y-1/2 left-8 lg:left-16 w-[90%] sm:w-[440px] bg-white rounded-2xl shadow-[0_16px_32px_rgba(0,0,0,0.15)] p-8 z-20">
                <h1 class="text-[28px] font-extrabold text-airbnb-dark leading-tight mb-8">
                    {{ __('Places to stay near') }}<br>{{ optional($hotel)->title }}
                </h1>
                
                <form action="{{ route('frontend.perahu') }}" method="GET" class="space-y-4">
                    <div class="relative border border-gray-300 rounded-xl p-3 focus-within:ring-2 focus-within:ring-black">
                        <label class="block text-[10px] font-bold uppercase text-airbnb-dark">{{ __('Location') }}</label>
                        <input type="text" name="location" readonly value="{{ optional($hotel)->title }}" 
                               class="w-full border-none p-0 text-[14px] text-airbnb-dark focus:ring-0 font-medium bg-transparent">
                    </div>
                    
                    <div class="grid grid-cols-3 gap-0 border border-gray-300 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-black">
                        <div class="p-3 border-r border-gray-300">
                             <label class="block text-[10px] font-bold uppercase text-airbnb-dark">{{ __('Check In') }}</label>
                             <input type="text" name="checkIn" id="checkin_picker" placeholder="{{ __('Tambah tanggal') }}"
                                    class="w-full border-none p-0 text-[14px] text-airbnb-dark focus:ring-0 placeholder:text-gray-400 bg-transparent">
                        </div>
                        <div class="p-3 border-r border-gray-300">
                             <label class="block text-[10px] font-bold uppercase text-airbnb-dark">{{ __('Check Out') }}</label>
                             <input type="text" name="checkOut" id="checkout_picker" placeholder="{{ __('Tambah tanggal') }}"
                                    class="w-full border-none p-0 text-[14px] text-airbnb-dark focus:ring-0 placeholder:text-gray-400 bg-transparent">
                        </div>
                        <div class="p-3">
                             <label class="block text-[10px] font-bold uppercase text-airbnb-dark">{{ __('Guests') }}</label>
                             <input type="number" name="adults" min="1" value="1"
                                    class="w-full border-none p-0 text-[14px] text-airbnb-dark focus:ring-0 placeholder:text-gray-400 bg-transparent">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-airbnb-red text-white py-3.5 rounded-xl font-bold text-[16px] hover:bg-rose-600 transition flex items-center justify-center space-x-2 airbnb-gradient-btn mt-6">
                        <i data-lucide="search" class="w-5 h-5"></i>
                        <span>{{ __('Search') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    {{-- SIMPLE AI SMART SEARCH --}}
    <div class="max-w-[1440px] mx-auto px-6 lg:px-20 mb-16">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <i data-lucide="sparkles" class="w-6 h-6 text-rose-500 mr-2"></i>
                {{ __('Tanya Asisten AI GoFishi') }}
            </h2>
            
            <form action="{{ route('frontend.perahu.ai_search') }}" method="GET" class="relative">
                <div class="flex items-center bg-gray-50 border border-gray-200 rounded-2xl p-2 focus-within:bg-white focus-within:shadow-md transition-all focus-within:border-rose-300">
                    <div class="px-4 text-gray-400">
                        <i data-lucide="search" class="w-5 h-5"></i>
                    </div>
                    <input type="text" name="q" 
                           placeholder="{{ __('Ke mana rencana berlayar Anda? Cari armada terbaik dengan AI...') }}" 
                           class="w-full bg-transparent border-none p-4 text-[16px] focus:ring-0 placeholder:text-gray-400 text-gray-900">
                    <button type="submit" class="bg-rose-500 text-white px-8 py-3.5 rounded-xl font-bold hover:bg-rose-600 transition shadow-sm">
                        {{ __('Cari') }}
                    </button>
                </div>
                
                {{-- Quick Examples --}}
                <div class="flex flex-wrap items-center gap-3 mt-6">
                    <span class="text-sm text-gray-400 mr-2 flex items-center">
                        <i data-lucide="info" class="w-3.5 h-3.5 mr-1.5 opacity-50"></i>
                        {{ __('Coba tanya:') }}
                    </span>
                    <a href="{{ route('frontend.perahu.ai_search', ['q' => 'Kapal di Ancol']) }}" class="text-xs text-gray-500 font-medium px-4 py-2 border border-gray-200 rounded-full hover:border-rose-400 hover:text-rose-500 transition-all">{{ __('Kapal di Ancol') }}</a>
                    <a href="{{ route('frontend.perahu.ai_search', ['q' => 'Budget di bawah 3 juta']) }}" class="text-xs text-gray-500 font-medium px-4 py-2 border border-gray-200 rounded-full hover:border-rose-400 hover:text-rose-500 transition-all">{{ __('Budget di bawah 3 juta') }}</a>
                    <a href="{{ route('frontend.perahu.ai_search', ['q' => 'Kapasitas 10 orang']) }}" class="text-xs text-gray-500 font-medium px-4 py-2 border border-gray-200 rounded-full hover:border-rose-400 hover:text-rose-500 transition-all">{{ __('Kapasitas 10 orang') }}</a>
                </div>
            </form>
        </div>
    </div>


    {{-- GRID PUSAT KEBERANGKATAN (LOKASI/DERMAGA) --}}
    @if(isset($all_hotels) && count($all_hotels) > 0)
    <div class="max-w-[1440px] mx-auto px-6 lg:px-20 py-12">
        <div class="mb-8">
            <h2 class="text-[26px] font-semibold text-airbnb-dark tracking-tight">{{ __('Pilih Pusat Keberangkatan') }}</h2>
            <p class="text-[15px] text-airbnb-gray mt-1">{{ __('Silahkan klik dan telusuri dermaga di bawah untuk mendapatkan pengalaman Split Map View') }}</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($all_hotels as $lok)
                @include('frontend.lokasi._location_card', ['hotel' => $lok, 'hotelContent' => $lok])
            @endforeach
        </div>
    </div>

    <div class="max-w-[1440px] mx-auto px-6 lg:px-20"><hr class="border-gray-200"></div>
    @endif


    {{-- 2. CARD CAROUSEL (Daftar Semua Perahu Existing) --}}
    <div class="max-w-[1440px] mx-auto px-6 lg:px-20 py-12">
        <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-8" x-data="{ currentSlide: 0, maxSlide: {{ max(0, ceil(count($rooms)/4) - 1) }} }">
            <div class="mb-4 md:mb-0">
                <h2 class="text-[26px] font-semibold text-airbnb-dark tracking-tight">{{ __('Daftar Perahu di') }} {{ optional($hotel)->title }}</h2>
                <p class="text-[15px] text-airbnb-gray mt-1">{{ __('Pilih dan sewa kapal terbaik untuk perjalanan Anda dari dermaga ini.') }}</p>
            </div>
            
            {{-- Carousel Navigation Dots --}}
            <div class="flex items-center space-x-3 w-max" x-show="maxSlide > 0">
                 <button type="button" 
                         x-bind:disabled="currentSlide === 0"
                         x-bind:class="currentSlide === 0 ? 'opacity-30 cursor-not-allowed' : 'hover:bg-gray-100'"
                         @click="if(currentSlide > 0) { currentSlide--; $refs.slider2.scrollBy({left: -($refs.slider2.clientWidth), behavior: 'smooth'}) }" 
                         class="p-2 border border-gray-300 rounded-full transition">
                         <i data-lucide="chevron-left" class="w-4 h-4 text-airbnb-dark"></i>
                 </button>
                 <span class="text-[14px] font-medium text-airbnb-gray" x-text="(currentSlide + 1) + ' / ' + (maxSlide + 1)"></span>
                 <button type="button"
                         x-bind:disabled="currentSlide === maxSlide"
                         x-bind:class="currentSlide === maxSlide ? 'opacity-30 cursor-not-allowed' : 'hover:bg-gray-100'"
                         @click="if(currentSlide < maxSlide) { currentSlide++; $refs.slider2.scrollBy({left: $refs.slider2.clientWidth, behavior: 'smooth'}) }" 
                         class="p-2 border border-gray-300 rounded-full transition">
                         <i data-lucide="chevron-right" class="w-4 h-4 text-airbnb-dark"></i>
                 </button>
            </div>
        </div>
            
        {{-- ARMADA CAROUSEL --}}
        <div id="armada-slider" x-ref="slider2" class="flex overflow-x-auto snap-x snap-mandatory gap-6 pb-4 [&::-webkit-scrollbar]:hidden" style="-ms-overflow-style: none; scrollbar-width: none;">
            @forelse($rooms as $room)
            <div class="snap-start shrink-0 w-[85%] sm:w-[calc(50%-12px)] lg:w-[calc(25%-18px)]">
                 @include('frontend.perahu._card', ['room' => $room])
            </div>
            @empty
                <div class="w-full text-center py-12 text-airbnb-gray">
                    <i data-lucide="anchor" class="w-12 h-12 mx-auto mb-4 opacity-30"></i>
                    <p class="text-lg">{{ __('Tidak ada kapal yang tersedia di dermaga ini saat ini.') }}</p>
                </div>
            @endforelse
        </div>
    </div>


    <div class="max-w-[1440px] mx-auto px-6 lg:px-20"><hr class="border-gray-200"></div>


    {{-- 3. ICON POPULAR AMENITIES --}}
    <div class="max-w-[1440px] mx-auto px-6 lg:px-20 py-16">
         <h3 class="text-2xl font-bold text-airbnb-dark mb-10">{{ __('Fasilitas populer di sekitar') }} {{ optional($hotel)->title }}</h3>
         
         <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-6">
            @foreach($all_amenities->take(12) as $amenity)
            <div class="flex flex-col items-center justify-center text-center space-y-3 p-4 hover:bg-gray-50 rounded-2xl transition cursor-default group">
                <div class="p-4 bg-gray-100 rounded-full group-hover:bg-white group-hover:shadow-md transition">
                    @if($amenity->icon)
                       <i class="{{ $amenity->icon }} text-2xl text-airbnb-dark"></i>
                    @else
                       <i data-lucide="check" class="w-6 h-6 text-airbnb-dark"></i>
                    @endif
                </div>
                <span class="text-sm font-medium text-airbnb-dark">{{ $amenity->title }}</span>
            </div>
            @endforeach
         </div>
    </div>


    <div class="max-w-[1440px] mx-auto px-6 lg:px-20"><hr class="border-gray-200"></div>


    {{-- MAP SECTION --}}
    <div class="max-w-[1440px] mx-auto px-6 lg:px-20 pb-24 pt-16">
         <h3 class="text-2xl font-bold text-airbnb-dark mb-8">{{ __('Lokasi Dermaga') }}</h3>
         <div class="relative w-full h-[480px] rounded-3xl overflow-hidden shadow-inner bg-gray-100">
            <div id="map" class="w-full h-full"></div>
         </div>
         <div class="mt-6 flex flex-col sm:flex-row items-start sm:items-center justify-between">
            <div class="mb-4 sm:mb-0">
               <h4 class="font-bold text-lg text-airbnb-dark">{{ optional($hotel)->title }}</h4>
               <p class="text-airbnb-gray mt-1">{{ @$hotel->address }}</p>
            </div>
            <a href="https://maps.google.com/?q={{ $hotel->latitude }},{{ $hotel->longitude }}" target="_blank" class="bg-airbnb-dark text-white px-6 py-3 rounded-xl font-bold hover:bg-black transition whitespace-nowrap">
                {{ __('Dapatkan Petunjuk Arah') }}
            </a>
         </div>
    </div>

</div>

<style>
    .airbnb-gradient-btn {
        background: linear-gradient(to right, #E61E4D 0%, #E31C5F 50%, #D70466 100%) !important;
        background-size: 200% auto !important;
        transition: 0.5s !important;
    }
    .airbnb-gradient-btn:hover { background-position: right center !important; }
    [x-cloak] { display: none !important; }
</style>
@endsection

@section('script')
<script>
    "use strict";

    // Initialize Flatpickr for Airbnb style date picking
    flatpickr("#checkin_picker", {
        dateFormat: "Y-m-d",
        minDate: "today",
        onChange: function(selectedDates, dateStr, instance) {
            document.getElementById('checkout_picker')._flatpickr.set('minDate', dateStr);
        }
    });

    flatpickr("#checkout_picker", {
        dateFormat: "Y-m-d",
        minDate: "today"
    });

    flatpickr("#checkout_picker", {
        dateFormat: "Y-m-d",
        minDate: "today"
    });

    window.initMap = function() {
        if (!window.google) return;
        const pos = { lat: parseFloat("{{ $hotel->latitude }}"), lng: parseFloat("{{ $hotel->longitude }}") };
        const map = new google.maps.Map(document.getElementById('map'), {
            center: pos,
            zoom: 15,
            disableDefaultUI: true,
            zoomControl: true,
            styles: [
                { "featureType": "administrative", "elementType": "labels.text.fill", "stylers": [{"color": "#444444"}] },
                { "featureType": "landscape", "elementType": "all", "stylers": [{"color": "#f2f2f2"}] },
                { "featureType": "water", "elementType": "all", "stylers": [{"color": "#c8d7d4"}] }
            ]
        });
        
        new google.maps.Marker({
            position: pos,
            map: map,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                fillColor: '#FF385C',
                fillOpacity: 1,
                strokeColor: 'white',
                strokeWeight: 3,
                scale: 12
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        if(window.lucide) lucide.createIcons();
        if(window.google) initMap();
    });
</script>

@if(!empty(config('google.maps_api_key')))
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('google.maps_api_key') }}&libraries=places&callback=initMap" async defer></script>
@endif
@endsection
