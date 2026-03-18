@php
  if (!isset($room) || is_null($room)) {
      return;
  }

  // If $room is actually an instance of RoomContent, get the parent room model
  if ($room instanceof \App\Models\RoomContent) {
      $room = $room->room;
  }
  
  if (!$room) return;

  $roomImages = \App\Models\RoomImage::where('room_id', $room->id)->get();
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
    $allImages[] = 'https://picsum.photos/seed/' . $room->id . '/400/400';
  }

  // Get language safely
  $currentLang = isset($currentLanguageInfo) ? $currentLanguageInfo : get_lang();
  $langId = $currentLang ? $currentLang->id : 1;

  // Use relationship method for safety
  $hotelContent = null;
  if ($room->hotel) {
      $hotelContent = $room->hotel->hotel_contents()->where('language_id', $langId)->first();
  }
  
  $locationLabel = $hotelContent ? ($hotelContent->title . ', ' . ($room->hotel->hotel_contents()->where('language_id', $langId)->first()->city ?? 'Jakarta')) : ($room->hotelName ?? 'Jakarta Utara');
  
  // Use relationship method for room content
  $roomContent = $room->room_content()->where('language_id', $langId)->first();
  $categoryLabel = $roomContent ? $roomContent->room_category_name : 'Boat';
  $price = $room->price_day_1 ?? $room->min_price ?? 0;
  $slug = $roomContent ? $roomContent->slug : ($room->slug ?? 'perahu');
  $title = $roomContent ? $roomContent->title : ($room->title ?? 'Perahu');
  $rating = round($room->average_rating, 1);
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
    <div class="relative aspect-square rounded-xl overflow-hidden mb-3 bg-gray-100">
        <template x-for="(img, index) in images" :key="index">
            <img :src="img" 
                 x-show="currentIndex === index"
                 x-transition:enter="transition transform duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 absolute inset-0"
                 alt="{{ $title }}">
        </template>

        {{-- Favorite Button --}}
        <button @click="toggleFavorite" class="absolute top-3 right-3 p-2 hover:scale-110 transition z-10">
            <template x-if="isFavorite">
                <i data-lucide="heart" class="text-airbnb-red fill-airbnb-red w-6 h-6"></i>
            </template>
            <template x-if="!isFavorite">
                <i data-lucide="heart" class="text-white w-6 h-6 drop-shadow-lg"></i>
            </template>
        </button>

        {{-- Image Navigation --}}
        <template x-if="total > 1">
            <div class="absolute inset-0 flex items-center justify-between px-2 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                <button @click.prevent="prev" class="bg-white/80 hover:bg-white rounded-full p-1.5 shadow-md transition">
                    <i data-lucide="chevron-left" class="w-4 h-4 text-gray-700"></i>
                </button>
                <button @click.prevent="next" class="bg-white/80 hover:bg-white rounded-full p-1.5 shadow-md transition">
                    <i data-lucide="chevron-right" class="w-4 h-4 text-gray-700"></i>
                </button>
            </div>
        </template>

        {{-- Image Dots --}}
        <template x-if="total > 1">
            <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex space-x-1 z-10">
                <template x-for="(_, index) in images.slice(0, 5)" :key="index">
                    <div class="w-1.5 h-1.5 rounded-full transition-all duration-300"
                         :class="currentIndex === index ? 'bg-white scale-110' : 'bg-white/50'"></div>
                </template>
            </div>
        </template>
    </div>

    {{-- Listing Info --}}
    <div class="space-y-1">
        <div class="flex items-center justify-between">
            <h3 class="font-semibold text-gray-900 truncate">
                {{ $locationLabel }}
            </h3>
            <div class="flex items-center space-x-1">
                <i data-lucide="star" class="w-3 h-3 text-black fill-current"></i>
                <span class="text-sm font-medium">
                    {{ $rating > 0 ? number_format($rating, 1) : '4.5' }}
                </span>
            </div>
        </div>

        <p class="text-sm text-airbnb-gray truncate capitalize">
            KM {{ $room->nama_km ?? $title }} · {{ $room->boat_length ?? '16' }}m {{ __('panjang') }}
        </p>

        <p class="text-sm text-airbnb-gray">
            {{ $room->adult }} {{ __('tamu') }} · {{ $room->crew_count ?? '3' }} {{ __('kru') }} · {{ $room->toilet_count }} {{ __('toilet') }}
        </p>

        <div class="pt-1">
            <span class="font-semibold text-gray-900">{{ symbolPrice($price) }}</span>
            <span class="text-[15px] font-light text-gray-700"> hari</span>
        </div>
    </div>
</div>
