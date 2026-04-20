@php
    $locations = [
        ['city' => 'Marina Ancol', 'state' => 'Jakarta Utara', 'country' => 'Indonesia', 'type' => 'marina'],
        ['city' => 'Pantai Mutiara', 'state' => 'Penjaringan', 'country' => 'Indonesia', 'type' => 'marina'],
        ['city' => 'Muara Angke', 'state' => 'Jakarta Utara', 'country' => 'Indonesia', 'type' => 'port'],
        ['city' => 'Kepulauan Seribu', 'state' => 'DKI Jakarta', 'country' => 'Indonesia', 'type' => 'island'],
        ['city' => 'Tanjung Pasir', 'state' => 'Tangerang', 'country' => 'Indonesia', 'type' => 'beach'],
    ];

    $basicSettings = \App\Models\BasicSettings\Basic::select('google_gemini_status')->first();
    $aiStatus = ($basicSettings && $basicSettings->google_gemini_status == 1) || (env('GEMINI_API_KEY') && env('GEMINI_API_KEY') !== 'AIzaSyCV6ltmLwphJY07EZI8HOFRqLqZwSv8ghs');
@endphp

<style>
    /* Airbnb DLS (Design Language System) Implementation */
    :root {
        --airbnb-red: #FF385C;
        --airbnb-shadow-pill: 0 3px 12px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.08);
        --airbnb-shadow-modal: 0 12px 44px rgba(0,0,0,0.2);
        --airbnb-bg-hover: #F7F7F7;
        --airbnb-grey-light: #EBEBEB;
    }

    /* Selective Focus Removal (Airbnb Precision) */
    .hero-search-container input:focus, 
    .hero-search-container button:focus,
    .hero-search-container .flatpickr-day:focus {
        outline: none !important;
        box-shadow: none !important;
    }

    /* Flatpickr Airbnb Perfection */
    .airbnb-calendar-wrapper .flatpickr-calendar {
        box-shadow: none !important;
        border: none !important;
        background: transparent !important;
        width: 100% !important;
        max-width: none !important;
        padding: 0 !important;
    }
    .airbnb-calendar-wrapper .flatpickr-innerContainer {
        justify-content: center !important;
        border: none !important;
    }
    .airbnb-calendar-wrapper .flatpickr-rContainer {
        width: 100% !important;
    }
    .airbnb-calendar-wrapper .flatpickr-weekdays {
        gap: 40px !important;
        justify-content: center !important;
        display: flex !important;
    }
    .airbnb-calendar-wrapper .flatpickr-months {
        margin-bottom: 20px !important;
        border: none !important;
        gap: 40px !important;
        justify-content: center !important;
    }
    .airbnb-calendar-wrapper .flatpickr-month {
        color: #222222 !important;
        height: 60px !important;
        border: none !important;
        min-width: 320px !important;
    }
    .airbnb-calendar-wrapper .flatpickr-current-month {
        font-size: 16px !important;
        font-weight: 800 !important;
        padding-top: 15px !important;
    }
    .airbnb-calendar-wrapper .flatpickr-monthNav {
        height: 60px !important;
        border: none !important;
    }
    .airbnb-calendar-wrapper .flatpickr-days {
        gap: 40px !important;
        justify-content: center !important;
        display: flex !important;
        min-width: auto !important;
        width: auto !important;
    }
    .airbnb-calendar-wrapper .dayContainer, 
    .airbnb-calendar-wrapper .flatpickr-weekdaycontainer,
    .airbnb-calendar-wrapper .flatpickr-month {
        min-width: 320px !important;
        width: 320px !important;
        justify-content: center !important;
    }
    .airbnb-calendar-wrapper .flatpickr-weekday {
        font-weight: 800 !important;
        color: #222222 !important;
        font-size: 11px !important;
        text-transform: none !important;
        padding-bottom: 10px !important;
    }
    .airbnb-calendar-wrapper .flatpickr-day {
        font-weight: 600 !important;
        border-radius: 50% !important;
        height: 45px !important;
        line-height: 45px !important;
        width: 45px !important;
        max-width: 45px !important;
        font-size: 14px !important;
        border: 2px solid transparent !important;
        margin: 2px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    .airbnb-calendar-wrapper .flatpickr-day.selected, 
    .airbnb-calendar-wrapper .flatpickr-day.startRange, 
    .airbnb-calendar-wrapper .flatpickr-day.endRange {
        background: #222222 !important;
        border-color: #222222 !important;
        color: #fff !important;
    }
    .airbnb-calendar-wrapper .flatpickr-day.inRange {
        background: var(--airbnb-bg-hover) !important;
        border-color: var(--airbnb-bg-hover) !important;
        color: #222222 !important;
        border-radius: 0 !important;
    }
    .airbnb-calendar-wrapper .flatpickr-day.prevMonthDay, 
    .airbnb-calendar-wrapper .flatpickr-day.nextMonthDay {
        color: #DDDDDD !important;
    }
</style>

<div x-data="{ 
        searchMode: 'classic', 
        activeField: null,
        isSelecting: false,
        calendarTab: 'dates', 
        flexibleLength: 'weekend',
        flexibleMonth: 'july',
        dateTolerance: 'exact',
        toleranceDropdownOpen: false,
        fpInstance: null,
        location: '',
        allLocations: {{ json_encode($locations) }},
        filteredLocations: [],
        checkIn: '',
        checkOut: '',
        adults: 1,
        children: 0,
        
        get totalGuests() { return this.adults + this.children },
        get guestText() {
            if (this.totalGuests === 0) return '{{ __('Tambahkan tamu') }}';
            return `${this.totalGuests} {{ __('tamu') }}`;
        },
        
        init() {
            this.filteredLocations = this.allLocations;
            this.$watch('location', value => {
                if (this.isSelecting) return;
                let term = (value || '').toLowerCase().trim();
                if (term) {
                    this.filteredLocations = this.allLocations.filter(loc => 
                        loc.city.toLowerCase().includes(term) || 
                        loc.state.toLowerCase().includes(term)
                    );
                } else {
                    this.filteredLocations = this.allLocations;
                }
            });
            
            this.$nextTick(() => {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        },
        
        initCalendar() {
            if (!this.fpInstance && this.$refs.datepicker && typeof window !== 'undefined') {
                this.fpInstance = flatpickr(this.$refs.datepicker, {
                    mode: 'range',
                    minDate: 'today',
                    showMonths: window.innerWidth > 991 ? 2 : 1,
                    inline: true,
                    dateFormat: 'Y-m-d',
                    monthSelectorType: 'static',
                    nextArrow: '<i class=\'fas fa-chevron-right\'></i>',
                    prevArrow: '<i class=\'fas fa-chevron-left\'></i>',
                    locale: {
                        firstDayOfWeek: 0,
                        weekdays: {
                            shorthand: ['Min', 'Sn', 'Sl', 'R', 'Km', 'J', 'Sb'],
                            longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']
                        }
                    },
                    onChange: (selectedDates, dateStr, instance) => {
                        if (selectedDates.length >= 1) {
                            this.checkIn = instance.formatDate(selectedDates[0], 'Y-m-d');
                            this.checkOut = '';
                        }
                        if (selectedDates.length === 2) {
                            this.checkOut = instance.formatDate(selectedDates[1], 'Y-m-d');
                            setTimeout(() => {
                                if (this.activeField === 'dates') this.activeField = 'guests';
                            }, 350);
                        }
                    }
                });
            }
        },
        
        openDates() {
            this.activeField = 'dates';
            this.$nextTick(() => { this.initCalendar(); });
        },

        selectLocation(loc) {
            this.isSelecting = true;
            this.location = loc.city;
            this.activeField = 'dates';
            this.$nextTick(() => { 
                this.isSelecting = false;
                this.initCalendar();
            });
        }
     }" 
     class="w-full max-w-[850px] mx-auto mb-12 relative hero-search-container"
     style="z-index: 30;"
     @click.away="activeField = null">

    {{-- Search Type (Tab Style Airbnb) --}}
    <div class="flex items-center justify-center gap-10 mb-8 relative z-20">
        <button @click="searchMode = 'classic'; activeField = null"
                class="text-[16px] font-semibold transition-all pb-1 relative group"
                :class="searchMode === 'classic' ? 'text-gray-900 font-bold' : 'text-gray-500 hover:text-gray-700'">
            {{ __('Sewa Perahu') }}
            <div class="absolute -bottom-1 left-0 right-0 h-0.5 bg-gray-900 rounded-full" x-show="searchMode === 'classic'"></div>
        </button>
        <button @click="searchMode = 'ai'; activeField = null; $nextTick(() => $refs.aiInput.focus())"
                class="text-[16px] font-semibold transition-all pb-1 relative group flex items-center gap-2"
                :class="searchMode === 'ai' ? 'text-gray-900 font-bold' : 'text-gray-500 hover:text-gray-700'">
            <i data-lucide="sparkles" class="w-4 h-4 text-rose-500"></i> {{ __('Smart Search') }}
            <div class="absolute -bottom-1 left-0 right-0 h-0.5 bg-gray-900 rounded-full" x-show="searchMode === 'ai'"></div>
        </button>
    </div>

    {{-- Pill Search Bar (Airbnb Genuine Style) --}}
    <div x-show="searchMode === 'classic'" 
         class="bg-white rounded-full border border-gray-200 transition-all duration-300 relative z-20"
         style="box-shadow: var(--airbnb-shadow-pill);"
         :class="activeField !== null ? 'bg-[#F7F7F7] border-transparent shadow-[0_16px_32px_rgba(0,0,0,0.1)]' : 'hover:shadow-[0_6px_20px_rgba(0,0,0,0.1)]'">
        
        <div class="flex items-center h-[66px] relative">
            
            {{-- Part 1: Lokasi --}}
            <div class="flex-[1.5] h-full relative" 
                 :class="activeField === 'location' ? 'bg-white rounded-full shadow-[0_8px_32px_rgba(0,0,0,0.1)] z-30' : 'hover:bg-[#F7F7F7] rounded-full transition-colors duration-200 cursor-pointer'">
                <div class="pl-8 pr-4 flex flex-col justify-center h-full"
                     @click="activeField = 'location'; $nextTick(() => $refs.locInput.focus())">
                    <span class="text-[11px] font-extrabold text-gray-900 uppercase tracking-wide mb-0.5">{{ __('Lokasi') }}</span>
                    <input x-ref="locInput" type="text" x-model="location" 
                           placeholder="{{ __('Cari destinasi') }}" 
                           class="w-full bg-transparent border-none p-0 focus:ring-0 text-[14px] text-gray-900 placeholder-gray-500 font-normal truncate">
                </div>
            </div>

            {{-- Divider --}}
            <div class="w-[1px] h-8 bg-gray-200" x-show="activeField !== 'location' && activeField !== 'dates'"></div>

            {{-- Part 2: Kapan --}}
            <div class="flex-1 h-full relative"
                 :class="activeField === 'dates' ? 'bg-white rounded-full shadow-[0_8px_32px_rgba(0,0,0,0.1)] z-30' : 'hover:bg-[#F7F7F7] rounded-full transition-colors duration-200 cursor-pointer'">
                <div class="pl-6 pr-4 flex flex-col justify-center h-full"
                     @click="openDates()">
                    <span class="text-[11px] font-extrabold text-gray-900 uppercase tracking-wide mb-0.5">{{ __('Kapan') }}</span>
                    <span class="text-[14px] truncate" :class="checkIn ? 'text-gray-900 font-bold' : 'text-gray-500 font-normal'">
                        <span x-text="checkIn ? (checkIn + (checkOut ? ' - ' + checkOut : '')) : '{{ __('Tambahkan tanggal') }}'"></span>
                    </span>
                </div>
            </div>

            {{-- Divider --}}
            <div class="w-[1px] h-8 bg-gray-200" x-show="activeField !== 'dates' && activeField !== 'guests'"></div>

            {{-- Part 3: Peserta --}}
            <div class="flex-[1.2] h-full relative"
                 :class="activeField === 'guests' ? 'bg-white rounded-full shadow-[0_8px_32px_rgba(0,0,0,0.1)] z-30' : 'hover:bg-[#F7F7F7] rounded-full transition-colors duration-200 cursor-pointer'">
                <div class="pl-6 pr-2 flex items-center justify-between h-full rounded-full">
                    <div class="flex flex-col justify-center flex-1 pr-2 truncate"
                         @click="activeField = 'guests'">
                        <span class="text-[11px] font-extrabold text-gray-900 uppercase tracking-wide mb-0.5">{{ __('Peserta') }}</span>
                        <span class="text-[14px] truncate" :class="totalGuests > 1 ? 'text-gray-900 font-bold' : 'text-gray-500 font-normal'" x-text="guestText"></span>
                    </div>

                    {{-- Search Icon/Button (Genuine Airbnb Red) --}}
                    <button @click.stop="document.getElementById('hero-search-form').submit()" 
                            class="text-white rounded-full hover:brightness-110 flex items-center justify-center shrink-0 transition-all duration-300 shadow-sm"
                            style="background-color: var(--airbnb-red);"
                            :class="activeField !== null ? 'h-12 px-6 gap-2' : 'h-12 w-12'">
                        <i data-lucide="search" class="w-5 h-5"></i>
                        <span class="font-bold text-[14px] whitespace-nowrap" x-show="activeField !== null">{{ __('Cari') }}</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Location Menu --}}
        <div x-show="activeField === 'location'" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="absolute top-full mt-3 left-1/2 -translate-x-1/2 bg-white rounded-[32px] shadow-[0_12px_44px_rgba(0,0,0,0.12)] border border-gray-100 py-6 z-50 w-[95%] sm:w-[480px] sm:left-0 sm:translate-x-0">
            <div class="px-8 pb-4">
                <h3 class="text-xs font-bold text-gray-400 tracking-wider uppercase">{{ __('Destinasi yang disarankan') }}</h3>
            </div>
            <div class="max-h-[400px] overflow-y-auto no-scrollbar">
                <template x-for="(loc, index) in filteredLocations" :key="index">
                    <button @click="selectLocation(loc)" 
                            class="w-full px-8 py-4 hover:bg-gray-50 flex items-center gap-4 group transition-colors">
                        <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center border border-gray-50 group-hover:bg-white group-hover:shadow-sm transition-all duration-300">
                            <template x-if="loc.type === 'marina'"><i data-lucide="anchor" class="w-5 h-5 text-gray-500 group-hover:text-rose-500"></i></template>
                            <template x-if="loc.type === 'port'"><i data-lucide="ship" class="w-5 h-5 text-gray-500 group-hover:text-rose-500"></i></template>
                            <template x-if="loc.type === 'island'"><i data-lucide="palmtree" class="w-5 h-5 text-gray-500 group-hover:text-rose-500"></i></template>
                            <template x-if="loc.type === 'beach'"><i data-lucide="waves" class="w-5 h-5 text-gray-500 group-hover:text-rose-500"></i></template>
                        </div>
                        <div class="text-left">
                            <p class="text-[15px] font-bold text-gray-900" x-text="loc.city"></p>
                            <p class="text-[13px] text-gray-400 font-light" x-text="`${loc.state}, ${loc.country}`"></p>
                        </div>
                    </button>
                </template>
            </div>
        </div>

        {{-- Guests Popover --}}
        <div x-show="activeField === 'guests'" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="absolute top-full mt-3 left-1/2 -translate-x-1/2 bg-white rounded-[32px] shadow-[0_12px_44px_rgba(0,0,0,0.12)] border border-gray-100 py-4 px-8 z-50 w-[95%] sm:w-[420px] sm:left-auto sm:right-0 sm:translate-x-0">
            
            <div class="flex items-center justify-between py-6 border-b border-gray-100">
                <div class="flex flex-col">
                    <span class="text-[16px] font-bold text-gray-900">{{ __('Dewasa') }}</span>
                    <span class="text-sm text-gray-500 font-light">{{ __('Usia 13 tahun ke atas') }}</span>
                </div>
                <div class="flex items-center gap-4">
                    <button @click.stop="adults = Math.max(1, adults - 1)" 
                            :disabled="adults <= 1"
                            class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-400 hover:border-gray-900 hover:text-gray-900 disabled:opacity-20 transition-all cursor-pointer">
                        <i data-lucide="minus" class="w-4 h-4"></i>
                    </button>
                    <span class="text-[16px] font-semibold w-6 text-center text-gray-900" x-text="adults"></span>
                    <button @click.stop="adults++" 
                            class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-600 hover:border-gray-900 hover:text-gray-900 transition-all cursor-pointer">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between py-6">
                <div class="flex flex-col">
                    <span class="text-[16px] font-bold text-gray-900">{{ __('Anak-anak') }}</span>
                    <span class="text-sm text-gray-500 font-light">{{ __('Usia 2-12') }}</span>
                </div>
                <div class="flex items-center gap-4">
                    <button @click.stop="children = Math.max(0, children - 1)" 
                            :disabled="children <= 0"
                            class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-400 hover:border-gray-900 hover:text-gray-900 disabled:opacity-20 transition-all cursor-pointer">
                        <i data-lucide="minus" class="w-4 h-4"></i>
                    </button>
                    <span class="text-[16px] font-semibold w-6 text-center text-gray-900" x-text="children"></span>
                    <button @click.stop="children++" 
                            class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-600 hover:border-gray-900 hover:text-gray-900 transition-all cursor-pointer">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Calendar Modal (Airbnb 1:1 Mirror) --}}
    <div class="absolute top-full mt-3 left-1/2 -translate-x-1/2 z-30 rounded-[32px] border border-gray-100 bg-white p-10 w-[95%] md:w-[850px]" 
         style="box-shadow: var(--airbnb-shadow-modal);"
         x-show="activeField === 'dates'" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         @click.stop>
         
        {{-- Inner Tabs (Tanggal & Fleksibel Only) --}}
        <div class="flex justify-center mb-6">
            <div class="p-[5px] rounded-full flex items-center bg-[#EBEBEB]">
                <button @click.prevent="calendarTab = 'dates'" 
                        :class="calendarTab === 'dates' ? 'bg-white shadow-[0_1px_3px_rgba(0,0,0,0.1)] text-gray-900' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-200/50'"
                        class="px-6 py-1.5 rounded-full text-[15px] font-semibold transition-all duration-300">
                    {{ __('Tanggal') }}
                </button>
                <button @click.prevent="calendarTab = 'flexible'" 
                        :class="calendarTab === 'flexible' ? 'bg-white shadow-[0_1px_3px_rgba(0,0,0,0.1)] text-gray-900' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-200/50'"
                        class="px-6 py-1.5 rounded-full text-[15px] font-semibold transition-all duration-300">
                    {{ __('Fleksibel') }}
                </button>
            </div>
        </div>

        {{-- Calendar Tab --}}
        <div x-show="calendarTab === 'dates'" class="airbnb-calendar-wrapper">
            <input x-ref="datepicker" type="text" class="hidden">
            
            {{-- Check-in and Check-out Selection Display with Dropdown --}}
            <div class="flex justify-center mt-8 relative" @click.away="toleranceDropdownOpen = false">
                <div class="flex items-center rounded-[32px] border border-[#EEEEEE] bg-white overflow-visible relative z-30">
                    <button type="button" @click="toleranceDropdownOpen = !toleranceDropdownOpen" class="flex flex-col text-left py-3 px-6 hover:bg-[#F7F7F7] focus:bg-[#F7F7F7] items-start transition-all gap-1 border-r border-[#EEEEEE] w-[220px] rounded-l-[32px]">
                        <span class="text-[12px] text-gray-500 font-semibold">{{ __('Check-in') }}</span>
                        <div class="flex items-center justify-between w-full">
                            <span class="text-[14px] text-gray-900 font-medium" 
                                  x-text="dateTolerance === 'exact' ? '{{ __('Tanggal pasti') }}' : '± ' + dateTolerance + ' hari'"></span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-gray-600 transition-transform" :class="toleranceDropdownOpen ? 'rotate-180' : ''"></i>
                        </div>
                    </button>
                    <button type="button" @click="toleranceDropdownOpen = !toleranceDropdownOpen" class="flex flex-col text-left py-3 px-6 hover:bg-[#F7F7F7] focus:bg-[#F7F7F7] items-start transition-all gap-1 w-[220px] rounded-r-[32px]">
                        <span class="text-[12px] text-gray-500 font-semibold">{{ __('Check-out') }}</span>
                        <div class="flex items-center justify-between w-full">
                            <span class="text-[14px] text-gray-900 font-medium"
                                  x-text="dateTolerance === 'exact' ? '{{ __('Tanggal pasti') }}' : '± ' + dateTolerance + ' hari'"></span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-gray-600 transition-transform" :class="toleranceDropdownOpen ? 'rotate-180' : ''"></i>
                        </div>
                    </button>
                    
                    {{-- Dropdown Popup --}}
                    <div x-show="toleranceDropdownOpen" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 lg:translate-y-0"
                         x-transition:leave-end="opacity-0 lg:-translate-y-2 pointer-events-none"
                         class="absolute bottom-full mb-3 left-0 w-[280px] bg-white rounded-[24px] border border-gray-100 py-3 shadow-[0_12px_44px_rgba(0,0,0,0.15)] z-[100]">
                        @php
                            $tolerances = [
                                ['id' => '14', 'label' => '± 14 hari'],
                                ['id' => '7', 'label' => '± 7 hari'],
                                ['id' => '3', 'label' => '± 3 hari'],
                                ['id' => '2', 'label' => '± 2 hari'],
                                ['id' => '1', 'label' => '± 1 hari'],
                                ['id' => 'exact', 'label' => 'Tanggal pasti']
                            ];
                        @endphp
                        @foreach($tolerances as $tol)
                        <button type="button" @click="dateTolerance = '{{ $tol['id'] }}'; toleranceDropdownOpen = false"
                                class="w-full text-left px-6 py-3.5 text-[15px] hover:bg-[#F7F7F7] transition-colors flex items-center justify-between"
                                :class="dateTolerance === '{{ $tol['id'] }}' ? 'font-semibold text-gray-900 bg-gray-50' : 'text-gray-700'">
                            {{ $tol['label'] }}
                            <i x-show="dateTolerance === '{{ $tol['id'] }}'" data-lucide="check" class="w-4 h-4 text-gray-900"></i>
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Flexible Tab --}}
        <div x-show="calendarTab === 'flexible'" style="display: none;" class="py-2">
            <div class="text-center mb-8">
                <h3 class="text-[22px] font-[600] text-[#222222] mb-5 tracking-tight">{{ __('Berapa lama Anda ingin menginap?') }}</h3>
                <div class="flex justify-center gap-3">
                    @php
                        $durations = [
                            ['id' => 'weekend', 'label' => __('Akhir pekan')],
                            ['id' => 'week', 'label' => __('Minggu')],
                            ['id' => 'month', 'label' => __('Bulan')],
                        ];
                    @endphp
                    @foreach($durations as $d)
                    <button @click="flexibleLength = '{{ $d['id'] }}'"
                            class="px-8 py-2.5 border rounded-full text-[14px] transition-all duration-200"
                            :class="flexibleLength === '{{ $d['id'] }}' ? 'border-[#222222] bg-[#F7F7F7] ring-1 ring-[#222222] text-[#222222]' : 'border-[#EEEEEE] text-[#222222] hover:border-[#222222] bg-white'">
                        {{ $d['label'] }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div class="mt-10 border-t border-[#EBEBEB] pt-8">
                <h3 class="text-[22px] font-[600] text-center text-[#222222] mb-6 tracking-tight">{{ __('Kapan Anda ingin pergi?') }}</h3>
                
                {{-- Horizontal Month Carousel (Dynamic) --}}
                <div class="relative group" x-data="{ showLeft: false, showRight: true }" x-init="
                    $nextTick(() => {
                        $refs.carousel.addEventListener('scroll', () => {
                            showLeft = $refs.carousel.scrollLeft > 10;
                            showRight = $refs.carousel.scrollLeft < ($refs.carousel.scrollWidth - $refs.carousel.clientWidth - 10);
                        });
                        // Check initial state after render
                        if($refs.carousel.scrollWidth <= $refs.carousel.clientWidth) {
                            showRight = false;
                        }
                    });
                ">
                    <!-- Left Arrow -->
                    <button type="button" x-show="showLeft" style="display: none;" 
                            @click="$refs.carousel.scrollBy({left: -300, behavior: 'smooth'})" 
                            class="absolute left-0 top-1/2 -translate-y-1/2 -ml-3 w-8 h-8 bg-white border border-gray-200 rounded-full shadow-sm flex items-center justify-center z-10 hover:shadow-md hover:scale-105 transition-all text-gray-600">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                    </button>

                    <div x-ref="carousel" class="flex gap-4 overflow-x-auto no-scrollbar scroll-smooth pb-4 px-2 snap-x">
                        @php
                            \Carbon\Carbon::setLocale('id'); // Force Indonesian month names
                            $availableMonths = [];
                            for($i=0; $i<12; $i++) {
                                $date = \Carbon\Carbon::now()->addMonths($i);
                                $availableMonths[] = [
                                    'id' => strtolower($date->format('F-Y')),
                                    'name' => $date->isoFormat('MMMM'), 
                                    'year' => $date->format('Y')
                                ];
                            }
                        @endphp
                        @foreach($availableMonths as $month)
                        <button type="button" @click="flexibleMonth = '{{ $month['id'] }}'"
                                class="flex-none w-[120px] sm:w-[130px] flex flex-col items-center pt-8 pb-6 px-4 border rounded-[20px] transition-all duration-200 snap-start group cursor-pointer"
                                :class="flexibleMonth === '{{ $month['id'] }}' ? 'border-[#222222] bg-[#F7F7F7] ring-[1.5px] ring-[#222222]' : 'border-[#DDDDDD] hover:border-[#222222] bg-white'">
                            <div class="w-10 h-10 mb-2 flex items-center justify-center transition-colors">
                                <i data-lucide="calendar-days" class="w-8 h-8" stroke-width="1.5"
                                   :class="flexibleMonth === '{{ $month['id'] }}' ? 'text-[#222222]' : 'text-gray-400 group-hover:text-gray-800'"></i>
                            </div>
                            <span class="text-[15px] mt-2 font-normal text-[#222222] mb-0.5">{{ $month['name'] }}</span>
                            <span class="text-[13px] font-light text-gray-500">{{ $month['year'] }}</span>
                        </button>
                        @endforeach
                        
                        {{-- Spacer for end of scroll --}}
                        <div class="flex-none w-4"></div>
                    </div>

                    <!-- Right Arrow -->
                    <button type="button" x-show="showRight" 
                            @click="$refs.carousel.scrollBy({left: 300, behavior: 'smooth'})" 
                            class="absolute right-0 top-1/2 -translate-y-1/2 -mr-3 w-8 h-8 bg-white border border-gray-200 rounded-full shadow-sm flex items-center justify-center z-10 hover:shadow-md hover:scale-105 transition-all text-gray-600">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Smart Search --}}
    @if($aiStatus)
    <div x-show="searchMode === 'ai'" style="display: none;"
         class="bg-white rounded-full border border-gray-200 shadow-[0_3px_12px_rgba(0,0,0,0.1)] hover:shadow-[0_12px_32px_rgba(0,0,0,0.15)] transition-all overflow-hidden mx-auto max-w-[720px]">
        <form action="{{ route('frontend.perahu.ai_search') }}" method="GET" class="flex items-center h-[66px]">
            <div class="pl-10 flex-grow flex flex-col justify-center">
                <span class="text-[10px] font-black text-rose-500 uppercase tracking-[0.2em] mb-0.5">{{ __('GoFishi Intelligence') }}</span>
                <input x-ref="aiInput" type="text" name="q" 
                       placeholder="{{ __('Coba: \'Yacht mewah di Kepulauan Seribu untuk 12 orang\'') }}" 
                       class="w-full bg-transparent border-none p-0 focus:ring-0 text-[15px] text-gray-900 font-medium placeholder-gray-400">
            </div>
            <div class="pr-3">
                <button type="submit" 
                        class="bg-gradient-to-r from-rose-500 to-rose-600 text-white rounded-full h-12 px-8 flex items-center gap-2 hover:shadow-[0_4px_12_rgba(255,56,92,0.4)] hover:scale-105 transition-all">
                    <i data-lucide="sparkle" class="w-4 h-4"></i>
                    <span class="font-bold text-sm">{{ __('Tanya AI') }}</span>
                </button>
            </div>
        </form>
    </div>
    @endif

    {{-- Hidden Classic Form --}}
    <form action="{{ route('frontend.perahu') }}" method="GET" id="hero-search-form" class="hidden">
        <input type="hidden" name="location" x-model="location">
        <input type="hidden" name="checkIn" x-model="checkIn">
        <input type="hidden" name="checkOut" x-model="checkOut">
        <input type="hidden" name="adults" x-model="adults">
        <input type="hidden" name="children" x-model="children">
    </form>
</div>
