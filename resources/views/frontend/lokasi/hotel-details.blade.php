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
    
    {{-- HERO SECTION WITH FLOATING SEARCH CARD --}}
    <div class="relative max-w-[1440px] mx-auto px-6 lg:px-20 pt-8 pb-16">
        <div class="relative h-[480px] lg:h-[600px] w-full rounded-[30px] overflow-hidden">
            {{-- Main Hero Background --}}
            <img src="{{ $mainImage }}" class="w-full h-full object-cover">
            
            {{-- Floating Search Card (Left Overlay) --}}
            <div class="absolute top-1/2 -translate-y-1/2 left-8 lg:left-16 w-[90%] sm:w-[480px] bg-white rounded-[24px] shadow-[0_16px_32px_rgba(0,0,0,0.15)] p-8 z-20">
                <h1 class="text-[32px] font-bold text-airbnb-dark leading-[36px] mb-8">
                    Places to stay near {{ optional($hotel)->title }}
                </h1>
                
                <form action="{{ route('frontend.perahu') }}" method="GET" class="space-y-4">
                    {{-- Location Input (Read Only) --}}
                    <div class="relative border border-gray-300 rounded-xl p-3 focus-within:ring-2 focus-within:ring-black">
                        <label class="block text-[10px] font-bold uppercase text-airbnb-dark">{{ __('Location') }}</label>
                        <input type="text" name="location" readonly value="{{ optional($hotel)->title }}, {{ @$hotel->address }}" 
                               class="w-full border-none p-0 text-[14px] text-airbnb-dark focus:ring-0 font-medium">
                    </div>
                    
                    {{-- Checkin/Checkout/Guests Grid --}}
                    <div class="grid grid-cols-3 gap-0 border border-gray-300 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-black">
                        <div class="p-3 border-r border-gray-300">
                             <label class="block text-[10px] font-bold uppercase text-airbnb-dark">{{ __('Check in') }}</label>
                             <input type="text" name="checkIn" id="checkin_picker" value="{{ request('checkIn') }}" placeholder="Add dates"
                                    class="w-full border-none p-0 text-[14px] text-airbnb-dark focus:ring-0 placeholder:text-gray-400">
                        </div>
                        <div class="p-3 border-r border-gray-300">
                             <label class="block text-[10px] font-bold uppercase text-airbnb-dark">{{ __('Check out') }}</label>
                             <input type="text" name="checkOut" id="checkout_picker" value="{{ request('checkOut') }}" placeholder="Add dates"
                                    class="w-full border-none p-0 text-[14px] text-airbnb-dark focus:ring-0 placeholder:text-gray-400">
                        </div>
                        <div class="p-3">
                             <label class="block text-[10px] font-bold uppercase text-airbnb-dark">{{ __('Guests') }}</label>
                             <input type="number" name="adults" min="1" value="{{ request('adults', 1) }}"
                                    class="w-full border-none p-0 text-[14px] text-airbnb-dark focus:ring-0 placeholder:text-gray-400">
                        </div>
                    </div>

                    {{-- Search Button --}}
                    <button type="submit" class="w-full bg-airbnb-red text-white py-4 rounded-xl font-bold text-[18px] hover:bg-rose-600 transition flex items-center justify-center space-x-2 airbnb-gradient-btn mt-6">
                        <i data-lucide="search" class="w-5 h-5"></i>
                        <span>Search</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    </div>

    {{-- LOKASI GALLERY SECTION --}}
    @if(isset($hotelImages) && count($hotelImages) > 0)
    <div class="max-w-[1440px] mx-auto px-6 lg:px-20 mb-16">
        <h2 class="text-[32px] font-bold text-airbnb-dark mb-6">Gallery Dermaga</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($hotelImages->take(8) as $img)
            <div class="relative aspect-[4/3] rounded-[16px] overflow-hidden group bg-gray-100 shadow-sm">
                <img src="{{ asset('assets/img/hotel/hotel-gallery/' . $img->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- MAIN CONTENT SECTION --}}
    <div class="max-w-[1440px] mx-auto px-6 lg:px-20">
        
        {{-- Section Header & Alpine State Init --}}
        <div class="text-center mb-12" x-data="{ currentSlide: 0, maxSlide: {{ max(0, ceil(count($rooms)/4) - 1) }} }">
            <h2 class="text-[32px] font-bold text-airbnb-dark">Top-rated vacation rentals near {{ optional($hotel)->title }}</h2>
            <p class="text-[16px] text-airbnb-gray mt-2">Guests agree: these stays are highly rated for location, cleanliness, and more.</p>
            
            {{-- Carousel Navigation Dots --}}
            <div class="flex items-center justify-center space-x-4 mt-8" x-show="maxSlide > 0">
                 <button type="button" 
                         x-bind:disabled="currentSlide === 0"
                         x-bind:class="currentSlide === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'"
                         @click="if(currentSlide > 0) { currentSlide--; $refs.slider.scrollBy({left: -($refs.slider.clientWidth), behavior: 'smooth'}) }" 
                         class="p-2 border border-gray-300 rounded-full transition">
                         <i data-lucide="chevron-left" class="w-4 h-4"></i>
                 </button>
                 <span class="text-xs font-bold text-airbnb-dark" x-text="(currentSlide + 1) + ' / ' + (maxSlide + 1)"></span>
                 <button type="button"
                         x-bind:disabled="currentSlide === maxSlide"
                         x-bind:class="currentSlide === maxSlide ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'"
                         @click="if(currentSlide < maxSlide) { currentSlide++; $refs.slider.scrollBy({left: $refs.slider.clientWidth, behavior: 'smooth'}) }" 
                         class="p-2 border border-gray-300 rounded-full transition">
                         <i data-lucide="chevron-right" class="w-4 h-4"></i>
                 </button>
            </div>
            
            {{-- ARMADA CAROUSEL --}}
            <div x-ref="slider" class="flex overflow-x-auto snap-x snap-mandatory gap-6 mt-12 mb-20 pb-4 [&::-webkit-scrollbar]:hidden" style="-ms-overflow-style: none; scrollbar-width: none;">
                @forelse($rooms as $room)
                <div class="snap-start shrink-0 w-full sm:w-[calc(50%-12px)] lg:w-[calc(25%-18px)] group cursor-pointer text-left" onclick="window.location.href='{{ route('frontend.perahu.details', ['slug' => $room->slug, 'id' => $room->id]) }}'">
                    {{-- Image Card --}}
                    <div class="relative aspect-square rounded-[16px] overflow-hidden mb-3 bg-gray-100">
                        <img src="{{ $room->feature_image ? asset('assets/img/perahu/featureImage/' . $room->feature_image) : asset('assets/front/images/noimage.jpg') }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        
                        {{-- Guest Favorite Badge --}}
                        <div class="absolute top-3 left-3 bg-white/95 px-3 py-1 rounded-full shadow-sm">
                            <div class="flex items-center space-x-1">
                                 <i data-lucide="trophy" class="w-3 h-3 text-yellow-500"></i>
                                 <span class="text-[11px] font-bold text-airbnb-dark">Guest favorite</span>
                            </div>
                        </div>

                        {{-- Wishlist Heart --}}
                        <button class="absolute top-3 right-3 text-white/90 hover:text-rose-500 transition" onclick="event.stopPropagation(); alert('Added to wishlist!')">
                            <i data-lucide="heart" class="w-6 h-6 stroke-[2px]"></i>
                        </button>
                    </div>
                    
                    {{-- Meta Info --}}
                    <div class="space-y-0.5">
                        <div class="flex justify-between items-start">
                            <h3 class="font-bold text-[15px] text-airbnb-dark truncate pr-2">KM {{ $room->nama_km ?? $room->title }}</h3>
                            <div class="flex items-center space-x-1 shrink-0">
                                <i data-lucide="star" class="w-3 h-3 text-airbnb-dark fill-current"></i>
                                <span class="text-[14px] text-airbnb-dark">{{ number_format($room->average_rating, 1) }}</span>
                            </div>
                        </div>
                        <p class="text-[15px] text-airbnb-gray font-light">Near {{ optional($hotel)->title }}</p>
                        <p class="text-[15px] text-airbnb-gray font-light">Kapten {{ $room->captain_name ?? 'Senior' }}</p>
                        <div class="pt-1">
                            <span class="text-[15px] font-bold text-airbnb-dark">{{ symbolPrice($room->price_day_1 ?? 0) }}</span>
                            <span class="text-[15px] text-airbnb-dark font-light"> / trip</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="w-full text-center py-12 text-airbnb-gray">
                    <i data-lucide="anchor" class="w-12 h-12 mx-auto mb-4 opacity-30"></i>
                    <p class="text-lg">No boats available for the selected dates.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- QUICK STATS SECTION (Jay Peak Style) --}}
        <div class="py-16 border-t border-gray-200 grid grid-cols-1 md:grid-cols-3 gap-12 mb-20">
            <div class="space-y-4">
                <i data-lucide="anchor" class="w-8 h-8 text-airbnb-dark"></i>
                <h4 class="text-xl font-bold">{{ count($rooms) }} Active Boats</h4>
                <p class="text-airbnb-gray leading-relaxed text-[15px]">Wide selection of fishing and leisure boats available in {{ optional($hotel)->title }}.</p>
            </div>
            <div class="space-y-4">
                <i data-lucide="users" class="w-8 h-8 text-airbnb-dark"></i>
                <h4 class="text-xl font-bold">{{ $total_vendors ?? 0 }} Verified Vendors</h4>
                <p class="text-airbnb-gray leading-relaxed text-[15px]">Our community of local captains ensures the best sailing experience.</p>
            </div>
            <div class="space-y-4">
                <i data-lucide="shield-check" class="w-8 h-8 text-airbnb-dark"></i>
                <h4 class="text-xl font-bold">Safe & Easy Booking</h4>
                <p class="text-airbnb-gray leading-relaxed text-[15px]">Go Fishi protection covers every trip from this location.</p>
            </div>
        </div>

        {{-- AMENITIES SECTION --}}
        <div class="pb-16 decoration-2 border-b border-gray-200">
             <h3 class="text-2xl font-bold text-airbnb-dark mb-10">Popular amenities near {{ optional($hotel)->title }}</h3>
             <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
                @foreach($all_amenities->take(10) as $amenity)
                <div class="flex flex-col items-center text-center space-y-3 p-4 hover:bg-gray-50 rounded-2xl transition cursor-default group">
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

        {{-- FAQ SECTION --}}
        <div class="py-20 max-w-4xl">
             <h3 class="text-[32px] font-bold text-airbnb-dark mb-12">Frequently asked questions</h3>
             <div class="space-y-8" x-data="{ active: null }">
                @foreach($faqs as $index => $faq)
                <div class="border-b border-gray-200 pb-8">
                    <button @click="active = (active === {{ $index }} ? null : {{ $index }})" 
                            class="flex justify-between items-center w-full text-left focus:outline-none group">
                        <span class="text-[18px] font-medium text-airbnb-dark group-hover:underline">{{ $faq->question }}</span>
                        <i data-lucide="chevron-down" class="w-5 h-5 transition-transform duration-300" :class="active === {{ $index }} ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="active === {{ $index }}" x-collapse x-cloak class="mt-4 text-airbnb-gray leading-relaxed text-[16px]">
                        {{ $faq->answer }}
                    </div>
                </div>
                @endforeach
             </div>
        </div>

        {{-- MAP SECTION --}}
        <div class="pb-24 pt-10 border-t border-gray-200">
             <h3 class="text-2xl font-bold text-airbnb-dark mb-8">Where you'll be</h3>
             <div class="relative w-full h-[480px] rounded-[24px] overflow-hidden shadow-inner bg-gray-100">
                <div id="map" class="w-full h-full"></div>
             </div>
             <div class="mt-6 flex items-center justify-between">
                <div>
                   <h4 class="font-bold text-lg">{{ optional($hotel)->title }}</h4>
                   <p class="text-airbnb-gray">{{ @$hotel->address }}</p>
                </div>
                <button class="bg-airbnb-dark text-white px-6 py-3 rounded-xl font-bold hover:bg-black transition">Get Directions</button>
             </div>
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
