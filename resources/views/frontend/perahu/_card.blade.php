@php
  if (!isset($room) || is_null($room)) {
      return;
  }

  // If $room is actually an instance of RoomContent, get the parent room model
  if ($room instanceof \App\Models\RoomContent) {
      $room = $room->room;
  }
  
  if (!$room) return;

  // Get language safely and early
  $currentLang = isset($currentLanguageInfo) ? $currentLanguageInfo : get_lang();
  $langId = $currentLang ? $currentLang->id : 1;

  // Location is now eager-loaded in PerahuService
  $hotelContent = null;
  if ($room->hotel && $room->hotel->hotel_contents) {
      $hotelContent = $room->hotel->hotel_contents->where('language_id', $langId)->first();
  }
  
  $locationLabel = 'Jakarta Utara'; 
  if ($hotelContent) {
      $cityName = $hotelContent->city ? $hotelContent->city->name : 'Jakarta';
      $locationLabel = $hotelContent->title . ', ' . $cityName;
  }
  
  $roomContent = $room->room_content ? $room->room_content->where('language_id', $langId)->first() : null;
  $slug = $roomContent ? $roomContent->slug : ($room->slug ?? 'perahu');
  $title = $roomContent ? $roomContent->title : ($room->title ?? 'Perahu');
  $rating = round($room->average_rating, 1);

  $roomImages = $room->room_galleries ?? [];
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
    // Better maritime placeholders from Unsplash
    $placeholders = [
        'https://images.unsplash.com/photo-1544551763-47a0159f9234?auto=format&fit=crop&w=800&q=80', // Fishing boat
        'https://images.unsplash.com/photo-1567899832328-3c990cc67349?auto=format&fit=crop&w=800&q=80', // Yacht
        'https://images.unsplash.com/photo-1540962351504-03099e0a754b?auto=format&fit=crop&w=800&q=80', // Boat on tropical water
        'https://images.unsplash.com/photo-1559139225-3327fbc13714?auto=format&fit=crop&w=800&q=80', // Speedboat
    ];
    $allImages[] = $placeholders[$room->id % count($placeholders)];
  }
@endphp

{{-- ListingCard.tsx Benchmark Implementation --}}
<div class="cursor-pointer group" 
     onclick="window.location.href='{{ route('frontend.perahu.details', ['slug' => $slug, 'id' => $room->id]) }}'"
     x-data="{ 
        currentIndex: 0, 
        images: {{ json_encode($allImages) }},
        total: {{ count($allImages) }},
        isFavorite: false,
        next(e) { e.stopPropagation(); this.currentIndex = (this.currentIndex + 1) % this.total },
        prev(e) { e.stopPropagation(); this.currentIndex = (this.currentIndex - 1 + this.total) % this.total },
        toggleFavorite(e) { e.stopPropagation(); this.isFavorite = !this.isFavorite }
     }">
    
    {{-- Image Container --}}
    <div class="relative aspect-square rounded-2xl overflow-hidden mb-3 bg-gray-100 shadow-sm">
        <template x-for="(img, index) in images" :key="index">
            <img :src="img" 
                 x-show="currentIndex === index"
                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 absolute inset-0"
                 alt="{{ $title }}"
                 width="400" height="400">
        </template>

        {{-- Guest Choice Badge (Airbnb Style) --}}
        @if($rating >= 4.8 || $room->is_featured)
            <div class="absolute top-3 left-3 bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-full shadow-lg z-20 border border-gray-100">
                <span class="text-[12px] font-bold text-gray-900 tracking-tight">{{ __('Pilihan tamu') }}</span>
            </div>
        @endif

        {{-- Favorite Button --}}
        <button @click="toggleFavorite" class="absolute top-3 right-3 p-1.5 hover:scale-110 active:scale-95 transition-all z-20 group/heart">
            <template x-if="isFavorite">
                <i data-lucide="heart" class="text-airbnb-red fill-airbnb-red w-6 h-6 drop-shadow-md"></i>
            </template>
            <template x-if="!isFavorite">
                <i data-lucide="heart" class="text-white w-6 h-6 group-hover/heart:text-gray-200 transition-colors" style="filter: drop-shadow(0 2px 4px rgba(0,0,0,0.4));"></i>
            </template>
        </button>

        {{-- Image Navigation --}}
        <template x-if="total > 1">
            <div class="absolute inset-x-2 top-1/2 -translate-y-1/2 flex items-center justify-between opacity-0 group-hover:opacity-100 transition-all duration-300 z-20">
                <button @click.prevent="prev" class="w-8 h-8 bg-white/90 hover:bg-white rounded-full flex items-center justify-center shadow-lg transition transform active:scale-90">
                    <i data-lucide="chevron-left" class="w-4 h-4 text-gray-800"></i>
                </button>
                <button @click.prevent="next" class="w-8 h-8 bg-white/90 hover:bg-white rounded-full flex items-center justify-center shadow-lg transition transform active:scale-90">
                    <i data-lucide="chevron-right" class="w-4 h-4 text-gray-800"></i>
                </button>
            </div>
        </template>

        {{-- Image Dots (Airbnb style pagination) --}}
        <template x-if="total > 1">
            <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex items-center gap-1.5 z-20">
                <template x-for="(_, index) in images.slice(0, 5)" :key="index">
                    <div class="rounded-full transition-all duration-300"
                         :class="currentIndex === index ? 'w-1.5 h-1.5 bg-white' : 'w-1.5 h-1.5 bg-white/60'"></div>
                </template>
            </div>
        </template>
    </div>

    {{-- Listing Info (High Fidelity Alignment) --}}
    <div class="space-y-0.5 mt-1">
        <div class="flex flex-col gap-0.5">
            {{-- Name --}}
            <h3 class="font-bold text-[15px] text-gray-900 group-hover:text-airbnb-red transition-colors truncate">
                KM {{ $room->nama_km ?? $title }} di {{ $cityName ?? 'Jakarta' }}
            </h3>
            
            {{-- Price Line --}}
            <div class="flex items-center text-[15px] text-gray-900 leading-tight">
                <span class="font-bold">Rp {{ number_format($room->price, 0, ',', '.') }}</span>
                <span class="font-light text-gray-500 ml-1">untuk per trip</span>
            </div>

            {{-- Rating Line (Airbnb Style) --}}
            <div class="flex items-center gap-1 text-[13px] text-gray-900 mt-0.5">
                <i data-lucide="star" class="w-3.5 h-3.5 text-black fill-current"></i>
                <span class="font-semibold">{{ $rating > 0 ? number_format($rating, 1) : '5.0' }}</span>
                <span class="text-gray-400 font-light ml-0.5 text-[12px] uppercase tracking-wider">Top Rated</span>
            </div>
        </div>
    </div>
</div>
