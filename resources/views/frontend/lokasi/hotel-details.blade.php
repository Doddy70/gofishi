@extends('frontend.layout-airbnb')

@php
    $langId = $currentLanguageInfo ? $currentLanguageInfo->id : get_lang()->id;
    
    // Safety check for $hotel
    if (!isset($hotel) || !$hotel) {
        return; 
    }

    $gallery = [];
    if (!empty($hotel->logo)) {
        $gallery[] = asset('assets/img/hotel/logo/' . $hotel->logo);
    }
    
    if (isset($hotelImages) && count($hotelImages) > 0) {
        foreach($hotelImages as $img) {
            $gallery[] = asset('assets/img/hotel/hotel-gallery/' . $img->image); 
        }
    }
    
    // Fallback images
    if (empty($gallery)) {
        $gallery[] = 'https://images.unsplash.com/photo-1559136555-9303baea8ebd?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80';
    }
    while(count($gallery) < 5) {
        $seeds = ['ocean', 'boat', 'pier', 'marina', 'fishing'];
        $gallery[] = 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';
    }

    $rating = round($hotel->average_rating ?? 4.8, 1);
    $reviewsCount = $numOfReview ?? 0;
    $vendorName = $vendor ? ($vendor->username ?? 'Go Fishi Host') : 'Admin';
    $vendorPhoto = $vendor ? ($vendor->photo ? asset('assets/admin/img/vendor-photo/' . $vendor->photo) : asset('assets/front/images/user.png')) : asset('assets/front/images/user.png');
@endphp

@section('pageHeading')
    {{ optional($hotel)->title }}
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 py-10" x-data="{ openPhotos: false }">
    
    {{-- Breadcrumb & Title --}}
    <div class="mb-4 text-sm text-gray-500 flex items-center space-x-2">
        <a href="{{ route('index') }}" class="hover:underline">Home</a>
        <span>/</span>
        <a href="{{ route('frontend.lokasi') }}" class="hover:underline">{{ __('Dermaga') }}</a>
        <span>/</span>
        <span class="text-gray-900 font-medium">{{ optional($hotel)->title }}</span>
    </div>

    {{-- Main Title Section --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-[32px] font-bold text-gray-900 leading-tight">
                {{ optional($hotel)->title }}
            </h1>
            <div class="flex flex-wrap items-center mt-2 space-x-2 text-[15px]">
                <div class="flex items-center">
                    <i data-lucide="star" class="w-4 h-4 text-black fill-current mr-1"></i>
                    <span class="font-bold">{{ $rating }}</span>
                </div>
                <span>·</span>
                <span class="underline font-semibold cursor-pointer">{{ $reviewsCount }} {{ __('ulasan') }}</span>
                <span>·</span>
                <span class="underline font-semibold cursor-pointer">{{ optional($hotel)->address }}</span>
            </div>
        </div>
        <div class="flex items-center space-x-4 pb-2">
            <button class="flex items-center space-x-2 text-sm font-semibold underline hover:bg-gray-100 px-3 py-2 rounded-lg transition">
                <i data-lucide="share" class="w-4 h-4"></i>
                <span>{{ __('Bagikan') }}</span>
            </button>
            <button class="flex items-center space-x-2 text-sm font-semibold underline hover:bg-gray-100 px-3 py-2 rounded-lg transition">
                <i data-lucide="heart" class="w-4 h-4"></i>
                <span>{{ __('Simpan') }}</span>
            </button>
        </div>
    </div>

    {{-- Hero Image Gallery (Airbnb 5-Grid) --}}
    <div class="relative grid grid-cols-4 gap-2 rounded-2xl overflow-hidden h-[480px] mb-12 group shadow-sm bg-gray-100">
        {{-- Main Large Photo --}}
        <div class="col-span-2 row-span-2 relative cursor-pointer overflow-hidden" @click="openPhotos = true">
            <img src="{{ $gallery[0] }}" class="w-full h-full object-cover group-hover:brightness-95 hover:brightness-90 transition duration-500 scale-100 hover:scale-105">
        </div>
        {{-- 4 Grid Photos --}}
        <div class="relative cursor-pointer overflow-hidden border-l border-white" @click="openPhotos = true">
            <img src="{{ $gallery[1] }}" class="w-full h-full object-cover group-hover:brightness-95 hover:brightness-90 transition duration-500 scale-100 hover:scale-105">
        </div>
        <div class="relative cursor-pointer overflow-hidden border-l border-white" @click="openPhotos = true">
            <img src="{{ $gallery[2] }}" class="w-full h-full object-cover group-hover:brightness-95 hover:brightness-90 transition duration-500 scale-100 hover:scale-105 rounded-tr-2xl">
        </div>
        <div class="relative cursor-pointer overflow-hidden border-l border-white border-t border-white" @click="openPhotos = true">
            <img src="{{ $gallery[3] }}" class="w-full h-full object-cover group-hover:brightness-95 hover:brightness-90 transition duration-500 scale-100 hover:scale-105">
        </div>
        <div class="relative cursor-pointer overflow-hidden border-l border-white border-t border-white" @click="openPhotos = true">
            <img src="{{ $gallery[4] }}" class="w-full h-full object-cover group-hover:brightness-95 hover:brightness-90 transition duration-500 scale-100 hover:scale-105 rounded-br-2xl">
        </div>

        <button @click="openPhotos = true" class="absolute bottom-6 right-6 bg-white border border-gray-900 px-5 py-2 rounded-xl text-sm font-bold flex items-center space-x-2 shadow-2xl hover:bg-gray-50 transition active:scale-95">
            <i data-lucide="layout-grid" class="w-4 h-4 text-gray-900"></i>
            <span>{{ __('Lihat semua foto') }}</span>
        </button>
    </div>

    {{-- Main Layout: 2 Columns --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
        
        {{-- Left: Details --}}
        <div class="lg:col-span-2 space-y-12">
            
            {{-- Header Info --}}
            <div class="flex justify-between items-start pb-8 border-b border-gray-200">
                <div>
                    <h2 class="text-[24px] font-bold text-gray-900 leading-tight">
                        {{ $hotel->categoryName }} · {{ __('Dikelola oleh') }} {{ $vendorName }}
                    </h2>
                    <ul class="flex flex-wrap items-center mt-2 space-x-4 text-[16px] text-gray-600">
                        <li class="flex items-center space-x-1">
                            <i data-lucide="users" class="w-4 h-4"></i>
                            <span>{{ count($rooms) }} {{ __('Armada Kapal') }}</span>
                        </li>
                        <li class="flex items-center space-x-1">
                            <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                            <span>{{ __('Diverifikasi') }}</span>
                        </li>
                    </ul>
                </div>
                <div class="shrink-0 relative">
                    <img src="{{ $vendorPhoto }}" class="w-16 h-16 rounded-full object-cover border-2 border-white ring-1 ring-gray-100 shadow-md">
                    <div class="absolute -bottom-1 -right-1 bg-rose-500 text-white p-1 rounded-full border-2 border-white shadow-sm">
                        <i data-lucide="award" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </div>

            {{-- Feature Highlights --}}
            <div class="grid grid-cols-1 gap-8 pb-12 border-b border-gray-200">
                <div class="flex items-start space-x-5">
                    <div class="mt-1 flex-shrink-0">
                        <i data-lucide="anchor" class="w-8 h-8 text-rose-500"></i>
                    </div>
                    <div>
                        <h4 class="text-[17px] font-bold text-gray-900 leading-tight">{{ __('Titik Keberangkatan Utama') }}</h4>
                        <p class="text-[14px] text-gray-500 mt-1 leading-snug">
                            {{ __('Salah satu lokasi paling dicari untuk memancing di area ini.') }}
                        </p>
                    </div>
                </div>
                <div class="flex items-start space-x-5">
                    <div class="mt-1 flex-shrink-0">
                        <i data-lucide="shield-check" class="w-8 h-8 text-rose-500"></i>
                    </div>
                    <div>
                        <h4 class="text-[17px] font-bold text-gray-900 leading-tight">{{ __('Go Fishi Verified') }}</h4>
                        <p class="text-[14px] text-gray-500 mt-1 leading-snug">
                            {{ __('Setiap keberangkatan dari sini dipantau oleh sistem keamanan kami.') }}
                        </p>
                    </div>
                </div>
                <div class="flex items-start space-x-5">
                    <div class="mt-1 flex-shrink-0">
                        <i data-lucide="message-square" class="w-8 h-8 text-rose-500"></i>
                    </div>
                    <div>
                        <h4 class="text-[17px] font-bold text-gray-900 leading-tight">{{ __('Respon Cepat Host') }}</h4>
                        <p class="text-[14px] text-gray-500 mt-1 leading-snug">
                            {{ __('Pengelola dermaga ini biasanya merespon pesan dalam hitungan menit.') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Pier Description --}}
            <div class="pb-12 border-b border-gray-200">
                <div class="flex items-center space-x-3 mb-6">
                    <i data-lucide="info" class="w-6 h-6 text-gray-900"></i>
                    <h3 class="text-[22px] font-bold text-gray-900">{{ __('Tentang Dermaga') }}</h3>
                </div>
                <div class="text-[16px] leading-relaxed text-gray-700 space-y-4">
                    {!! $hotel->description !!}
                </div>
                <button class="mt-6 text-[16px] font-bold underline flex items-center space-x-1 hover:text-rose-600 transition">
                    <span>{{ __('Baca selengkapnya') }}</span>
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </button>
            </div>

            {{-- AMENITIES --}}
            <div class="pb-12 border-b border-gray-200">
                 <h3 class="text-[22px] font-bold text-gray-900 mb-8">{{ __('Fasilitas di Lokasi Ini') }}</h3>
                 @php 
                    $amenities = json_decode($hotel->amenities, true) ?? [];
                    // For demo if empty
                    if(empty($amenities)) $amenities = ['Ruang Tunggu', 'Toilet', 'Musholla', 'Parkir Luas', 'Warung Makan', 'Penyewaan Alat'];
                 @endphp
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-12">
                     @foreach(array_slice($amenities, 0, 8) as $item)
                     <div class="flex items-center space-x-4">
                         <i data-lucide="check" class="w-5 h-5 text-gray-900"></i>
                         <span class="text-[16px] text-gray-700 font-light">{{ $item }}</span>
                     </div>
                     @endforeach
                 </div>
                 @if(count($amenities) > 8)
                 <button class="mt-10 bg-white border border-gray-900 px-6 py-3 rounded-xl font-bold text-[16px] hover:bg-gray-50 transition active:scale-95">
                     {{ __('Tampilkan semua fasilitas') }}
                 </button>
                 @endif
            </div>

            {{-- LISTING ARMADA (ROOMS) --}}
            <div id="section-boats" class="pb-12 border-b border-gray-200">
                <div class="flex justify-between items-center mb-10">
                    <h3 class="text-[26px] font-bold text-gray-900 tracking-tight">{{ __('Daftar Armada di Dermaga Ini') }}</h3>
                    <div class="bg-gray-100 px-3 py-1 rounded-full text-xs font-bold text-gray-500 uppercase tracking-widest">
                        {{ count($rooms) }} {{ __('KM Ditemukan') }}
                    </div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 lg:gap-10">
                    @foreach($rooms as $room)
                    <div class="group cursor-pointer flex flex-col"
                         onclick="window.location.href='{{ route('frontend.perahu.details', ['slug' => $room->slug, 'id' => $room->id]) }}'">
                        {{-- Card Image --}}
                        <div class="relative aspect-square rounded-2xl overflow-hidden mb-4 bg-gray-100">
                            <img src="{{ $room->feature_image ? asset('assets/img/perahu/featureImage/' . $room->feature_image) : asset('assets/front/images/noimage.jpg') }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-700" alt="{{ $room->title }}">
                            
                            {{-- Wishlist Heart --}}
                            <button class="absolute top-4 right-4 p-2 text-white/90 hover:text-rose-500 transition">
                                <i data-lucide="heart" class="w-6 h-6 stroke-[2px]"></i>
                            </button>

                            {{-- Badge --}}
                            <div class="absolute bottom-4 left-4 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-xl shadow-lg">
                                <span class="text-[11px] font-bold text-gray-900 uppercase tracking-widest">{{ $room->room_category_name ?? __('Mancing & Wisata') }}</span>
                            </div>
                        </div>
                        
                        {{-- Card Body --}}
                        <div class="space-y-1">
                            <div class="flex justify-between items-center">
                                <h4 class="text-[17px] font-bold text-gray-900 truncate pr-2 group-hover:text-rose-600 transition-colors">KM {{ $room->nama_km ?? $room->title }}</h4>
                                <div class="flex items-center space-x-1 shrink-0">
                                    <i data-lucide="star" class="w-3.5 h-3.5 text-black fill-current"></i>
                                    <span class="text-sm font-bold text-gray-900">{{ number_format($room->average_rating, 1) }}</span>
                                </div>
                            </div>
                            <p class="text-[15px] text-gray-500 font-light truncate">
                                {{ $room->adult }} {{ __('Tamu') }} · Kapten {{ $room->captain_name ?? 'Senior' }}
                            </p>
                            <p class="text-[15px] text-gray-500 font-light italic truncate">
                                {{ __('Berangkat jam') }} {{ $room->meet_time_day_1 ?? '05:00' }}
                            </p>
                            <div class="pt-2 flex items-baseline space-x-1">
                                <span class="text-[17px] font-bold text-gray-900">{{ symbolPrice($room->price_day_1 ?? 0) }}</span>
                                <span class="text-[14px] text-gray-500 font-light">/ {{ __('Trip') }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                @if(count($rooms) > 4)
                <button class="mt-12 w-full py-4 border-2 border-gray-900 rounded-2xl font-bold text-[18px] hover:bg-gray-950 hover:text-white transition duration-300">
                    {{ __('Tampilkan semua armada') }}
                </button>
                @endif
            </div>

            {{-- MAP SECTION --}}
            <div class="pb-12">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-[22px] font-bold text-gray-900 mb-1">{{ __('Lokasi Dermaga') }}</h3>
                        <p class="text-[15px] text-gray-600 font-light">{{ $hotel->address }}</p>
                    </div>
                    <button class="bg-gray-100 p-3 rounded-full hover:bg-gray-200 transition">
                         <i data-lucide="navigation" class="w-6 h-6 text-gray-900"></i>
                    </button>
                </div>
                <div class="rounded-3xl overflow-hidden border border-gray-200 shadow-xl">
                    <div id="map" class="h-[520px] w-full bg-gray-50"></div>
                </div>
            </div>

        </div>

        {{-- Right: Sticky Info --}}
        <div>
            <div class="sticky top-28 space-y-8">
                
                {{-- Info Card --}}
                <div class="border border-gray-200 rounded-3xl p-8 shadow-2xl space-y-8 bg-white ring-1 ring-gray-900/5 transition hover:shadow-rose-100/50">
                    <div class="flex justify-between items-baseline">
                        <div>
                            <span class="text-[26px] font-bold text-gray-900 leading-none">{{ __('Info Lokasi') }}</span>
                        </div>
                        <div class="flex items-center space-x-1.5 p-1 px-2 border border-gray-100 rounded-lg bg-gray-50">
                            <i data-lucide="star" class="w-3.5 h-3.5 text-black fill-current"></i>
                            <span class="text-[14px] font-bold text-gray-900">{{ $rating }}</span>
                            <span class="text-xs text-gray-400">·</span>
                            <span class="text-xs text-gray-500 underline font-medium">{{ $reviewsCount }} {{ __('ulasan') }}</span>
                        </div>
                    </div>

                    {{-- Stats Grid --}}
                    <div class="grid grid-cols-2 gap-px bg-gray-200 rounded-2xl overflow-hidden border border-gray-200 shadow-inner">
                        <div class="col-span-2 p-4 bg-white hover:bg-gray-50 transition cursor-default">
                             <label class="text-[10px] uppercase font-bold text-rose-500 tracking-widest mb-1 block">{{ __('Nama Dermaga') }}</label>
                             <p class="text-[15px] font-bold text-gray-900 leading-snug">{{ $hotel->title }}</p>
                        </div>
                        <div class="p-4 bg-white border-r border-t border-gray-200 hover:bg-gray-50 transition">
                             <label class="text-[10px] uppercase font-bold text-gray-400 tracking-widest mb-1 block">{{ __('Kategori') }}</label>
                             <p class="text-[15px] font-bold text-gray-900">{{ $hotel->categoryName }}</p>
                        </div>
                        <div class="p-4 bg-white border-t border-gray-200 hover:bg-gray-50 transition">
                             <label class="text-[10px] uppercase font-bold text-gray-400 tracking-widest mb-1 block">{{ __('Armada Aktif') }}</label>
                             <p class="text-[15px] font-extrabold text-rose-600">{{ count($rooms) }} {{ __('KM') }}</p>
                        </div>
                    </div>

                    <button @click="document.getElementById('section-boats').scrollIntoView({ behavior: 'smooth', block: 'start' })" 
                            class="w-full py-4 bg-rose-500 hover:bg-rose-600 text-white rounded-2xl font-bold text-[18px] transition transform active:scale-95 shadow-xl shadow-rose-200/50 airbnb-gradient-btn">
                        {{ __('Lihat Daftar Kapal') }}
                    </button>

                    <p class="text-center text-[13px] text-gray-500 font-medium px-4">
                        {{ __('Semua armada di dermaga ini telah melewati inspeksi kelaikan laut oleh Go Fishi.') }}
                    </p>

                    {{-- Local Metrics --}}
                    <div class="space-y-4 pt-4 border-t border-gray-100">
                        @foreach ($hotelCounters as $counter)
                        <div class="flex justify-between items-center text-[15px]">
                            <span class="text-gray-900 font-light border-b border-dotted border-gray-400">{{ $counter->label }}</span>
                            <span class="font-bold text-gray-900">{{ $counter->value }}</span>
                        </div>
                        @endforeach
                        
                        <div class="flex items-start space-x-4 pt-6 text-[13px] text-gray-600 leading-relaxed">
                            <i data-lucide="shield-check" class="w-10 h-10 text-rose-500 shrink-0"></i>
                            <div>
                                <b class="text-gray-900">{{ __('Proteksi Go Fishi') }}</b>. 
                                {{ __('Kami menjamin keamanan transaksi dan ketersediaan kapal untuk setiap booking yang Anda lakukan.') }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contact Card --}}
                <div class="group border border-gray-200 rounded-3xl p-6 flex items-center justify-between bg-white hover:bg-gray-950 hover:border-gray-950 transition-all duration-300 cursor-pointer shadow-sm hover:shadow-2xl">
                    <div class="flex items-center space-x-5">
                       <div class="p-4 bg-gray-100 group-hover:bg-gray-800 rounded-2xl transition">
                           <i data-lucide="message-circle" class="w-8 h-8 text-rose-500"></i>
                       </div>
                       <div>
                           <h4 class="font-bold text-gray-900 group-hover:text-white transition">{{ __('Chat Pengelola?') }}</h4>
                           <p class="text-sm text-gray-500 group-hover:text-gray-400 transition">{{ __('Tanya jadwal & ketersediaan') }}</p>
                       </div>
                    </div>
                    <i data-lucide="arrow-right" class="w-6 h-6 text-gray-300 group-hover:text-rose-500 group-hover:translate-x-1 transition"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Fullscreen Gallery Modal --}}
<div x-show="openPhotos" 
     x-cloak
     class="fixed inset-0 z-[99999] bg-white overflow-y-auto"
     x-transition:enter="transition transform duration-500 ease-out"
     x-transition:enter-start="translate-y-full"
     x-transition:enter-end="translate-y-0"
     x-transition:leave="transition transform duration-300 ease-in"
     x-transition:leave-start="translate-y-0"
     x-transition:leave-end="translate-y-full">
    
    <div class="min-h-screen bg-white">
        {{-- Custom Sticky Nav for Modal --}}
        <div class="sticky top-0 bg-white/80 backdrop-blur-md z-10 px-8 py-6 flex justify-between items-center border-b border-gray-100">
            <button @click="openPhotos = false" class="p-3 hover:bg-gray-100 rounded-full transition active:scale-90 ring-1 ring-gray-900/5 shadow-sm">
                <i data-lucide="chevron-left" class="w-6 h-6"></i>
            </button>
            <div class="flex space-x-4">
                <button class="flex items-center space-x-2 px-4 py-2 hover:bg-gray-100 rounded-xl transition font-semibold text-sm">
                    <i data-lucide="share" class="w-4 h-4"></i>
                    <span>Share</span>
                </button>
                <button class="flex items-center space-x-2 px-4 py-2 hover:bg-gray-100 rounded-xl transition font-semibold text-sm">
                    <i data-lucide="heart" class="w-4 h-4"></i>
                    <span>Save</span>
                </button>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-6 py-16 space-y-8">
            <h2 class="text-3xl font-bold text-gray-900 pb-8">{{ __('Galeri Foto') }} · {{ $hotel->title }}</h2>
            @foreach($gallery as $img)
            <div class="rounded-3xl overflow-hidden shadow-2xl group border border-gray-100">
                <img src="{{ $img }}" class="w-full h-auto object-cover transition duration-500 group-hover:scale-105">
            </div>
            @endforeach
        </div>

        {{-- Footer in Modal --}}
        <div class="py-20 text-center bg-gray-50">
             <i data-lucide="navigation" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
             <p class="text-gray-400 italic">End of gallery</p>
             <button @click="openPhotos = false" class="mt-8 bg-gray-900 text-white px-10 py-4 rounded-2xl font-bold hover:bg-black transition">
                 Back to Details
             </button>
        </div>
    </div>
</div>

<style>
    .airbnb-gradient-btn {
        background: linear-gradient(to right, #E61E4D 0%, #E31C5F 50%, #D70466 100%) !important;
        background-size: 200% auto !important;
        transition: 0.5s !important;
    }
    .airbnb-gradient-btn:hover {
        background-position: right center !important;
    }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    [x-cloak] { display: none !important; }
</style>
@endsection

@section('script')
  <script>
    "use strict";
    var latitude = "{{ $hotel->latitude }}";
    var longitude = "{{ $hotel->longitude }}";

    function initDetailsMap() {
        if (!window.google) return;
        const pos = { lat: parseFloat(latitude), lng: parseFloat(longitude) };
        const mapElement = document.getElementById('map');
        
        const map = new google.maps.Map(mapElement, {
            center: pos,
            zoom: 15,
            disableDefaultUI: true,
            zoomControl: true,
            styles: [
                { "featureType": "administrative", "elementType": "labels.text.fill", "stylers": [{"color": "#444444"}] },
                { "featureType": "landscape", "elementType": "all", "stylers": [{"color": "#f2f2f2"}] },
                { "featureType": "road", "elementType": "all", "stylers": [{"saturation": -100}, {"lightness": 45}] },
                { "featureType": "water", "elementType": "all", "stylers": [{"color": "#c8d7d4"}, {"visibility": "on"}] }
            ]
        });

        // Advanced Marker Element (Modern Google Maps)
        const marker = new google.maps.Marker({
            position: pos,
            map: map,
            animation: google.maps.Animation.DROP,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                fillColor: '#FF385C',
                fillOpacity: 1,
                strokeColor: 'white',
                strokeWeight: 4,
                scale: 18
            },
            title: "{{ $hotel->title }}"
        });

        const infoWindow = new google.maps.InfoWindow({
            content: `<div class='p-3 min-w-[150px]'>
                        <h5 class='font-bold text-gray-900 mb-1'>{{ $hotel->title }}</h5>
                        <p class='text-xs text-gray-500'>{{ $hotel->address }}</p>
                      </div>`
        });

        marker.addListener('click', () => {
            infoWindow.open(map, marker);
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        if(window.google) {
            initDetailsMap();
        }
    });

    // Handle Lucide Icons initialization
    if(window.lucide) {
        lucide.createIcons();
    }
  </script>
@endsection
