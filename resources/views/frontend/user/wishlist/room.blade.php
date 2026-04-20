@extends('frontend.layout-airbnb')

@section('pageHeading')
  {{ !empty($pageHeading) ? $pageHeading->room_wishlist_page_title : __('Saved Perahu') }}
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="mb-8">
      <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ __('My Favorites') }}</h1>
      <p class="text-gray-600">
        @if(count($wishlists) > 0)
          {{ __('You have') }} {{ count($wishlists) }} {{ __('favorite properties') }}
        @else
          {{ __('You haven\'t saved any favorites yet') }}
        @endif
      </p>
    </div>

    {{-- Favorites Grid --}}
    @if(count($wishlists) == 0)
      <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-16 text-center">
        <div class="w-20 h-20 bg-rose-50 rounded-full flex items-center justify-center mx-auto mb-6 text-rose-500">
            <i data-lucide="heart" class="w-10 h-10"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ __('No favorites yet') }}</h2>
        <p class="text-gray-500 font-light mb-8 max-w-sm mx-auto">
          {{ __('Start exploring and save your favorite properties by clicking the heart icon.') }}
        </p>
        <a href="{{ route('frontend.perahu') }}" 
           class="inline-block px-8 py-3 bg-airbnb-red text-white rounded-xl font-bold hover:bg-rose-600 transition shadow-md active:scale-95">
          {{ __('Explore Boats') }}
        </a>
      </div>
    @else
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @foreach($wishlists as $item)
            @php
                // Wrap in a room-like object for the card partial if possible, 
                // but here we might need to fetch the actual Room model or manually structure it.
                $room = \App\Models\Perahu::find($item->room_id);
            @endphp
            @if($room)
                @include('frontend.perahu._card', ['room' => $room])
            @endif
        @endforeach
      </div>
    @endif

    {{-- Tips Section --}}
    @if(count($wishlists) > 0)
      <div class="mt-12 bg-blue-50 border border-blue-100 rounded-2xl p-8 flex items-start gap-4">
        <div class="text-2xl text-blue-500 mt-1">💡</div>
        <div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ __('Pro Tip') }}</h3>
            <p class="text-gray-600 font-light leading-relaxed">
              {{ __('Properties in your favorites list may have limited availability. Book your favorites soon to secure your preferred dates!') }}
            </p>
        </div>
      </div>
    @endif
  </div>
</div>
@endsection
