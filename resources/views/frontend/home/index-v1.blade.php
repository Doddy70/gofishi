@extends('frontend.layout-airbnb')

@section('pageHeading')
  {{ __('Home') }}
@endsection

@section('content')
@php
    $categories = App\Models\RoomCategory::where('language_id', $currentLanguageInfo->id)->where('status', 1)->get();
@endphp

<div class="bg-white">
    {{-- 1. Hero Section (Clean Gradient) --}}
    <div class="bg-gradient-to-b from-gray-50 to-white py-12 border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-2 tracking-tight">
                {{ __('Discover Your Perfect Getaway') }}
            </h1>
            <p class="text-gray-600 mb-10 text-lg">
                {{ __('Explore unique stays and experiences around the world') }}
            </p>

            {{-- The Static Search Bar --}}
            <div class="w-full max-w-4xl mx-auto mb-8 relative">
                <div class="bg-white rounded-full shadow-lg border border-gray-200 hover:shadow-xl transition-shadow flex items-stretch divide-x divide-gray-200 h-[66px] relative overflow-visible">
                    
                    {{-- Where Segment --}}
                    <div class="flex-1 flex flex-col justify-center px-8 hover:bg-gray-100 rounded-l-full cursor-pointer transition relative" 
                         @click="activeField = 'location'">
                        <label class="block text-[10px] font-bold text-gray-900 mb-0.5 uppercase tracking-wider">{{ __('Where') }}</label>
                        <input type="text" x-model="location" placeholder="{{ __('Search destinations') }}" class="bg-transparent border-none p-0 focus:ring-0 text-sm text-gray-600 placeholder-gray-400 w-full">
                        
                        {{-- Dropdown Location --}}
                        <div x-show="activeField === 'location'" @click.away="activeField = null" x-cloak
                             class="absolute top-full mt-3 left-0 bg-white rounded-3xl shadow-2xl border border-gray-200 py-4 z-[100] w-96">
                            <div class="px-6 pb-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('Popular destinations') }}</div>
                            <div class="px-6 py-3 hover:bg-gray-100 cursor-pointer flex items-center gap-4" @click.stop="location = 'Marina Ancol, Jakarta'; activeField = 'dates'">
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">📍</div>
                                <span class="text-sm font-medium text-gray-900">Marina Ancol, Jakarta</span>
                            </div>
                        </div>
                    </div>

                    {{-- Dates Segment --}}
                    <div class="flex-1 flex divide-x divide-gray-200 relative">
                        <div class="flex-1 px-8 flex flex-col justify-center hover:bg-gray-100 transition cursor-pointer" @click="activeField = 'dates'">
                            <label class="block text-[10px] font-bold text-gray-900 mb-0.5 uppercase tracking-wider">{{ __('Check in') }}</label>
                            <span class="text-sm text-gray-400 truncate" x-text="dates.split(' - ')[0] || '{{ __('Add dates') }}'"></span>
                        </div>
                        <div class="flex-1 px-8 flex flex-col justify-center hover:bg-gray-100 transition cursor-pointer" @click="activeField = 'dates'">
                            <label class="block text-[10px] font-bold text-gray-900 mb-0.5 uppercase tracking-wider">{{ __('Check out') }}</label>
                            <span class="text-sm text-gray-400 truncate" x-text="dates.split(' - ')[1] || '{{ __('Add dates') }}'"></span>
                        </div>

                        {{-- Calendar Dropdown --}}
                        <div x-show="activeField === 'dates'" @click.away="activeField = null" x-cloak
                             class="absolute top-full mt-3 left-1/2 -translate-x-1/2 bg-white rounded-3xl shadow-2xl p-6 border border-gray-100 w-[850px] z-[100]">
                            <div id="home-calendar-final-cleanup"></div>
                        </div>
                    </div>

                    {{-- Who Segment --}}
                    <div class="flex-1 flex items-center justify-between px-8 hover:bg-gray-100 rounded-r-full cursor-pointer transition relative" 
                         @click="activeField = 'guests'">
                        <div class="flex flex-col justify-center overflow-hidden">
                            <label class="block text-[10px] font-bold text-gray-900 mb-0.5 uppercase tracking-wider">{{ __('Who') }}</label>
                            <span class="text-sm text-gray-400 truncate" x-text="guestSummary()"></span>
                        </div>
                        <button type="button" class="bg-[#FF385C] text-white rounded-full p-3.5 hover:bg-red-600 transition shadow-md ml-2 flex-shrink-0">
                            <i class="fas fa-search text-lg"></i>
                        </button>

                        {{-- Guests Dropdown --}}
                        <div x-show="activeField === 'guests'" @click.away="activeField = null" x-cloak
                             class="absolute top-full mt-3 right-0 bg-white rounded-3xl shadow-2xl border border-gray-200 p-8 z-[100] w-80">
                            <div class="flex items-center justify-between py-4 border-b border-gray-100">
                                <div><p class="text-sm font-bold text-gray-900">{{ __('Adults') }}</p><p class="text-xs text-gray-400">{{ __('Ages 13+') }}</p></div>
                                <div class="flex items-center gap-3">
                                    <button type="button" @click.stop="guests.adults = Math.max(1, guests.adults - 1)" class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:border-black transition">－</button>
                                    <span x-text="guests.adults" class="text-sm font-bold w-4 text-center"></span>
                                    <button type="button" @click.stop="guests.adults++" class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:border-black transition">＋</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Categories (Sticky) --}}
    <div class="border-b border-gray-200 sticky top-20 bg-white z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-center space-x-10 overflow-x-auto scrollbar-hide">
                @foreach($categories as $category)
                    <a href="#" class="flex flex-col items-center gap-2 transition min-w-fit border-b-2 border-transparent hover:border-gray-200 pb-3 group">
                        <span class="text-2xl grayscale group-hover:grayscale-0 transition opacity-60">🚢</span>
                        <span class="text-xs font-bold text-gray-500 group-hover:text-gray-900">{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- 3. Listings Grid --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-6 gap-y-10">
            @php $rooms = \App\Models\Perahu::where('status', 1)->limit(12)->get(); @endphp
            @foreach($rooms as $room)
                @include('frontend.perahu._card', ['room' => $room])
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof flatpickr !== 'undefined') {
        flatpickr("#home-calendar-final-cleanup", {
            mode: "range", inline: true, showMonths: 2, minDate: "today", dateFormat: "Y-m-d",
            onChange: function(selectedDates, dateStr) {
                if (selectedDates.length === 2) {
                    const start = selectedDates[0].toLocaleDateString('id-ID', { month: 'short', day: 'numeric' });
                    const end = selectedDates[1].toLocaleDateString('id-ID', { month: 'short', day: 'numeric' });
                    
                    // Access status Alpine dari layout
                    const bodyData = document.querySelector('body').__x.$data;
                    bodyData.dates = start + " - " + end;
                }
            }
        });
    }
});
</script>
@endsection
