@extends('frontend.layout-airbnb')

@php
  $langId = $currentLanguageInfo ? $currentLanguageInfo->id : get_lang()->id;
  $roomContent = $room->room_content()->where('language_id', $langId)->first();
  $hotelContent = $room->hotel ? $room->hotel->hotel_contents()->where('language_id', $langId)->first() : null;
  
  $roomImages = $room->room_galleries;
  $allImages = [];
  if (!empty($room->feature_image)) {
    if (filter_var($room->feature_image, FILTER_VALIDATE_URL)) {
        $allImages[] = $room->feature_image;
    } else {
        $allImages[] = asset('assets/img/perahu/featureImage/' . $room->feature_image);
    }
  }
  foreach ($roomImages as $img) {
    if(!empty($img->image)) {
       $allImages[] = asset('assets/img/perahu/room-gallery/' . $img->image);
    }
  }
  if (empty($allImages)) {
    $allImages[] = 'https://via.placeholder.com/800x600?text=No+Image';
  }

  $rating = round($room->average_rating ?? 4.8, 1);
  
  // Real Packages from Backend
  $packages = $room->packages()->where('status', 1)->get();
  $defaultPackage = $packages->first();
  $price = $defaultPackage ? $defaultPackage->price : ($room->price_day_1 ?? $room->min_price ?? 0);
  
  $amenities = $amenities ?? collect([]);
  $reviews = $room_reviews['reviews'] ?? collect([]);
  
  // Boat Specific Features
  $highlights = [
    ['icon' => 'ship', 'title' => __('Professional Captain'), 'desc' => __('Your trip is led by a licensed expert.')],
    ['icon' => 'shield-check', 'title' => __('Safety Guaranteed'), 'desc' => __('International standard life jackets and gear.')],
    ['icon' => 'fish', 'title' => __('Fishing Expert'), 'desc' => __('Equipped with the best sonar and tackle.')]
  ];
  // Video YouTube Extraction
  $embed_url = '';
  if (!empty($room->video_url)) {
      if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $room->video_url, $match)) {
          $embed_url = "https://www.youtube.com/embed/" . $match[1];
      }
  }
@endphp

@section('pageHeading')
{{ $roomContent->title }}
@endsection

@section('content')
<style>
    /* Professional Calendar Theme - STABLE MODE */
    .flatpickr-calendar {
        background: #fff !important;
        box-shadow: 0 8px 28px rgba(0,0,0,0.2) !important;
        border-radius: 16px !important;
        border: none !important;
        padding: 24px !important;
        z-index: 40000 !important;
    }
    @media (min-width: 768px) {
        .flatpickr-calendar.rangeMode {
            width: 700px !important;
        }
    }
    .flatpickr-day {
        border-radius: 50% !important;
        height: 44px !important;
        width: 44px !important;
        line-height: 44px !important;
        margin: 2px !important;
        font-weight: 500;
        color: #222;
    }
    .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange {
        background: #222 !important;
        color: #fff !important;
        border: none !important;
    }
    .flatpickr-day.inRange {
        background: #f7f7f7 !important;
        box-shadow: none !important;
    }
</style>
<div id="product-details-app" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 py-6" x-cloak
     x-data="productDetails()"
     @scroll.window="showStickyNav = (window.pageYOffset > 540); updateActiveTab()">
    
    {{-- Airbnb Sticky Nav Bar --}}
    <div x-show="showStickyNav" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-full"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         class="fixed top-0 left-0 right-0 bg-white border-b border-gray-200 z-[80] hidden lg:block"
         x-cloak>
        <div class="max-w-7xl mx-auto px-12 h-20 flex justify-between items-center">
            <div class="flex space-x-6 h-full items-center">
                @foreach(['foto' => 'Foto', 'fasilitas' => 'Fasilitas', 'ulasan' => 'Ulasan', 'lokasi' => 'Lokasi'] as $id => $label)
                <button @click="scrollToSection('{{ $id }}')" 
                        :class="activeTab === '{{ $id }}' ? 'border-black text-black' : 'border-transparent text-gray-500 hover:text-black'"
                        class="h-full border-b-[4px] px-2 text-[14px] font-semibold transition-all">
                    {{ __($label) }}
                </button>
                @endforeach
            </div>
            <div class="flex items-center gap-6">
                <div class="flex flex-col text-right">
                    <span class="text-[18px] font-bold text-gray-900" x-text="'Rp ' + price.toLocaleString('id-ID')"></span>
                    <div class="flex items-center justify-end text-[12px] font-semibold">
                        <i data-lucide="star" class="w-3 h-3 fill-current mr-1"></i>
                        <span>{{ $rating }}</span>
                        <div class="relative inline-block">
                            <button type="button" @click="scrollToSection('ulasan')" class="text-gray-900 underline text-sm font-semibold cursor-pointer hover:text-black transition-colors ml-1">
                                ({{ count($reviews) }} ulasan)
                            </button>
                        </div>
                    </div>
                </div>
                <button @click="document.querySelector('form').scrollIntoView({ behavior: 'smooth' })" 
                        class="bg-airbnb-red text-white px-6 py-3 rounded-xl font-bold text-[16px] hover:brightness-95 transition active:scale-95">
                    {{ __('Pesan') }}
                </button>
            </div>
        </div>
    </div>
    
    {{-- Breadcrumbs & Top Actions --}}
    <div class="flex justify-between items-center mb-4 text-sm font-medium">
        <div class="flex items-center space-x-2">
            <a href="{{ route('frontend.perahu') }}" class="hover:underline">{{ __('Perahu') }}</a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-900">{{ $roomContent->title }}</span>
        </div>
        <div class="flex items-center space-x-4">
            <button @click="shareUrl()" class="flex items-center hover:bg-gray-100 px-2 py-1 rounded-md transition border border-transparent">
                <i data-lucide="share" class="w-4 h-4 mr-2"></i> {{ __('Share') }}
                <span x-show="copied" x-cloak class="ml-2 text-green-600 text-[12px] animate-pulse">Disalin!</span>
            </button>
            <button @click="isSaved = !isSaved" 
                    class="flex items-center hover:bg-gray-100 px-2 py-1 rounded-md transition border border-transparent"
                    :class="isSaved ? 'text-[#E31C5F]' : 'text-gray-900'">
                <i data-lucide="heart" class="w-4 h-4 mr-2" :class="isSaved ? 'fill-[#E31C5F]' : ''"></i> 
                <span x-text="isSaved ? 'Tersimpan' : '{{ __('Simpan') }}'"></span>
            </button>
        </div>
    </div>

    {{-- Header --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 mb-2">
            @if($rating >= 4.8)
                <div class="flex items-center gap-1 bg-white border border-gray-200 px-3 py-1 rounded-full shadow-sm">
                    <i data-lucide="award" class="w-4 h-4 text-airbnb-red fill-current"></i>
                    <span class="text-[12px] font-bold text-gray-900 tracking-tight">{{ __('Pilihan tamu') }}</span>
                </div>
            @endif
            <span class="text-[14px] text-gray-500 font-medium">· KM {{ $room->nama_km ?? 'Angler' }}</span>
        </div>
        <h1 class="text-[26px] font-semibold text-gray-900 leading-tight">
            {{ $roomContent->title }}
        </h1>
        <div class="flex items-center mt-2 space-x-2 text-[14px]">
            <div class="flex items-center">
                <i data-lucide="star" class="w-4 h-4 text-black fill-current mr-1"></i>
                <span class="font-semibold">{{ $rating }}</span>
            </div>
            <span>·</span>
            <span class="underline font-semibold cursor-pointer" @click="scrollToSection('ulasan')">{{ count($reviews) }} {{ __('ulasan') }}</span>
            <span>·</span>
            <span class="underline font-semibold cursor-pointer" @click="scrollToSection('lokasi')">{{ $hotelContent->title ?? 'Jakarta Utara' }}</span>
        </div>
    </div>

    {{-- Image Gallery (Airbnb Grid) --}}
    @php
        // Pastikan kita memiliki minimal 5 gambar untuk grid
        $gridImages = $allImages;
        while(count($gridImages) < 5) {
            $gridImages[] = $allImages[0];
        }
    @endphp
    
    <style>
        .airbnb-gallery:hover .gallery-item::after { opacity: 1; }
        .gallery-item:hover::after { opacity: 0 !important; }
        .gallery-item::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.2);
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
            z-index: 10;
        }
    </style>

    <div id="foto-section" class="relative grid grid-cols-1 md:grid-cols-4 md:grid-rows-2 gap-2 rounded-2xl overflow-hidden h-[300px] md:h-[500px] mb-10 airbnb-gallery scroll-mt-24 group/gallery shadow-sm">
        {{-- Main Large Photo --}}
        <div class="md:col-span-2 md:row-span-2 relative cursor-pointer gallery-item overflow-hidden" @click="openPhotos = true">
            <img src="{{ $gridImages[0] }}" class="w-full h-full object-cover group-hover/gallery: brightness-95 hover:!brightness-100 transition-all duration-500">
        </div>
        
        {{-- 4 Supporting Photos --}}
        @foreach(array_slice($gridImages, 1, 4) as $idx => $img)
            <div class="hidden md:block relative cursor-pointer h-full gallery-item" @click="openPhotos = true">
                <img src="{{ $img }}" class="w-full h-full object-cover">
            </div>
        @endforeach
        
        {{-- View All Photos Button --}}
        <button @click="openPhotos = true" class="absolute bottom-6 right-6 bg-white border border-gray-900 rounded-lg px-4 py-1.5 font-semibold text-[14px] shadow-sm hover:bg-gray-100 transition z-30 transform hover:scale-[1.02] active:scale-[0.98] flex items-center">
            <i data-lucide="grid" class="w-4 h-4 mr-2"></i>
            {{ __('Tampilkan semua foto') }}
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
        {{-- Main Left Side --}}
        <div class="lg:col-span-8">
            
            {{-- Title & Host Info ala Airbnb --}}
            <div class="py-8 border-b border-gray-200">
                <div class="flex justify-between items-start gap-4">
                    <div class="flex-1">
                        <h2 class="text-[22px] font-semibold leading-tight">
                            Kapal {{ $room->nama_km ?? $roomContent->title }} dikelola oleh {{ $room->kapten ?? ($room->vendor->username ?? 'Kapten Profesional') }}
                        </h2>
                        <ol class="flex flex-wrap items-center gap-1.5 text-gray-700 mt-2 text-[16px]">
                            <li>{{ $room->adult }} tamu</li>
                            <li class="mt-0.5 sm:mt-0 text-[10px]">·</li>
                            <li>{{ $room->boat_length ?? '16' }}m panjang</li>
                            <li class="mt-0.5 sm:mt-0 text-[10px]">·</li>
                            <li>{{ $room->crew_count ?? '3' }} kru</li>
                            <li class="mt-0.5 sm:mt-0 text-[10px]">·</li>
                            <li>{{ $room->toilet_count ?? '1' }} toilet</li>
                        </ol>
                    </div>
                    <div class="relative shrink-0">
                        <div class="w-14 h-14 rounded-full overflow-hidden border border-gray-100 bg-gray-50 group cursor-pointer shadow-sm">
                            @if($room->vendor && $room->vendor->image)
                                <img src="{{ asset('assets/img/vendors/' . $room->vendor->image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-500 font-bold text-xl uppercase">
                                    {{ substr($room->vendor->username ?? 'K', 0, 1) }}
                                </div>
                            @endif
                        </div>
                        {{-- Verified Badge --}}
                        <div class="absolute -bottom-1 -right-1 bg-white rounded-full p-0.5 shadow-md">
                            <div class="bg-airbnb-red rounded-full p-1 leading-none flex items-center justify-center">
                                <i data-lucide="check" class="w-3 h-3 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Experience Highlights --}}
            <div class="py-8 space-y-6 border-b border-gray-200">
                @foreach((array)$highlights as $h)
                <div class="flex items-start gap-5">
                    <i data-lucide="{{ $h['icon'] ?? '' }}" class="w-8 h-8 text-gray-900 mt-1"></i>
                    <div>
                        <h4 class="font-semibold text-[16px]">{{ $h['title'] ?? '' }}</h4>
                        <p class="text-gray-500 text-[14px] leading-relaxed">{{ $h['desc'] ?? '' }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Boat Specs Detail --}}
            <div class="py-8 border-b border-gray-200">
                <h3 class="text-[22px] font-semibold mb-6">{{ __('Spesifikasi Kapal') }}</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-y-6 gap-x-4">
                    <div class="flex flex-col">
                        <span class="text-gray-500 text-sm">{{ __('Nama KM') }}</span>
                        <div class="flex items-center gap-2 mt-1">
                            <i data-lucide="ship" class="w-4 h-4 text-gray-900"></i>
                            <span class="font-medium underline">{{ $room->nama_km ?? '--' }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-gray-500 text-sm">{{ __('Kapten') }}</span>
                        <div class="flex items-center gap-2 mt-1">
                            <i data-lucide="user" class="w-4 h-4 text-gray-900"></i>
                            <span class="font-medium underline">{{ $room->captain_name ?? '--' }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-gray-500 text-sm">{{ __('Kru') }}</span>
                        <div class="flex items-center gap-2 mt-1">
                            <i data-lucide="users" class="w-4 h-4 text-gray-900"></i>
                            <span class="font-medium">{{ $room->crew_count ?? '0' }} {{ __('orang') }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-gray-500 text-sm">{{ __('Mesin Utama') }}</span>
                        <div class="flex items-center gap-2 mt-1">
                            <i data-lucide="zap" class="w-4 h-4 text-gray-900"></i>
                            <span class="font-medium">{{ $room->engine_1 ?? '--' }}</span>
                        </div>
                    </div>
                    @if($room->engine_2)
                    <div class="flex flex-col">
                        <span class="text-gray-500 text-sm">{{ __('Mesin Cadangan') }}</span>
                        <div class="flex items-center gap-2 mt-1">
                            <i data-lucide="zap-off" class="w-4 h-4 text-gray-900"></i>
                            <span class="font-medium">{{ $room->engine_2 }}</span>
                        </div>
                    </div>
                    @endif
                    <div class="flex flex-col">
                        <span class="text-gray-500 text-sm">{{ __('Lebar Kapal') }}</span>
                        <div class="flex items-center gap-2 mt-1">
                            <i data-lucide="maximize" class="w-4 h-4 text-gray-900"></i>
                            <span class="font-medium">{{ $room->boat_width ?? '4' }} meter</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- About / Description with Airbnb Modal --}}
            <div class="py-10 border-b border-gray-200">
                <div class="text-[16px] text-gray-700 leading-[1.6] font-light space-y-4">
                    <div class="overflow-hidden" style="display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical;">
                        {!! replaceBaseUrl($roomContent->content, 'summernote') !!}
                    </div>
                </div>
                <button @click="aboutModal = true" class="mt-6 font-semibold underline flex items-center group z-10 relative">
                    {{ __('Tampilkan lebih banyak') }}
                    <i data-lucide="chevron-right" class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform"></i>
                </button>

            </div>

            {{-- Amenities / Facilities --}}
            <div id="fasilitas-section" class="py-12 border-b border-gray-200 scroll-mt-24">
                <h3 class="text-[22px] font-semibold mb-6">{{ __('Apa yang ditawarkan kapal ini') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-5">
                    @php
                        $boatFeatures = [
                            ['key' => 'fishing_gear', 'label' => 'Alat Pancing Lengkap', 'icon' => 'fish'],
                            ['key' => 'bait', 'label' => 'Umpan & Hook', 'icon' => 'anchor'],
                            ['key' => 'life_jacket', 'label' => 'Life Jacket (BS)', 'icon' => 'shield'],
                            ['key' => 'ac', 'label' => 'Pendingin Udara (AC)', 'icon' => 'snowflake'],
                            ['key' => 'wifi', 'label' => 'Wi-Fi Gratis', 'icon' => 'wifi'],
                            ['key' => 'mineral_water', 'label' => 'Air Mineral', 'icon' => 'droplets'],
                            ['key' => 'breakfast', 'label' => 'Sarapan (Pagi)', 'icon' => 'utensils-crosshair'],
                            ['key' => 'refrigerator', 'label' => 'Kulkas / Cooler', 'icon' => 'refrigerator'],
                        ];
                    @endphp
                    @foreach($boatFeatures as $feat)
                        @if($room->{$feat['key']})
                        <div class="flex items-center gap-4 text-gray-900">
                            <i data-lucide="{{ $feat['icon'] }}" class="w-6 h-6 text-gray-700"></i>
                            <span class="text-[16px]">{{ __($feat['label']) }}</span>
                        </div>
                        @else
                        <div class="flex items-center gap-4 text-gray-300">
                            <i data-lucide="{{ $feat['icon'] }}" class="w-6 h-6"></i>
                            <span class="text-[16px] line-through">{{ __($feat['label']) }}</span>
                        </div>
                        @endif
                    @endforeach

                    @foreach($amenities as $amenity)
                    <div class="flex items-center gap-4 text-gray-900">
                        <i data-lucide="check-circle" class="w-6 h-6 text-gray-700"></i>
                        <span class="text-[16px]">{{ $amenity->name }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Video Section --}}
            @if($embed_url)
            <div class="py-12 border-b border-gray-200">
                <h3 class="text-[22px] font-semibold mb-6">{{ __('Video Pengalaman Trip') }}</h3>
                <div class="w-full aspect-video rounded-2xl overflow-hidden border border-gray-200 shadow-sm">
                    <iframe class="w-full h-full" src="{{ $embed_url }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
            @endif

            {{-- Reviews Section (High Fidelity Airbnb Style) --}}
            <div id="ulasan-section" class="py-12 border-b border-gray-200 scroll-mt-24">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
                    <div class="flex items-center gap-2">
                        <i data-lucide="star" class="w-5 h-5 text-black fill-current"></i>
                        <h3 class="text-[22px] font-semibold">
                            {{ $rating }} · {{ count($reviews) }} ulasan
                        </h3>
                    </div>
                </div>

                {{-- Airbnb-Style Review Infographic --}}
                <style>
                    .review-infographic-container {
                        display: flex;
                        flex-direction: row;
                        border-bottom: 1px solid #ebebeb;
                        padding-bottom: 48px;
                        gap: 40px;
                        margin-bottom: 48px;
                    }
                    .overall-rating-section {
                        flex: 1;
                        min-width: 200px;
                        padding-right: 24px;
                        border-right: 1px solid #f1f1f1;
                    }
                    .categorical-rating-grid {
                        flex: 3;
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                        gap: 24px;
                    }
                    .cat-item {
                        display: flex;
                        flex-direction: column;
                        justify-content: space-between;
                        height: 90px;
                        padding-left: 20px;
                        border-left: 1px solid #f1f1f1;
                    }
                    
                    @media (max-width: 767px) {
                        .review-infographic-container {
                            flex-direction: column;
                            gap: 32px;
                        }
                        .overall-rating-section {
                            border-right: none;
                            padding-right: 0;
                            border-bottom: 1px solid #f1f1f1;
                            padding-bottom: 32px;
                        }
                        .categorical-rating-grid {
                            grid-template-columns: repeat(3, 1fr); /* Compact 3 columns for mobile */
                            gap: 12px;
                        }
                        .cat-item {
                            border-left: none;
                            padding-left: 0;
                            height: auto;
                            gap: 4px;
                            text-align: center;
                            align-items: center;
                        }
                        .cat-item h5 {
                            font-size: 11px !important;
                        }
                        .cat-item .text-[18px] {
                            font-size: 14px !important;
                        }
                    }
                </style>

                <div class="review-infographic-container">
                    @php
                        // Calculate averages if reviews exist, otherwise use defaults
                        $hasReviews = count($reviews) > 0;
                        $detailedRatings = [
                            ['label' => 'Kebersihan', 'key' => 'cleanliness', 'icon' => 'spray-can'],
                            ['label' => 'Keakuratan', 'key' => 'accuracy', 'icon' => 'check-circle'],
                            ['label' => 'Check-in', 'key' => 'check_in', 'icon' => 'key'],
                            ['label' => 'Komunikasi', 'key' => 'communication', 'icon' => 'message-square'],
                            ['label' => 'Lokasi', 'key' => 'location', 'icon' => 'map'],
                            ['label' => 'Nilai ekonomis', 'key' => 'value', 'icon' => 'tag'],
                        ];
                        
                        // Rating weights for progress bar
                        $ratingCounts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
                        if ($hasReviews) {
                            foreach($reviews as $r) {
                                $rd = floor($r->rating);
                                if ($rd >= 1 && $rd <= 5) $ratingCounts[$rd]++;
                            }
                        }
                    @endphp

                    {{-- Overall Weight Bar --}}
                    <div class="overall-rating-section">
                        <h4 class="text-[14px] font-semibold mb-4 text-gray-900">Nilai keseluruhan</h4>
                        <div class="space-y-1.5">
                            @foreach([5, 4, 3, 2, 1] as $num)
                                @php 
                                    $percent = $hasReviews ? ($ratingCounts[$num] / count($reviews)) * 100 : 0; 
                                    if (!$hasReviews && $num == 5) $percent = 100; // Visual placeholder for new boats
                                @endphp
                                <div class="flex items-center gap-3">
                                    <span class="text-[12px] text-gray-500 w-3">{{ $num }}</span>
                                    <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div style="width: {{ $percent }}%;" class="h-full bg-gray-900 rounded-full"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Categorical Ratings with Icons --}}
                    <div class="categorical-rating-grid">
                        @foreach($detailedRatings as $dr)
                            @php
                                $avg = $hasReviews ? $reviews->avg($dr['key']) : 5.0;
                                $formattedAvg = number_format($avg ?: 5.0, 1, ',', '.');
                            @endphp
                            <div class="cat-item">
                                <div>
                                    <h5 class="text-[14px] font-medium text-gray-900 mb-1">{{ $dr['label'] }}</h5>
                                    <div class="text-[18px] font-semibold text-gray-900">{{ $formattedAvg }}</div>
                                </div>
                                <div class="mt-1 text-gray-800">
                                    <i data-lucide="{{ $dr['icon'] }}" class="w-5 h-5 stroke-[1.5px]"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Airbnb Style Review Summary (Pilihan Tamu) --}}
                <div class="flex flex-col items-center justify-center text-center py-12 mb-10 border-t border-gray-200">
                    <div class="flex items-center justify-center gap-8 mb-8">
                        {{-- Gold Branch Left --}}
                        <div class="relative w-16 h-28 hidden md:block opacity-80">
                            <svg viewBox="0 0 32 32" class="w-full h-full fill-current text-gray-900"><path d="M12.63 7.02l-1.42 1.41 1.42 1.42L9.79 12.7l1.41 1.41 2.83-2.83 1.42 1.42-1.42 1.41 1.42 1.42-2.12 2.12 1.41 1.41 2.13-2.12 1.41 1.41-1.41 1.42 1.41 1.41 2.83-2.83 1.42 1.42-1.41 1.41 1.41 1.42-4.24 4.24 1.41 1.41 4.24-4.24 1.42 1.41-1.42 1.42 1.42 1.41 2.83-2.83-1.42-1.41 1.42-1.42-2.83-2.83 1.41-1.41 2.83 2.83 1.42-1.42-1.42-1.41 1.42-1.42-2.12-2.11 1.41-1.42 2.12 2.12 1.42-1.41-1.42-1.42 1.42-1.41-2.83-2.83-1.42 1.42-1.41-1.42 2.83-2.83-1.42-1.41-2.83 2.83-1.41-1.41 2.83-2.83z"></path></svg>
                        </div>

                        <div class="flex flex-col items-center">
                            <span class="text-[84px] font-bold leading-none tracking-tighter text-gray-900">{{ number_format($rating, 1, ',', '.') }}</span>
                            <div class="flex flex-col items-center mt-2">
                                <span class="text-[18px] font-bold text-gray-900 tracking-tight">{{ __('Pilihan tamu') }}</span>
                                <p class="text-[14px] text-gray-500 max-w-[340px] mt-4 leading-relaxed">
                                    Rumah ini berada di 10% teratas untuk iklan yang memenuhi syarat berdasarkan nilai, ulasan, dan keandalan
                                </p>
                                <div class="relative mt-2" @click.away="reviewModal = false">
                                    <button @click.stop="reviewModal = !reviewModal" class="text-[14px] text-gray-900 underline font-semibold hover:text-black transition-colors">
                                        Cara kerja ulasan
                                    </button>

                                    {{-- Airbnb-Style Tooltip Popover (Robust Inline Styles) --}}
                                    <div x-show="reviewModal" x-cloak 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 translate-y-[-10px] scale-95"
                                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                         style="background-color: #222222 !important; color: white !important; width: 360px !important; position: absolute !important; z-index: 10003 !important; border-radius: 24px !important; left: 50% !important; transform: translateX(-50%) !important; box-shadow: 0 8px 28px rgba(0,0,0,0.2) !important; padding: 32px !important; top: calc(100% + 15px) !important; text-align: left !important; display: block !important;">
                                        
                                        {{-- Arrow pointing UP --}}
                                        <div style="background-color: #222222 !important; width: 16px !important; height: 16px !important; position: absolute !important; top: -8px !important; left: 50% !important; transform: translateX(-50%) rotate(45deg) !important; z-index: -1 !important;"></div>
                                        
                                        <button @click.stop="reviewModal = false" style="position: absolute !important; top: 16px !important; right: 16px !important; color: white !important; cursor: pointer !important; background: transparent !important; border: none !important;">
                                            <i data-lucide="x" style="width: 20px !important; height: 20px !important;"></i>
                                        </button>

                                        <h3 style="font-size: 18px !important; font-weight: 700 !important; margin-bottom: 16px !important; color: white !important; display: block !important;">Cara kerja ulasan</h3>
                                        <div style="font-size: 14px !important; line-height: 1.6 !important; color: #dddddd !important; font-weight: 300 !important; display: flex !important; flex-direction: column !important; gap: 20px !important;">
                                            <p>Ulasan dari tamu terdahulu akan membantu komunitas GoFishi mendapatkan informasi lebih lanjut tentang setiap perahu dan kapten. Secara default, ulasan diurutkan berdasarkan relevansinya (terbaru, panjang ulasan, dan keandalan).</p>
                                            <p>Hanya tamu yang memesan reservasi dan telah menyelesaikan perjalanan memancing yang bisa memberikan ulasan. GoFishi memoderasi ulasan untuk memastikan kejujuran dan kepatuhan terhadap kebijakan komunitas kami.</p>
                                            <p>Agar memenuhi syarat untuk peringkat 10% teratas atau label Pilihan Tamu, iklan perahu membutuhkan setidaknya 5 ulasan dalam 24 bulan terakhir.</p>
                                        </div>

                                        <div style="margin-top: 32px !important; padding-top: 16px !important; border-top: 1px solid rgba(255,255,255,0.1) !important;">
                                            <a href="{{ url('/cara-kerja-ulasan') }}" target="_blank" style="font-size: 13px !important; font-bold: 700 !important; color: white !important; text-decoration: underline !important;">
                                                Pelajari lebih lanjut di Pusat Bantuan
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Gold Branch Right (Flipped) --}}
                        <div class="relative w-16 h-28 hidden md:block opacity-80 scale-x-[-1]">
                            <svg viewBox="0 0 32 32" class="w-full h-full fill-current text-gray-900"><path d="M12.63 7.02l-1.42 1.41 1.42 1.42L9.79 12.7l1.41 1.41 2.83-2.83 1.42 1.42-1.42 1.41 1.42 1.42-2.12 2.12 1.41 1.41 2.13-2.12 1.41 1.41-1.41 1.42 1.41 1.41 2.83-2.83 1.42 1.42-1.41 1.41 1.41 1.42-4.24 4.24 1.41 1.41 4.24-4.24 1.42 1.41-1.42 1.42 1.42 1.41 2.83-2.83-1.42-1.41 1.42-1.42-2.83-2.83 1.41-1.41 2.83 2.83 1.42-1.42-1.42-1.41 1.42-1.42-2.12-2.11 1.41-1.42 2.12 2.12 1.42-1.41-1.42-1.42 1.42-1.41-2.83-2.83-1.42 1.42-1.41-1.42 2.83-2.83-1.42-1.41-2.83 2.83-1.41-1.41 2.83-2.83z"></path></svg>
                        </div>
                    </div>

                    {{-- Write Review Button/Form Toggle --}}
                    @auth('web')
                        @php
                            $hasBooking = \App\Models\Booking::where('user_id', Auth::guard('web')->id())
                                ->where('room_id', $room->id)
                                ->where('payment_status', 1)
                                ->exists();
                        @endphp
                        
                        @if($hasBooking)
                        <div x-data="{ showForm: false }" class="w-full md:w-auto">
                            <button @click="showForm = !showForm" 
                                    class="w-full md:w-auto px-6 py-2.5 border border-neutral-900 rounded-xl font-semibold hover:bg-neutral-50 transition active:scale-95 flex items-center justify-center gap-2">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                                {{ __('Tulis Ulasan') }}
                            </button>

                            {{-- Review Form --}}
                            <div x-show="showForm" x-cloak x-collapse class="mt-8 p-8 border border-neutral-200 rounded-2xl bg-neutral-50/50 shadow-inner">
                                <form action="{{ route('frontend.perahu.store_review', $room->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-6">
                                        <label class="block text-sm font-bold text-neutral-900 mb-3 uppercase tracking-wider">Berapa bintang untuk trip ini?</label>
                                        <div class="flex gap-2" x-data="{ rating: 5, hover: 0 }">
                                            <input type="hidden" name="rating" :value="rating">
                                            @foreach(range(1, 5) as $i)
                                            <button type="button" 
                                                    @mouseenter="hover = {{ $i }}" 
                                                    @mouseleave="hover = 0"
                                                    @click="rating = {{ $i }}"
                                                    class="focus:outline-none transition-transform hover:scale-110">
                                                <i data-lucide="star" 
                                                   :class="(hover || rating) >= {{ $i }} ? 'text-black fill-current' : 'text-neutral-300'"
                                                   class="w-8 h-8 transition-colors"></i>
                                            </button>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="mb-6">
                                        <label class="block text-sm font-bold text-neutral-900 mb-3 uppercase tracking-wider">Ceritakan pengalaman Anda</label>
                                        <textarea name="review" rows="4" required
                                                  class="w-full px-4 py-3 rounded-xl border border-neutral-300 focus:border-black focus:ring-0 text-[15px] font-light placeholder:text-neutral-400"
                                                  placeholder="Bagaimana pelayanan kapten? Apakah kapalnya bersih dan nyaman?"></textarea>
                                    </div>
                                    <div class="flex justify-end gap-3">
                                        <button type="button" @click="showForm = false" class="px-6 py-2.5 font-semibold text-sm underline">{{ __('Batal') }}</button>
                                        <button type="submit" class="bg-airbnb-red text-white px-8 py-2.5 rounded-xl font-bold text-sm shadow-sm hover:brightness-95 transition">
                                            {{ __('Kirim Ulasan') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif
                    @endauth
                </div>

                @if(count($reviews) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                    @foreach($reviews as $review)
                    <div class="flex flex-col gap-4 animate-in fade-in slide-in-from-bottom-2 duration-700">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-neutral-100 flex items-center justify-center overflow-hidden border border-neutral-100">
                                @if($review->user && $review->user->image)
                                    <img src="{{ asset('assets/img/users/' . $review->user->image) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-neutral-200 text-neutral-500 font-bold">
                                        {{ strtoupper(substr($review->user->username ?? 'G', 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-semibold text-neutral-900 text-[16px] leading-tight">
                                    {{ $review->user->username ?? 'Guest User' }}
                                </h4>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <div class="flex text-[10px]">
                                        @foreach(range(1, 5) as $i)
                                            <i data-lucide="star" class="w-3 h-3 {{ $review->rating >= $i ? 'text-black fill-current' : 'text-neutral-300' }}"></i>
                                        @endforeach
                                    </div>
                                    <span class="text-neutral-400 text-[12px]">·</span>
                                    <p class="text-neutral-500 text-[14px]">
                                        {{ \Carbon\Carbon::parse($review->created_at)->translatedFormat('F Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="text-neutral-700 text-[16px] font-light leading-relaxed review-content" x-data="{ expanded: false }">
                            <div :class="expanded ? '' : 'line-clamp-3 overflow-hidden text-ellipsis'">
                                {!! nl2br(e($review->review)) !!}
                            </div>
                            @if(strlen($review->review) > 180)
                                <button @click="expanded = !expanded" 
                                        class="mt-2 text-gray-900 font-semibold underline hover:text-black transition-colors text-[14px] flex items-center gap-1 z-10 relative">
                                    <span x-text="expanded ? 'Sembunyikan' : 'Tampilkan lebih banyak'"></span>
                                    <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" :class="expanded ? 'rotate-180' : ''"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                @if(count($reviews) > 6)
                <button class="mt-10 px-6 py-3 border border-neutral-900 rounded-xl font-semibold hover:bg-neutral-50 transition active:scale-95">
                    Tampilkan semua {{ count($reviews) }} ulasan
                </button>
                @endif
                @else
                <div class="flex flex-col items-center justify-center py-12 bg-neutral-50 rounded-2xl border border-dashed border-neutral-200 animate-in fade-in duration-1000">
                    <i data-lucide="message-square" class="w-12 h-12 text-neutral-300 mb-4"></i>
                    <p class="text-neutral-500 font-light">{{ __('Belum ada ulasan untuk kapal ini.') }}</p>
                    <p class="text-neutral-400 text-sm mt-1">{{ __('Jadilah yang pertama untuk memberikan ulasan!') }}</p>
                </div>
                @endif
            </div>

            @php
                $vInfo = $room->vendor->vendor_infos()->where('language_id', $langId)->first();
                $joinedYear = \Carbon\Carbon::parse($room->vendor->created_at)->format('Y');
                $isSuperhost = $rating >= 4.8 && count($reviews) >= 5;
            @endphp
            {{-- Host Profile Section (Airbnb High-Fidelity Refactored) --}}
            <div id="host-section" style="padding-top: 48px; padding-bottom: 48px; border-bottom: 1px solid #ebebeb; scroll-margin-top: 96px;">
                <h3 style="font-size: 22px; font-weight: 600; margin-bottom: 32px; color: #222222;">{{ __('Tuan rumah Anda') }}</h3>
                
                <div style="display: flex; flex-wrap: wrap; gap: 48px;">
                    {{-- Left Column: Host Card & Personal Info --}}
                    <div style="flex: 1; min-width: 320px; display: flex; flex-direction: column; gap: 40px;">
                        <style>
                            .airbnb-host-card-v2 {
                                background-color: white;
                                border-radius: 24px;
                                padding: 32px;
                                box-shadow: 0 6px 16px rgba(0,0,0,0.12);
                                border: 1px solid rgba(0,0,0,0.04);
                                width: 100%;
                                max-width: 440px;
                                display: flex;
                                flex-direction: row;
                                align-items: stretch;
                                gap: 0;
                            }
                            .host-identity-v2 {
                                flex: 0 0 170px;
                                border-right: 1px solid #ebebeb;
                                padding-right: 24px;
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                                text-align: center;
                            }
                            .host-stats-v2 {
                                flex: 1;
                                display: flex;
                                flex-direction: column;
                                padding-left: 24px;
                                justify-content: center;
                                gap: 16px;
                            }
                            @media (max-width: 767px) {
                                .airbnb-host-card-v2 {
                                    flex-direction: column;
                                    padding: 24px;
                                }
                                .host-identity-v2 {
                                    border-right: none;
                                    border-bottom: 1px solid #ebebeb;
                                    padding-right: 0;
                                    padding-bottom: 24px;
                                    margin-bottom: 24px;
                                    flex: none;
                                    width: 100%;
                                }
                                .host-stats-v2 {
                                    padding-left: 0;
                                }
                            }
                        </style>

                        <div class="airbnb-host-card-v2">
                            {{-- Card Left --}}
                            <div class="host-identity-v2">
                                <div style="position: relative; margin-bottom: 12px; width: 104px; height: 104px;">
                                    <div style="width: 104px; height: 104px; border-radius: 50%; overflow: hidden; background-color: #f7f7f7;">
                                        @if($room->vendor && $room->vendor->photo)
                                            <img src="{{ asset('assets/admin/img/vendor-photo/' . $room->vendor->photo) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @elseif($room->vendor && $room->vendor->image)
                                            <img src="{{ asset('assets/img/vendors/' . $room->vendor->image) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background-color: #f0f0f0; color: #717171; font-weight: bold; font-size: 32px;">
                                                {{ strtoupper(substr($vInfo->name ?? ($room->vendor->username ?? 'K'), 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div style="position: absolute; bottom: 8px; right: 0; background-color: white; border-radius: 50%; padding: 2px;">
                                        <div style="background-color: #E31C5F; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                            <i data-lucide="check" style="width: 14px; height: 14px; color: white; stroke-width: 4px;"></i>
                                        </div>
                                    </div>
                                </div>
                                <h4 style="font-size: 32px; font-weight: 800; line-height: 1; margin: 0; color: #222222; letter-spacing: -0.02em;">
                                    {{ $vInfo->name ?? ($room->vendor->username ?? 'Kapten') }}
                                </h4>
                                <div style="display: flex; align-items: center; gap: 4px; font-size: 14px; font-weight: 600; margin-top: 8px; color: #222222;">
                                    <i data-lucide="award" style="width: 14px; height: 14px;"></i>
                                    <span>Host Teladan</span>
                                </div>
                            </div>

                            {{-- Card Right --}}
                            <div class="host-stats-v2">
                                <div>
                                    <div style="font-size: 20px; font-weight: 800; color: #222222; line-height: 1;">{{ count($reviews) }}</div>
                                    <div style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: #717171; margin-top: 2px;">Ulasan</div>
                                </div>
                                <div style="height: 1px; background-color: #ebebeb; width: 100%;"></div>
                                <div>
                                    <div style="font-size: 20px; font-weight: 800; color: #222222; line-height: 1; display: flex; align-items: center; gap: 4px;">
                                        {{ $rating }} <i data-lucide="star" style="width: 14px; height: 14px; fill: currentColor;"></i>
                                    </div>
                                    <div style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: #717171; margin-top: 2px;">Penilaian</div>
                                </div>
                                <div style="height: 1px; background-color: #ebebeb; width: 100%;"></div>
                                <div>
                                    <div style="font-size: 20px; font-weight: 800; color: #222222; line-height: 1;">{{ (int)\Carbon\Carbon::parse($room->vendor->created_at ?? now())->diffInYears() + 1 }}</div>
                                    <div style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: #717171; margin-top: 2px;">Tahun menjadi tuan rumah</div>
                                </div>
                            </div>
                        </div>

                        {{-- Personal Info Icons --}}
                        <div class="space-y-6 px-2">
                            <div class="flex items-center gap-4">
                                <i data-lucide="cake" class="w-6 h-6 text-gray-900 border-b border-gray-900/10 pb-0.5"></i>
                                <span class="text-[16px] text-gray-900 font-light">Lahir di tahun {{ \Carbon\Carbon::parse($room->vendor->dob ?? '1990-01-01')->format('90-an') }}</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <i data-lucide="languages" class="w-6 h-6 text-gray-900"></i>
                                <span class="text-[16px] text-gray-900 font-light">Saya menguasai {{ $vInfo->languages ?? 'Bahasa Inggris dan Indonesia' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Bio & Response --}}
                    <div style="flex: 1.5; min-width: 320px; display: flex; flex-direction: column; gap: 40px;">
                        <div>
                            <h4 class="text-[18px] font-bold text-gray-900 mb-4">{{ $vInfo->name ?? ($room->vendor->username ?? 'Kapten') }} adalah Host Teladan</h4>
                            <p class="text-[16px] text-gray-700 leading-[1.6] font-light">
                                Host Teladan adalah tuan rumah berpengalaman dan berperingkat tinggi yang berkomitmen memberi tamu pengalaman menginap yang memuaskan.
                            </p>
                        </div>

                        <div>
                            <h4 class="text-[18px] font-bold text-gray-900 mb-4">Detail Tuan Rumah</h4>
                            <div class="space-y-1 text-[16px] text-gray-800 font-light">
                                <div class="flex items-center gap-2">
                                    <span>Tingkat respons:</span>
                                    <span>100%</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span>Menanggapi dalam satu jam</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-6">
                            <a href="{{ route('frontend.vendor.details', ['username' => $room->vendor->username]) }}" 
                               style="background-color: #222222 !important; color: white !important;"
                               class="w-fit px-8 py-3.5 rounded-xl font-bold text-[16px] hover:bg-black transition active:scale-95 text-center">
                                Kirimkan pesan kepada tuan rumah
                            </a>
                            
                            <div style="height: 1px; background-color: #ebebeb; width: 100%;"></div>

                            <div class="flex items-start gap-3 text-[12px] text-gray-500">
                                <i data-lucide="shield-alert" class="w-5 h-5 text-rose-500 shrink-0"></i>
                                <p>Untuk membantu melindungi pembayaran Anda, pastikan Anda selalu menggunakan GoFishi untuk mengirimkan uang dan berkomunikasi dengan tuan rumah.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="lokasi-section" class="py-12 border-b border-gray-200 scroll-mt-24">
                <h3 class="text-[22px] font-semibold mb-4">{{ __('Lokasi Keberangkatan') }}</h3>
                <p class="mb-6 text-gray-700 text-[16px] font-light">{{ $roomContent->address ?? $hotelContent->address }}</p>
                
                <div class="w-full h-[480px] bg-gray-100 rounded-2xl overflow-hidden border border-gray-200 shadow-sm relative">
                    @if($room->latitude && $room->longitude)
                        <iframe width="100%" height="100%" frameborder="0" style="border:0" 
                                src="https://www.google.com/maps/embed/v1/search?key={{ config('google.maps_api_key') }}&q={{ $room->latitude }},{{ $room->longitude }}" 
                                allowfullscreen></iframe>
                        {{-- Circular Overlay Airbnb Style --}}
                        <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                            <div class="w-32 h-32 bg-airbnb-red/10 border-2 border-airbnb-red rounded-full flex items-center justify-center backdrop-blur-[1px] shadow-[0_0_50px_rgba(255,56,92,0.2)]">
                                <i data-lucide="ship" class="w-8 h-8 text-airbnb-red fill-current"></i>
                            </div>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center h-full bg-gray-50 text-gray-400">
                            <i data-lucide="map" class="w-16 h-16 mb-4 opacity-20"></i>
                            <p class="font-medium">{{ __('Data lokasi belum dikonfigurasi') }}</p>
                            <p class="text-xs mt-1">{{ __('Koordinat: ' . ($room->latitude ?? '-') . ', ' . ($room->longitude ?? '-')) }}</p>
                        </div>
                    @endif
                </div>
                
                <div class="mt-6 flex flex-col gap-2">
                    <h4 class="font-bold text-[16px]">{{ $hotelContent->title ?? 'Jakarta Utara' }}</h4>
                    <p class="text-gray-600 text-[14px] leading-relaxed">
                        {{ __('Lokasi pelabuhan keberangkatan yang strategis dengan fasilitas dermaga yang lengkap dan aman untuk kendaraan Anda selama perjalanan trip.') }}
                    </p>
                </div>
            </div>

        </div>

        {{-- Booking Card (Sticky Right Side) --}}
        <div class="lg:col-span-4 relative">
            <div id="booking-card" class="sticky top-24 scroll-mt-20">
                <div class="border border-gray-200 rounded-2xl p-6 shadow-xl bg-white">
                    <form action="{{ route('frontend.perahu.go.checkout') }}" method="POST">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                        <input type="hidden" name="package_id" :value="selectedPackageId">
                        
                        <div class="flex justify-between items-start mb-4">
                            <div x-show="!checkIn">
                                <span class="text-[22px] font-bold text-gray-900 leading-tight">{{ __('Tambahkan tanggal untuk melihat harga') }}</span>
                            </div>
                            <div x-show="checkIn" x-cloak>
                                <span class="text-[22px] font-bold text-gray-900" x-text="'Rp ' + price.toLocaleString('id-ID')"></span>
                                <span class="text-gray-500 text-[16px]"> / paket</span>
                            </div>
                            <div class="flex items-center text-sm font-semibold mt-2" x-show="checkIn" x-cloak>
                                <i data-lucide="star" class="w-3.5 h-3.5 fill-current text-black mr-1"></i>
                                <span>{{ $rating }}</span>
                            </div>
                        </div>

                        {{-- Unified Airbnb UI Box: Dates & Guests (Dropdown Architecture) --}}
                        <div class="border border-gray-400 rounded-xl mb-4 shadow-sm bg-white relative">
                            
                            {{-- Date Row with Clean Accordion Flow --}}
                            <div id="checkin-trigger" @click.stop="calendarOpen = !calendarOpen; guestMenu = false;" class="flex border-b border-gray-400 cursor-pointer hover:bg-gray-50 transition-colors">
                                <div class="flex-1 p-3 border-r border-gray-400">
                                    <label class="text-[9px] font-extrabold text-gray-900 uppercase tracking-tighter">CHECK-IN</label>
                                    <div class="text-[14px] text-gray-700 font-medium truncate" x-text="checkIn ? new Date(checkIn).toLocaleDateString('id-ID') : 'Pilih tanggal'">Pilih tanggal</div>
                                </div>
                                <div class="flex-1 p-3">
                                    <label class="text-[9px] font-extrabold text-gray-900 uppercase tracking-tighter">CHECK-OUT</label>
                                    <div class="text-[14px] text-gray-700 font-medium truncate" x-text="checkOut ? new Date(checkOut).toLocaleDateString('id-ID') : 'Pilih tanggal'">Pilih tanggal</div>
                                </div>
                            </div>
                            
                            {{-- Accordion Calendar Engine (No absolute overlapping, pushes content down elegantly) --}}
                            <div x-show="calendarOpen" x-cloak 
                                 class="bg-gray-50 border-b border-gray-300 p-4"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-[-10px]"
                                 x-transition:enter-end="opacity-100 translate-y-0">
                                <div class="w-full flex justify-center">
                                    <div id="inline-calendar"></div>
                                </div>
                                <div class="flex justify-between items-center mt-3 pt-3">
                                    <button type="button" @click="if(calendarInstance) calendarInstance.clear()" class="text-[13px] font-bold underline hover:text-red-500 transition-colors">Hapus</button>
                                    <button type="button" @click="calendarOpen = false" class="bg-black text-white px-4 py-1.5 rounded-lg font-bold text-[13px] hover:bg-gray-800 transition-colors">Selesai</button>
                                </div>
                            </div>
                            
                            <input type="hidden" name="checkInDate" x-model="checkIn">
                            <input type="hidden" name="checkout_date" x-model="checkOut">
                            
                            {{-- Integrated Guest Selector --}}
                            <div class="p-3 cursor-pointer hover:bg-gray-50 transition-colors relative z-20 rounded-b-xl" 
                                 @click.away="guestMenu = false">
                                <div @click.stop="guestMenu = !guestMenu; calendarOpen = false;" class="flex justify-between items-center w-full shadow-none border-none p-0 bg-transparent">
                                    <div class="flex flex-col text-left">
                                        <label class="text-[9px] font-extrabold text-gray-900 uppercase tracking-tighter">TAMU</label>
                                        <div class="text-[14px] text-gray-700 font-medium" x-text="getTotal() + ' tamu' + (counts.infants ? ', ' + counts.infants + ' bayi' : '')">1 tamu</div>
                                    </div>
                                    <i data-lucide="chevron-down" class="w-5 h-5 text-gray-900 transition-transform" :class="guestMenu ? 'rotate-180' : ''"></i>
                                </div>
                                <input type="hidden" name="adult" x-model="counts.adults">
                                <input type="hidden" name="children" x-model="counts.children">
                                <input type="hidden" name="infant" x-model="counts.infants">

                                {{-- Professional Airbnb Guest Dropdown --}}
                                <div x-show="guestMenu" x-cloak 
                                     class="absolute top-full left-[-1px] right-[-1px] bg-white border border-gray-200 shadow-xl rounded-2xl mt-1 p-6 z-[10000] space-y-6"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0">
                                    
                                    <div class="flex justify-between items-center">
                                        <div class="flex flex-col">
                                            <span class="text-[16px] font-semibold text-gray-900 leading-tight">Dewasa</span>
                                            <span class="text-[14px] text-gray-500 font-light">Usia 13+</span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <button type="button" @click.stop="if(counts.adults > 1) counts.adults--" 
                                                    class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:border-black hover:text-black transition-colors"
                                                    :class="counts.adults <= 1 ? 'opacity-20 cursor-not-allowed' : ''">−</button>
                                            <span class="w-4 text-center font-medium text-gray-900" x-text="counts.adults"></span>
                                            <button type="button" @click.stop="if(getTotal() < maxGuests) counts.adults++" 
                                                    class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:border-black hover:text-black transition-colors"
                                                    :class="getTotal() >= maxGuests ? 'opacity-20 cursor-not-allowed' : ''">+</button>
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <div class="flex flex-col">
                                            <span class="text-[16px] font-semibold text-gray-900 leading-tight">Anak-anak</span>
                                            <span class="text-[14px] text-gray-500 font-light">Usia 2-12</span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <button type="button" @click.stop="if(counts.children > 0) counts.children--" 
                                                    class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:border-black hover:text-black transition-colors"
                                                    :class="counts.children <= 0 ? 'opacity-20 cursor-not-allowed' : ''">−</button>
                                            <span class="w-4 text-center font-medium text-gray-900" x-text="counts.children"></span>
                                            <button type="button" @click.stop="if(getTotal() < maxGuests) counts.children++" 
                                                    class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:border-black hover:text-black transition-colors"
                                                    :class="getTotal() >= maxGuests ? 'opacity-20 cursor-not-allowed' : ''">+</button>
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <div class="flex flex-col">
                                            <span class="text-[16px] font-semibold text-gray-900 leading-tight">Bayi</span>
                                            <span class="text-[14px] text-gray-500 font-light">Di bawah 2 thn</span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <button type="button" @click.stop="if(counts.infants > 0) counts.infants--" 
                                                    class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:border-black hover:text-black transition-colors"
                                                    :class="counts.infants <= 0 ? 'opacity-20 cursor-not-allowed' : ''">−</button>
                                            <span class="w-4 text-center font-medium text-gray-900" x-text="counts.infants"></span>
                                            <button type="button" @click.stop="counts.infants++" 
                                                    class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:border-black hover:text-black transition-colors">+</button>
                                        </div>
                                    </div>
                                    
                                    <div class="pt-2">
                                        <p class="text-[12px] text-gray-500 leading-tight mb-4">
                                            Tempat ini mengizinkan jumlah tamu maksimum {{ $room->adult }} orang, tidak termasuk bayi.
                                        </p>
                                        <div class="flex justify-end">
                                            <button type="button" @click="guestMenu = false" class="text-[14px] font-bold underline hover:bg-gray-50 px-3 py-1 rounded-lg">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Hidden Package Selection (Minimalist Approach) --}}
                        <div x-data="{ pkgOpen: false }" class="mb-4">
                            <button type="button" @click="pkgOpen = !pkgOpen" class="text-[12px] font-bold underline text-gray-500 ml-1">
                                <span x-text="pkgOpen ? 'Sembunyikan Paket' : 'Ganti Paket: ' + packages.find(p => p.id == selectedPackageId)?.name"></span>
                            </button>
                            <div x-show="pkgOpen" x-cloak class="mt-2 space-y-2 border border-gray-200 rounded-xl max-h-[150px] overflow-y-auto p-1">
                                @foreach($packages as $pkg)
                                <div @click="updatePackage({{ $pkg->id }}); pkgOpen = false" 
                                     class="flex justify-between items-center p-2 rounded-lg cursor-pointer hover:bg-rose-50 border-b border-gray-50 last:border-0 transition-colors"
                                     :class="selectedPackageId == {{ $pkg->id }} ? 'bg-rose-50' : ''">
                                    <div class="text-[12px]">
                                        <div class="font-bold {{ $pkg->id }}">{{ $pkg->name }}</div>
                                        <div class="text-gray-400">{{ $pkg->duration_days }} hari</div>
                                    </div>
                                    <div class="text-[12px] font-bold text-airbnb-red">{{ symbolPrice($pkg->price) }}</div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" 
                                @click="if(!checkIn) { $event.preventDefault(); calendarOpen = true; }"
                                style="background-color: #E31C5F !important; color: white !important;"
                                class="w-full py-3.5 rounded-xl font-bold text-[16px] shadow-sm hover:!bg-[#D70466] transition-all mt-2 active:scale-95">
                            {{ __('Periksa ketersediaan') }}
                        </button>

                        <div class="text-center mt-6">
                            <a href="#" class="text-[14px] text-gray-500 underline flex items-center justify-center gap-2">
                                <i data-lucide="flag" class="w-4 h-4"></i>
                                {{ __('Laporkan iklan ini') }}
                            </a>
                        </div>

                        <div class="flex justify-between text-[18px] font-bold mt-6">
                            <span>Total</span>
                            <span x-text="'Rp ' + price.toLocaleString('id-ID')"></span>
                        </div>
                    </form>
                </div>

                <div class="mt-8 border border-gray-200 rounded-xl p-6 flex flex-col items-center gap-4 text-center">
                    <div class="text-[18px] font-semibold">Tanya Kapten?</div>
                    <p class="text-[14px] text-gray-500">Punya pertanyaan khusus tentang spot pancing atau rute perjalanan?</p>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $room->vendor->phone ?? '08123456789') }}" target="_blank" class="w-full border border-gray-900 py-2.5 rounded-xl font-semibold hover:bg-gray-100 transition flex items-center justify-center gap-2">
                        <i class="fab fa-whatsapp"></i> Chat via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Full Screen Photo Modal (Lightbox) --}}
    <div x-show="aboutModal" x-cloak 
         class="fixed inset-0 z-[20000] flex items-center justify-center p-4 sm:p-6"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="aboutModal = false"></div>
        <div class="bg-white w-full max-w-3xl max-h-[90vh] rounded-3xl relative z-10 flex flex-col shadow-2xl overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white z-20">
                <button @click="aboutModal = false" class="p-2 hover:bg-gray-100 rounded-full transition">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
                <h3 class="font-bold text-[18px]">{{ __('Tentang tempat ini') }}</h3>
                <div class="w-9"></div>
            </div>
            <div class="p-8 md:p-12 overflow-y-auto text-gray-700 text-[16px] leading-[1.7] font-light description-modal-content">
                {!! replaceBaseUrl($roomContent->content, 'summernote') !!}
            </div>
        </div>
    </div>

    {{-- Full Screen Photo Modal (Lightbox) --}}
    <div x-show="openPhotos" x-cloak 
         class="fixed inset-0 z-[10002] bg-white overflow-y-auto" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-full"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-full">
        
        <div class="sticky top-0 bg-white/90 backdrop-blur-md z-50 px-6 py-4 flex justify-between items-center border-b border-gray-100">
            <button @click="openPhotos = false" class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition font-semibold text-[15px]">
                <i data-lucide="chevron-left" class="w-5 h-5"></i>
                <span>Kembali</span>
            </button>
            <div class="flex items-center space-x-4">
                <button @click="shareUrl()" class="flex items-center hover:bg-gray-100 px-3 py-2 rounded-lg transition text-[14px] font-semibold">
                    <i data-lucide="share" class="w-4 h-4 mr-2"></i> {{ __('Share') }}
                    <span x-show="copied" x-cloak class="ml-2 text-green-600 text-[12px] animate-pulse">Disalin!</span>
                </button>
                <button @click="isSaved = !isSaved" 
                        class="flex items-center hover:bg-gray-100 px-3 py-2 rounded-lg transition border border-transparent text-[14px] font-semibold"
                        :class="isSaved ? 'text-[#E31C5F]' : 'text-gray-900'">
                    <i data-lucide="heart" class="w-4 h-4 mr-2" :class="isSaved ? 'fill-[#E31C5F]' : ''"></i> 
                    <span x-text="isSaved ? 'Tersimpan' : '{{ __('Simpan') }}'"></span>
                </button>
            </div>
        </div>

        <div class="max-w-4xl mx-auto py-8 px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <img src="{{ $allImages[0] }}" class="w-full h-auto rounded-xl object-cover" style="max-height: 600px">
                </div>
                @foreach(array_slice($allImages, 1) as $idx => $img)
                    <div class="{{ $loop->iteration % 3 == 0 ? 'md:col-span-2' : '' }}">
                       <img src="{{ $img }}" class="w-full h-auto rounded-xl object-cover" style="max-height: 600px">
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Original Architecture Required No Separate Modal Container Here --}}

    {{-- Photo Grid --}}
    <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 flex justify-between items-center" style="z-index: 90; box-shadow: 0 -5px 15px rgba(0,0,0,0.05);">
        <div class="flex flex-col">
            <div class="flex items-center gap-1 leading-none">
                <span class="text-[17px] font-bold text-gray-900" x-text="'Rp ' + price.toLocaleString('id-ID')"></span>
                <span class="text-[14px] text-gray-500"> paket</span>
            </div>
            <div class="flex items-center text-[12px] font-semibold mt-1">
                <i data-lucide="star" class="w-3 h-3 fill-current text-black mr-1"></i>
                <span>{{ $rating }}</span>
                <span class="text-gray-400 font-normal ml-1 underline cursor-pointer hover:text-gray-600 transition-colors" @click="scrollToSection('ulasan')">· {{ count($reviews) }} {{ __('ulasan') }}</span>
            </div>
        </div>
        <a href="#booking-card" 
           onclick="event.preventDefault(); document.getElementById('booking-card').scrollIntoView({ behavior: 'smooth', block: 'center' });"
           class="bg-airbnb-red text-white px-8 py-3 rounded-xl font-bold text-base active:scale-95 transition-transform shadow-lg inline-block text-center">
            {{ __('Pesan') }}
        </a>
    </div>

    </div>
@endsection

@section('script')
<script>
    // Safeguard the data injection to prevent syntax errors
    window.appData = {
        packages: @json($packages ?? []),
        selectedPackageId: {{ $defaultPackage ? $defaultPackage->id : 'null' }},
        price: {{ $price ?? 0 }},
        maxGuests: {{ $room->adult ?? 10 }},
        bookedDates: @json($bookedDates ?? [])
    };

    // Define directly on window to bypass racing with alpine:init listener
    window.productDetails = function() {
        return {
            checkIn: '',
            checkOut: '',
            openPhotos: false,
            showStickyNav: false,
            activeTab: 'foto',
            packages: window.appData.packages,
            selectedPackageId: window.appData.selectedPackageId,
            price: window.appData.price,
            guests: 1,
            maxGuests: window.appData.maxGuests,
            aboutModal: false,
            guestMenu: false,
            counts: { adults: 1, children: 0, infants: 0, pets: 0 },
            reviewModal: false,
            calendarOpen: false,
            isSaved: false,
            copied: false,
            
            shareUrl() {
                const url = window.location.href;
                if (navigator.share) {
                    navigator.share({ title: document.title, url: url });
                } else {
                    navigator.clipboard.writeText(url);
                    this.copied = true;
                    setTimeout(() => this.copied = false, 2000);
                }
            },
            
            getTotal() { return this.counts.adults + this.counts.children },
            
            updatePackage(id) {
                this.selectedPackageId = id;
                const pkg = this.packages.find(p => p.id == id);
                if (pkg) this.price = pkg.price;
            },
            
            scrollToSection(id) {
                this.activeTab = id;
                const el = document.getElementById(id + '-section');
                if (el) {
                    const offset = 80;
                    const bodyRect = document.body.getBoundingClientRect().top;
                    const elementRect = el.getBoundingClientRect().top;
                    const elementPosition = elementRect - bodyRect;
                    const offsetPosition = elementPosition - offset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            },
            
            updateActiveTab() {
                const sections = ['foto', 'fasilitas', 'ulasan', 'lokasi'];
                for (const section of sections) {
                    const el = document.getElementById(section + '-section');
                    if (el) {
                        const rect = el.getBoundingClientRect();
                        if (rect.top <= 120 && rect.bottom >= 120) {
                            this.activeTab = section;
                            break;
                        }
                    }
                }
            },
            
            init() {
                // Initialize Flatpickr natively only when the Modal is actively opened and visible
                // This prevents Flatpickr bounds-measurement from crashing on a display:none element
                this.$watch('calendarOpen', (value) => {
                    if (value && !this.calendarInstance) {
                        this.$nextTick(() => {
                            this.calendarInstance = flatpickr("#inline-calendar", {
                                inline: true,
                                mode: "range",
                                dateFormat: "Y-m-d",
                                minDate: "today",
                                disable: window.appData.bookedDates,
                                showMonths: 1, // Fixed to 1 month to strictly prevent bounding box expansion / overlap
                                onChange: (selectedDates, dateStr, instance) => {
                                    // Direct Reactive Binding (No DOM Polling required)
                                    this.checkIn = selectedDates.length > 0 ? instance.formatDate(selectedDates[0], "Y-m-d") : '';
                                    this.checkOut = selectedDates.length > 1 ? instance.formatDate(selectedDates[1], "Y-m-d") : '';

                                    let nightCount = 0;
                                    if (selectedDates.length > 1) {
                                        const diffTime = Math.abs(selectedDates[1] - selectedDates[0]);
                                        nightCount = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                                    }

                                    // Update Modal Header UI
                                    const hNight = document.getElementById('modal-night-count');
                                    const hRange = document.getElementById('modal-date-range');
                                    const hIn = document.getElementById('modal-box-in');
                                    const hOut = document.getElementById('modal-box-out');

                                    if(hNight) hNight.innerText = nightCount > 0 ? `${nightCount} malam` : 'Pilih tanggal';
                                    if(hRange) hRange.innerText = dateStr || 'Tambahkan tanggal perjalanan Anda';
                                    if(hIn) hIn.innerText = selectedDates.length > 0 ? instance.formatDate(selectedDates[0], "d/m/Y") : 'Pilih tanggal';
                                    if(hOut) hOut.innerText = selectedDates.length > 1 ? instance.formatDate(selectedDates[1], "d/m/Y") : 'Pilih tanggal';
                                }
                            });
                        });
                    }
                });

                // Establish invisible reset button binding
                const resetBtn = document.createElement('button');
                resetBtn.id = 'reset-date-btn';
                resetBtn.style.display = 'none';
                resetBtn.onclick = () => {
                    if (this.calendarInstance) this.calendarInstance.clear();
                };
                document.body.appendChild(resetBtn);
            }
        };
    };
</script>
@endsection
