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
  $price = $room->price_day_1 ?? $room->min_price ?? 0;
  $amenities = $amenities ?? collect([]);
  $reviews = $room_reviews['reviews'] ?? collect([]);
  
  // Boat Specific Features
  $highlights = [
    ['icon' => 'ship', 'title' => __('Professional Captain'), 'desc' => __('Your trip is led by a licensed expert.')],
    ['icon' => 'shield-check', 'title' => __('Safety Guaranteed'), 'desc' => __('International standard life jackets and gear.')],
    ['icon' => 'fish', 'title' => __('Fishing Expert'), 'desc' => __('Equipped with the best sonar and tackle.')]
  ];
@endphp

@section('pageHeading')
{{ $roomContent->title }}
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 py-6" 
     x-data="{ 
        checkIn: '', 
        openPhotos: false,
        price: {{ $price }},
        guests: 1,
        maxGuests: {{ $room->adult ?? 10 }}
     }">
    
    {{-- Breadcrumbs & Top Actions --}}
    <div class="flex justify-between items-center mb-4 text-sm font-medium">
        <div class="flex items-center space-x-2">
            <a href="{{ route('frontend.perahu') }}" class="hover:underline">{{ __('Hotels/Perahu') }}</a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-900">{{ $roomContent->title }}</span>
        </div>
        <div class="flex items-center space-x-4">
            <button class="flex items-center hover:bg-gray-100 px-2 py-1 rounded-md transition border border-transparent">
                <i data-lucide="share" class="w-4 h-4 mr-2"></i> {{ __('Share') }}
            </button>
            <button class="flex items-center hover:bg-gray-100 px-2 py-1 rounded-md transition border border-transparent text-airbnb-red">
                <i data-lucide="heart" class="w-4 h-4 mr-2"></i> {{ __('Save') }}
            </button>
        </div>
    </div>

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-[26px] font-semibold text-gray-900 leading-tight">
            {{ $roomContent->title }}
        </h1>
        <div class="flex items-center mt-2 space-x-2 text-[14px]">
            <div class="flex items-center">
                <i data-lucide="star" class="w-4 h-4 text-black fill-current mr-1"></i>
                <span class="font-semibold">{{ $rating }}</span>
            </div>
            <span>·</span>
            <span class="underline font-semibold cursor-pointer">{{ count($reviews) }} {{ __('ulasan') }}</span>
            <span>·</span>
            <span class="underline font-semibold cursor-pointer">{{ $hotelContent->title ?? 'Jakarta Utara' }}</span>
        </div>
    </div>

    {{-- Image Gallery (Airbnb Grid) --}}
    <div class="relative grid grid-cols-4 gap-2 rounded-2xl overflow-hidden h-[450px] mb-10 group">
        {{-- Main Large Photo --}}
        <div class="col-span-2 row-span-2 relative cursor-pointer overflow-hidden">
            <img src="{{ $allImages[0] }}" class="w-full h-full object-cover group-hover:brightness-95 hover:brightness-90 transition duration-300 transform hover:scale-[1.02]">
        </div>
        {{-- 4 Supporting Photos --}}
        @php $galleryImages = array_slice($allImages, 1, 4); @endphp
        @foreach($galleryImages as $idx => $img)
            <div class="relative cursor-pointer overflow-hidden">
                <img src="{{ $img }}" class="w-full h-full object-cover group-hover:brightness-95 hover:brightness-90 transition duration-300 transform hover:scale-[1.02]">
            </div>
        @endforeach
        
        {{-- View All Photos Button --}}
        <button @click="openPhotos = true" class="absolute bottom-6 right-6 bg-white border border-gray-900 rounded-lg px-4 py-1.5 font-semibold text-sm shadow-sm hover:bg-gray-100 transition">
            <i data-lucide="grid" class="w-4 h-4 inline-block mr-2 -mt-0.5"></i>
            {{ __('Tampilkan semua foto') }}
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
        {{-- Main Left Side --}}
        <div class="lg:col-span-8">
            
            {{-- Title & Category Info --}}
            <div class="pb-6 border-b border-gray-200">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-[22px] font-semibold">
                            KM {{ $room->nama_km ?? $roomContent->title }} dikelola oleh {{ $room->kapten ?? ($room->vendor->username ?? 'Kapten Profesional') }}
                        </h2>
                        <ul class="flex flex-wrap gap-2 text-gray-700 mt-1">
                            <li>{{ $room->adult }} tamu</li>
                            <li>·</li>
                            <li>{{ $room->boat_length ?? '16' }}m panjang</li>
                            <li>·</li>
                            <li>{{ $room->crew_count ?? '3' }} kru</li>
                            <li>·</li>
                            <li>{{ $room->toilet_count ?? '1' }} toilet</li>
                        </ul>
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

            {{-- About / Description --}}
            <div class="py-10 border-b border-gray-200">
                <p class="text-[16px] text-gray-700 leading-relaxed overflow-hidden" 
                   style="display: -webkit-box; -webkit-line-clamp: 6; -webkit-box-orient: vertical;">
                   {!! replaceBaseUrl($roomContent->content, 'summernote') !!}
                </p>
                <button class="mt-4 font-semibold underline flex items-center">
                    {{ __('Tampilkan lebih banyak') }}
                    <i data-lucide="chevron-right" class="w-4 h-4 ml-1"></i>
                </button>
            </div>

            {{-- Amenities / Facilities --}}
            <div class="py-10 border-b border-gray-200">
                <h3 class="text-[22px] font-semibold mb-6">{{ __('Apa yang ditawarkan kapal ini') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4">
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

            {{-- Map Section --}}
            <div class="py-10 border-b border-gray-200">
                <h3 class="text-[22px] font-semibold mb-6">{{ __('Lokasi Keberangkatan') }}</h3>
                <p class="mb-5 text-gray-700 font-medium">{{ $roomContent->address ?? $hotelContent->address }}</p>
                <div class="w-full h-[400px] bg-gray-100 rounded-xl overflow-hidden border border-gray-200">
                    {{-- Google Map Placeholder --}}
                    @if($room->latitude && $room->longitude)
                        <iframe width="100%" height="100%" frameborder="0" style="border:0" 
                                src="https://www.google.com/maps/embed/v1/place?key={{ config('google.maps_api_key') }}&q={{ $room->latitude }},{{ $room->longitude }}" allowfullscreen></iframe>
                    @else
                        <div class="flex flex-col items-center justify-center h-full text-gray-400">
                            <i data-lucide="map" class="w-12 h-12 mb-2"></i>
                            <p>Map rendering unavailable</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Booking Card (Sticky Right Side) --}}
        <div class="lg:col-span-4 relative">
            <div class="sticky top-24">
                <div class="border border-gray-200 rounded-2xl p-6 shadow-xl bg-white">
                    <div class="flex justify-between items-end mb-6">
                        <div>
                            <span class="text-[22px] font-bold">{{ symbolPrice($price) }}</span>
                            <span class="text-gray-500 text-[16px] ml-1">/ hari</span>
                        </div>
                        <div class="flex items-center text-sm font-semibold mb-1">
                            <i data-lucide="star" class="w-3.5 h-3.5 fill-current text-black mr-1"></i>
                            <span>{{ $rating }}</span>
                            <span class="text-gray-400 font-normal ml-1">· {{ count($reviews) }} ulasan</span>
                        </div>
                    </div>

                    {{-- Form Booking Mockup --}}
                    <div class="border border-gray-400 rounded-xl overflow-hidden mb-4">
                        <div class="grid grid-cols-2 border-b border-gray-400">
                            <div class="p-3 border-r border-gray-400 cursor-pointer hover:bg-gray-50 transition" id="checkin-btn">
                                <label class="text-[10px] uppercase font-extrabold text-gray-900">CHECK-IN</label>
                                <div class="text-[14px] text-gray-600">Pilih tanggal</div>
                            </div>
                            <div class="p-3 cursor-pointer hover:bg-gray-50 transition">
                                <label class="text-[10px] uppercase font-extrabold text-gray-900">CHECK-OUT</label>
                                <div class="text-[14px] text-gray-600">Hanya 1 Hari</div>
                            </div>
                        </div>
                        <div class="p-3 cursor-pointer hover:bg-gray-50 transition relative" x-data="{ guestMenu: false }">
                            <label class="text-[10px] uppercase font-extrabold text-gray-900">GUESTS</label>
                            <div class="text-[14px] text-gray-600 flex justify-between items-center" @click="guestMenu = !guestMenu">
                                <span x-text="guests + ' tamu'">1 tamu</span>
                                <i data-lucide="chevron-down" class="w-4 h-4 transform transition" :class="guestMenu ? 'rotate-180' : ''"></i>
                            </div>
                            
                            {{-- Guest Dropdown --}}
                            <div x-show="guestMenu" @click.away="guestMenu = false" class="absolute top-full left-0 right-0 bg-white border border-gray-200 z-50 p-4 shadow-lg rounded-b-xl mt-1">
                                <div class="flex justify-between items-center">
                                    <div class="text-sm font-bold">Tamu</div>
                                    <div class="flex items-center gap-3">
                                        <button @click="if(guests > 1) guests--" class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:border-gray-900">-</button>
                                        <span x-text="guests">1</span>
                                        <button @click="if(guests < maxGuests) guests++" class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:border-gray-900">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button class="w-full bg-airbnb-red text-white py-3.5 rounded-xl font-bold text-[16px] shadow-sm hover:brightness-95 transition mt-2">
                        Pesan Sekarang
                    </button>

                    <div class="text-center mt-4 text-[14px] text-gray-500">
                        Anda belum akan dikenakan biaya
                    </div>

                    {{-- Price Breakdown --}}
                    <div class="mt-6 space-y-3 pb-6 border-b border-gray-200">
                        <div class="flex justify-between text-[16px] text-gray-800">
                            <span class="underline">{{ symbolPrice($price) }} x 1 hari</span>
                            <span>{{ symbolPrice($price) }}</span>
                        </div>
                        <div class="flex justify-between text-[16px] text-gray-800">
                            <span class="underline">Biaya Layanan Go Fishi</span>
                            <span>Rp 0</span>
                        </div>
                    </div>
                    
                    <div class="flex justify-between text-[18px] font-bold mt-6">
                        <span>Total</span>
                        <span>{{ symbolPrice($price) }}</span>
                    </div>
                </div>

                <div class="mt-8 border border-gray-200 rounded-xl p-6 flex flex-col items-center gap-4 text-center">
                    <div class="text-[18px] font-semibold">Tanya Kapten?</div>
                    <p class="text-[14px] text-gray-500">Punya pertanyaan khusus tentang spot pancing atau rute perjalanan?</p>
                    <button class="w-full border border-gray-900 py-2.5 rounded-xl font-semibold hover:bg-gray-100 transition">
                        Hubungi Vendor
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Full Screen Photo Modal (Alpine) --}}
    <div x-show="openPhotos" x-cloak class="fixed inset-0 z-[100] bg-white overflow-y-auto" x-transition>
        <div class="max-w-4xl mx-auto py-12 px-6">
            <button @click="openPhotos = false" class="fixed top-6 left-6 p-2 rounded-full hover:bg-gray-100 transition">
                <i data-lucide="chevron-left" class="w-8 h-8"></i>
            </button>
            <div class="space-y-6">
                @foreach($allImages as $img)
                    <img src="{{ $img }}" class="w-full h-auto rounded-xl">
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Flatpickr simple initialization
        flatpickr("#checkin-btn", {
            dateFormat: "Y-m-d",
            minDate: "today",
            defaultDate: "today"
        });
    });
</script>
@endsection
