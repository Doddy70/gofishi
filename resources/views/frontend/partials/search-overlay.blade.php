@php
    $lang = $currentLanguageInfo ?? get_lang();
    $webInfo = $websiteInfo ?? bs();
    
    // Data lokasi populer terinspirasi dari React
    $popularLocations = [
        ['city' => 'Marina Ancol', 'state' => 'Jakarta Utara', 'country' => 'Indonesia', 'icon' => '📍'],
        ['city' => 'Pantai Mutiara', 'state' => 'Penjaringan', 'country' => 'Indonesia', 'icon' => '📍'],
        ['city' => 'Muara Angke', 'state' => 'Jakarta Utara', 'country' => 'Indonesia', 'icon' => '📍'],
        ['city' => 'Kepulauan Seribu', 'state' => 'DKI Jakarta', 'country' => 'Indonesia', 'icon' => '📍'],
        ['city' => 'Tanjung Pasir', 'state' => 'Tangerang', 'country' => 'Indonesia', 'icon' => '📍'],
    ];
@endphp

<!-- Advanced Search Overlay (Perfect Port from React Implementation) -->
<div x-data="{ 
        activeField: 'location',
        location: '',
        dates: '',
        guests: { adults: 1, children: 0, infants: 0, pets: 0 },
        
        guestSummary() {
            let total = this.guests.adults + this.guests.children;
            if (total === 0) return '{{ __("Tambah tamu") }}';
            let parts = [];
            parts.push(`${total} {{ __("tamu") }}`);
            if (this.guests.infants > 0) parts.push(`${this.guests.infants} {{ __("bayi") }}`);
            if (this.guests.pets > 0) parts.push(`${this.guests.pets} {{ __("hewan") }}`);
            return parts.join(', ');
        },
        selectLoc(city, state) {
            this.location = `${city}, ${state}`;
            this.activeField = 'dates';
        }
     }" 
     @open-search-overlay.window="isExpanded = true; activeField = 'location'"
     x-cloak>

    <!-- Overlay Background (Dimmer) -->
    <div x-show="isExpanded" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="isExpanded = false" 
         class="fixed inset-0 bg-black/25 backdrop-blur-[2px] z-[60]"></div>

    <!-- Expansion Container -->
    <div x-show="isExpanded" 
         x-transition:enter="transition transform ease-out duration-300"
         x-transition:enter-start="-translate-y-full opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition transform ease-in duration-200"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="-translate-y-full opacity-0"
         class="fixed top-0 left-0 w-full bg-white z-[70] pb-12 shadow-2xl border-b border-neutral-100 overflow-visible">
        
        <div class="max-w-4xl mx-auto pt-6 px-4">
            {{-- Search Tabs --}}
            <div class="flex justify-center mb-6">
                <div class="inline-flex rounded-full bg-neutral-100 p-1 shadow-sm">
                    <button type="button" class="px-6 py-3 rounded-full text-sm font-semibold text-gray-900 bg-white shadow-sm">{{ __('Perahu') }}</button>
                    <button type="button" class="px-6 py-3 rounded-full text-sm font-medium text-gray-600 hover:text-gray-900 transition">{{ __('Pengalaman') }}</button>
                </div>
            </div>

            {{-- Segmented Search Bar (React Style) --}}
            <form action="{{ route('frontend.perahu') }}" method="GET" class="relative" id="advanced-search-form">
                <input type="hidden" name="checkInDates" id="hidden-overlay-dates" x-model="dates">
                <input type="hidden" name="adults" :value="guests.adults">
                <input type="hidden" name="children" :value="guests.children">
                
                <div class="bg-white rounded-full flex items-stretch border border-neutral-300 relative shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-visible">
                    
                    {{-- Where Segment --}}
                    <div @click="activeField = 'location'" 
                         class="flex-[1.5] flex flex-col justify-center px-8 py-3 rounded-full transition-all duration-200 cursor-pointer relative z-20"
                         :class="activeField === 'location' ? 'bg-white shadow-2xl scale-[1.03]' : 'hover:bg-neutral-100'">
                        <label class="block text-[11px] font-bold text-gray-900 mb-0.5">{{ __('Ke mana') }}</label>
                        <input type="text" x-model="location" placeholder="{{ __('Cari destinasi') }}" 
                               class="bg-transparent border-none p-0 focus:ring-0 text-sm w-full text-gray-600 placeholder-gray-400 font-light"
                               @focus="activeField = 'location'">
                    </div>

                    <div class="w-[1px] h-8 bg-neutral-200 self-center" x-show="activeField !== 'location' && activeField !== 'dates'"></div>

                    {{-- Dates Segment --}}
                    <div @click="activeField = 'dates'" 
                         class="flex-1 flex flex-col justify-center px-8 py-3 rounded-full transition-all duration-200 cursor-pointer relative z-20"
                         :class="activeField === 'dates' ? 'bg-white shadow-2xl scale-[1.03]' : 'hover:bg-neutral-100'">
                        <label class="block text-[11px] font-bold text-gray-900 mb-0.5">{{ __('Kapan') }}</label>
                        <span class="text-sm text-gray-500 truncate font-light" x-text="dates || '{{ __('Kapan saja') }}'"></span>
                    </div>

                    <div class="w-[1px] h-8 bg-neutral-200 self-center" x-show="activeField !== 'dates' && activeField !== 'guests'"></div>

                    {{-- Who Segment --}}
                    <div @click="activeField = 'guests'" 
                         class="flex-1.2 flex flex-col justify-center pl-8 pr-2 py-2 rounded-full transition-all duration-200 cursor-pointer relative z-20"
                         :class="activeField === 'guests' ? 'bg-white shadow-2xl scale-[1.03]' : 'hover:bg-neutral-100'">
                        <div class="flex flex-row items-center justify-between">
                            <div class="flex flex-col overflow-hidden">
                                <label class="block text-[11px] font-bold text-gray-900 mb-0.5">{{ __('Siapa') }}</label>
                                <span class="text-sm text-gray-500 truncate font-light" x-text="guestSummary()"></span>
                            </div>
                            <button type="submit" class="bg-[#FF385C] text-white p-3.5 rounded-full flex items-center gap-2 hover:bg-[#E31C5F] transition shadow-md ml-2 active:scale-95">
                                <i class="fas fa-search text-sm"></i>
                                <span class="hidden lg:block font-bold text-sm">{{ __('Cari') }}</span>
                            </button>
                        </div>
                    </div>

                    {{-- REACT-STYLE POPOVERS (Perfectly Positioned) --}}
                    
                    {{-- Location Popover --}}
                    <div x-show="activeField === 'location'" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute top-full left-0 mt-3 bg-white rounded-[32px] shadow-2xl border border-neutral-200 py-6 z-[80] w-[450px]">
                        <h3 class="px-8 text-[11px] font-extrabold text-gray-500 mb-4 uppercase tracking-widest">{{ __('Destinasi Populer') }}</h3>
                        <div class="max-h-[50vh] overflow-y-auto scrollbar-hide">
                            @foreach($popularLocations as $loc)
                            <button type="button" @click.stop="selectLoc('{{ $loc['city'] }}', '{{ $loc['state'] }}')" 
                                    class="w-full px-8 py-3.5 hover:bg-neutral-50 flex items-center gap-4 transition group text-left">
                                <div class="w-12 h-12 bg-neutral-100 rounded-xl flex items-center justify-center group-hover:bg-white transition shadow-sm border border-neutral-100">
                                    <span class="text-2xl">{{ $loc['icon'] }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-gray-900">{{ $loc['city'] }}</span>
                                    <span class="text-xs text-gray-500">{{ $loc['state'] }}, {{ $loc['country'] }}</span>
                                </div>
                            </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Calendar Popover --}}
                    <div x-show="activeField === 'dates'" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         @click.stop 
                         class="absolute top-full left-1/2 -translate-x-1/2 mt-3 bg-white rounded-[32px] shadow-2xl border border-neutral-200 p-8 z-[80] w-[850px]">
                        <div class="airbnb-calendar-container" id="search-overlay-calendar"></div>
                        <div class="flex justify-between items-center mt-6 pt-4 border-t border-neutral-100">
                            <button type="button" @click="dates = ''; $dispatch('clear-calendar')" class="text-sm font-bold underline hover:text-neutral-600 transition">{{ __('Hapus Tanggal') }}</button>
                            <button type="button" @click="activeField = 'guests'" class="bg-gray-900 text-white px-8 py-3 rounded-xl font-bold text-sm hover:bg-black transition shadow-lg active:scale-95">{{ __('Simpan & Lanjut') }}</button>
                        </div>
                    </div>

                    {{-- Guest Popover (React UI Clone) --}}
                    <div x-show="activeField === 'guests'" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute top-full right-0 mt-3 bg-white rounded-[32px] shadow-2xl border border-neutral-200 p-8 w-[400px] z-[80]">
                        
                        @php
                            $guestTypes = [
                                ['id' => 'adults', 'title' => 'Dewasa', 'desc' => 'Usia 13 atau lebih', 'min' => 1],
                                ['id' => 'children', 'title' => 'Anak-anak', 'desc' => 'Usia 2-12', 'min' => 0],
                                ['id' => 'infants', 'title' => 'Bayi', 'desc' => 'Di bawah 2', 'min' => 0],
                                ['id' => 'pets', 'title' => 'Hewan', 'desc' => 'Membawa hewan pemandu?', 'min' => 0]
                            ];
                        @endphp

                        <div class="flex flex-col divide-y divide-neutral-100">
                            @foreach($guestTypes as $type)
                            <div class="flex items-center justify-between py-4 first:pt-0 last:pb-0">
                                <div class="flex flex-col">
                                    <span class="font-bold text-sm text-gray-900">{{ __($type['title']) }}</span>
                                    <span class="text-xs text-gray-500 font-light">{{ __($type['desc']) }}</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <button type="button" 
                                            @click.stop="guests.{{ $type['id'] }} = Math.max({{ $type['min'] }}, guests.{{ $type['id'] }} - 1)" 
                                            class="w-8 h-8 rounded-full border border-neutral-300 flex items-center justify-center hover:border-gray-900 transition-colors text-lg font-light text-gray-600 disabled:opacity-20 disabled:cursor-not-allowed"
                                            :disabled="guests.{{ $type['id'] }} <= {{ $type['min'] }}">−</button>
                                    <span x-text="guests.{{ $type['id'] }}" class="w-6 text-center font-medium text-sm text-gray-900"></span>
                                    <button type="button" @click.stop="guests.{{ $type['id'] }}++" 
                                            class="w-8 h-8 rounded-full border border-neutral-300 flex items-center justify-center hover:border-gray-900 transition-colors text-lg font-light text-gray-600">+</button>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-6 pt-4 border-t border-neutral-100">
                            <button type="button" @click="activeField = null; isExpanded = false" 
                                    class="w-full bg-gray-900 text-white px-6 py-3 rounded-xl text-sm font-bold hover:bg-black transition active:scale-95 shadow-md">
                                {{ __('Tutup') }}
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    [x-cloak] { display: none !important; }
    #advanced-search-form input::placeholder { color: #A0A0A0; font-weight: 300; }
</style>
