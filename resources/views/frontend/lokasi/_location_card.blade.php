@php
    if (!isset($hotel) || is_null($hotel)) {
        return;
    }

    $lang = $currentLanguageInfo ?? get_lang();
    $hotelContent = $hotel->hotel_contents()->where('language_id', $lang->id)->first();
    
    if (!$hotelContent) {
        $hotelContent = $hotel->hotel_contents()->first();
    }

    $title = $hotelContent ? $hotelContent->title : 'Dermaga';
    $slug = $hotelContent ? $hotelContent->slug : ($hotel->slug ?? 'dermaga');
    $image = $hotel->logo ? asset('assets/img/hotel/logo/' . $hotel->logo) : 'https://picsum.photos/seed/dermaga' . $hotel->id . '/400/400';
    $rating = round($hotel->average_rating ?? 4.5, 1);
    $totalPerahu = \App\Models\Perahu::where('hotel_id', $hotel->id)->count();
    
    $city = null;
    if ($hotelContent && $hotelContent->city_id) {
        $city = \App\Models\Location\City::find($hotelContent->city_id)->name ?? '';
    }
@endphp

<div class="cursor-pointer group" 
     onclick="window.location.href='{{ route('frontend.lokasi.details', ['slug' => $slug, 'id' => optional($hotel)->id]) }}'">
    
    {{-- Image Container --}}
    <div class="relative aspect-square rounded-xl overflow-hidden mb-3 bg-gray-100">
        <img src="{{ $image }}" 
             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
             alt="{{ $title }}">
        
        {{-- Total Armada Badge --}}
        <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full shadow-sm">
            <span class="text-[10px] font-bold text-gray-900 uppercase tracking-wider">{{ $totalPerahu }} Armada</span>
        </div>
    </div>

    {{-- Info --}}
    <div class="space-y-1">
        <div class="flex items-center justify-between">
            <h3 class="font-bold text-gray-900 truncate">
                {{ $title }}
            </h3>
            <div class="flex items-center space-x-1">
                <i data-lucide="star" class="w-3 h-3 text-black fill-current"></i>
                <span class="text-sm font-medium">{{ $rating }}</span>
            </div>
        </div>

        <p class="text-sm text-gray-500 font-light truncate">
            <i data-lucide="map-pin" class="w-3 h-3 inline mr-1"></i>
            {{ $city ?? 'Jakarta' }}
        </p>

        <p class="text-[13px] text-gray-500 mt-2 line-clamp-1 italic">
            {{ __('Dermaga keberangkatan utama untuk area ini.') }}
        </p>
    </div>
</div>
