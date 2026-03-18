@php
    $locations = [
        ['city' => 'Marina Ancol', 'state' => 'Jakarta Utara', 'country' => 'Indonesia'],
        ['city' => 'Pantai Mutiara', 'state' => 'Penjaringan', 'country' => 'Indonesia'],
        ['city' => 'Muara Angke', 'state' => 'Jakarta Utara', 'country' => 'Indonesia'],
        ['city' => 'Kepulauan Seribu', 'state' => 'DKI Jakarta', 'country' => 'Indonesia'],
        ['city' => 'Tanjung Pasir', 'state' => 'Tangerang', 'country' => 'Indonesia'],
    ];

    $basicSettings = \App\Models\BasicSettings\Basic::select('google_gemini_status')->first();
    $aiStatus = $basicSettings && $basicSettings->google_gemini_status == 1;
@endphp

<style>
    .airbnb-calendar-wrapper .flatpickr-calendar.inline {
        box-shadow: none !important;
        border: none !important;
        background: transparent !important;
        max-width: 100%;
    }
</style>

<div x-data="{ 
        searchMode: 'classic', // 'classic' or 'ai'
        activeField: null,
        isSelecting: false,
        calendarTab: 'dates', // 'dates', 'months', 'flexible'
        flexibleLength: 'weekend', // 'weekend', 'week', 'month'
        flexibleMonth: 'march',
        monthsCount: 1,
        location: '',
        allLocations: {{ json_encode($locations) }},
        filteredLocations: [],
        checkIn: '',
        checkOut: '',
        adults: 1,
        children: 0,
        aiQuery: '',
        
        get totalGuests() { return this.adults + this.children },
        get guestText() {
            if (this.totalGuests === 0) return '{{ __("Tambahkan tamu") }}';
            return `${this.totalGuests} {{ __("peserta") }}`;
        },
        
        init() {
            this.filteredLocations = this.allLocations;
            this.$watch('location', value => {
                if (this.isSelecting) return;
                
                if (value && value.trim()) {
                    let term = value.toLowerCase();
                    this.filteredLocations = this.allLocations.filter(loc => 
                        loc.city.toLowerCase().includes(term) || 
                        loc.state.toLowerCase().includes(term) ||
                        term.includes(loc.city.toLowerCase()) // Allow 'Marina Ancol, Jakarta' to still show Marina Ancol
                    );
                } else {
                    this.filteredLocations = this.allLocations;
                }
            });
            
            // Flatpickr instance placeholder
            this.fpInstance = null;
        },
        
        initCalendar() {
            if (!this.fpInstance && this.$refs.datepicker && typeof window !== 'undefined') {
                this.fpInstance = flatpickr(this.$refs.datepicker, {
                    mode: 'range',
                    minDate: 'today',
                    showMonths: window.innerWidth > 768 ? 2 : 1,
                        inline: true,
                        dateFormat: 'Y-m-d',
                        onChange: (selectedDates, dateStr, instance) => {
                            if (selectedDates.length >= 1) {
                                this.checkIn = instance.formatDate(selectedDates[0], 'Y-m-d');
                                this.checkOut = '';
                            }
                            if (selectedDates.length === 2) {
                                this.checkOut = instance.formatDate(selectedDates[1], 'Y-m-d');
                                // Auto advance to guests after selecting an end date
                                setTimeout(() => {
                                    if (this.activeField === 'dates') {
                                        this.activeField = 'guests';
                                    }
                                }, 350);
                            }
                        }
                    });
            }
        },
        
        openDates() {
            this.activeField = 'dates';
            this.$nextTick(() => {
                this.initCalendar();
            });
        },

        selectLocation(loc) {
            this.isSelecting = true;
            this.location = loc.city; // Set only city so dropdown filtering still works
            this.filteredLocations = this.allLocations; // Reset menu for next visit
            this.activeField = 'dates';
            this.$nextTick(() => { this.isSelecting = false; });
        }
     }" 
     class="w-full max-w-[850px] mx-auto mb-12 relative z-[60]"
     @click.away="activeField = null">

    {{-- Search Type Switcher (Like Airbnb Stays/Experiences) --}}
    <div class="flex items-center justify-center gap-6 mb-6 relative z-20">
        <button @click="searchMode = 'classic'; activeField = null"
                :class="searchMode === 'classic' ? 'text-gray-900 font-medium' : 'text-gray-500 hover:text-gray-700'"
                class="text-base transition border-b-2 px-1 pb-1"
                :style="searchMode === 'classic' ? 'border-color: #222222' : 'border-color: transparent'">
            Stays / Perahu
        </button>
        @if($aiStatus)
        <button @click="searchMode = 'ai'; activeField = null; $nextTick(() => $refs.aiInput.focus())"
                :class="searchMode === 'ai' ? 'text-gray-900 font-medium' : 'text-gray-500 hover:text-gray-700'"
                class="text-base transition border-b-2 px-1 pb-1 flex items-center gap-1.5"
                :style="searchMode === 'ai' ? 'border-color: #222222' : 'border-color: transparent'">
            <i class="fas fa-sparkles text-rose-500 text-sm"></i> Smart Search
        </button>
        @endif
    </div>

    {{-- Classic Interactive Airbnb Pill Form --}}
    <div x-show="searchMode === 'classic'" 
         class="bg-[#EBEBEB] rounded-full shadow-md border border-gray-200 hover:shadow-lg transition-shadow overflow-visible mx-auto w-full transition-all duration-300 relative z-20">
        
        <div class="flex items-stretch divide-x divide-gray-200 h-16 sm:h-[68px]">
            
            {{-- Lokasi --}}
            <div class="flex-1 relative group cursor-pointer" :class="activeField === 'location' ? 'bg-white rounded-full shadow-[0_6px_20px_rgba(0,0,0,0.15)] z-30' : 'hover:bg-[#DDDDDD] rounded-full'">
                <div class="px-8 py-3.5 flex flex-col justify-center h-full rounded-full w-full relative"
                     @click="activeField = 'location'; $nextTick(() => { $refs.locInput.focus(); $refs.locInput.select(); })">
                    <label for="locInput" class="block text-xs font-extrabold text-gray-900 mb-0.5 tracking-tight cursor-pointer">{{ __('Lokasi') }}</label>
                    <div class="flex items-center w-full">
                        <input x-ref="locInput" id="locInput" type="text" x-model="location" 
                               @click.stop="activeField = 'location'"
                               placeholder="{{ __('Kota, tujuan, atau nama kapal') }}" 
                               class="w-full outline-none text-[15px] text-gray-600 placeholder-gray-400 bg-transparent border-none p-0 focus:ring-0 truncate font-medium pr-6">
                        
                        {{-- Clear Location Button --}}
                        <button x-show="location.length > 0 && activeField === 'location'" 
                                @click.stop="location = ''; filteredLocations = allLocations; $refs.locInput.focus()" 
                                class="absolute right-5 w-5 h-5 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-gray-600 transition"
                                style="display: none;">
                            <i class="fas fa-times text-[10px]"></i>
                        </button>
                    </div>
                </div>

                {{-- Location Dropdown --}}
                <div x-show="activeField === 'location'" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute top-[80px] mt-2 left-0 bg-white rounded-[32px] shadow-[0_6px_24px_rgba(0,0,0,0.18)] border border-gray-100 py-6 z-50 w-full md:w-[450px]">
                    <div class="px-8 pb-3">
                        <h3 class="text-xs font-bold text-gray-500 tracking-wider">{{ __('SUGGESTED DESTINATIONS') }}</h3>
                    </div>
                    <div class="max-h-80 overflow-y-auto no-scrollbar">
                        <template x-for="(loc, index) in filteredLocations" :key="index">
                            <button @click="selectLocation(loc)" 
                                    class="w-full px-8 py-3 hover:bg-gray-100 transition text-left flex items-center space-x-4">
                                <div class="w-[48px] h-[48px] bg-gray-100 rounded-[12px] flex items-center justify-center border border-gray-200 shrink-0">
                                    <i class="fas fa-map-marker-alt text-gray-700 text-lg"></i>
                                </div>
                                <div class="truncate">
                                    <p class="text-[15px] font-semibold text-gray-900 truncate" x-text="loc.city"></p>
                                    <p class="text-[13px] text-gray-500 font-light truncate" x-text="`${loc.state}, ${loc.country}`"></p>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Tanggal --}}
            <div class="flex-[1.5] relative hidden sm:block group cursor-pointer" :class="activeField === 'dates' ? 'bg-white rounded-full shadow-[0_6px_20px_rgba(0,0,0,0.15)] z-30' : 'hover:bg-[#DDDDDD]'">
                <div class="px-6 py-3.5 flex flex-col justify-center h-full rounded-full w-full"
                     @click="openDates()">
                    <label class="block text-xs font-extrabold text-gray-900 mb-0.5 tracking-tight cursor-pointer">{{ __('Tanggal') }}</label>
                    <div class="text-[15px] cursor-pointer" :class="checkIn ? 'text-gray-900 font-semibold' : 'text-gray-600 font-medium'" x-text="checkIn ? (checkIn + (checkOut ? ' - ' + checkOut : '')) : '{{ __('Tambahkan tanggal') }}'"></div>
                </div>
            </div>

            {{-- Dates Calendar Dropdown (Joint for Check In and Out) --}}
            <div class="absolute top-[80px] left-1/2 -translate-x-1/2 z-[60] rounded-[32px] shadow-[0_6px_24px_rgba(0,0,0,0.18)] border border-neutral-100 bg-white p-6 md:p-8 w-[95%] md:min-w-[850px] overflow-hidden" 
                 x-show="activeField === 'dates'" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 @click.stop>
                 
                {{-- Calendar Megamenu Tabs --}}
                <div class="flex justify-center mb-6">
                    <div class="bg-gray-100/80 p-1.5 rounded-full inline-flex items-center space-x-1">
                        <button @click.prevent="calendarTab = 'dates'" 
                                :class="calendarTab === 'dates' ? 'bg-white shadow-sm text-gray-900 font-semibold' : 'text-gray-600 font-medium hover:text-gray-900'"
                                class="px-6 py-1.5 rounded-full text-[15px] transition-all duration-200">Dates</button>
                                
                        <button @click.prevent="calendarTab = 'months'" 
                                :class="calendarTab === 'months' ? 'bg-white shadow-sm text-gray-900 font-semibold' : 'text-gray-600 font-medium hover:text-gray-900'"
                                class="px-6 py-1.5 rounded-full text-[15px] transition-all duration-200">Months</button>
                                
                        <button @click.prevent="calendarTab = 'flexible'" 
                                :class="calendarTab === 'flexible' ? 'bg-white shadow-sm text-gray-900 font-semibold' : 'text-gray-600 font-medium hover:text-gray-900'"
                                class="px-6 py-1.5 rounded-full text-[15px] transition-all duration-200">Flexible</button>
                    </div>
                </div>

                {{-- Tab 1: Dates (Classic Calendar) --}}
                <div x-show="calendarTab === 'dates'" 
                     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="airbnb-calendar-wrapper flex justify-center w-full relative z-[70]">
                        <input x-ref="datepicker" type="text" class="hidden">
                    </div>
                    <div class="calendar-buttons w-full flex flex-wrap justify-center gap-2 mt-6 pb-2">
                        <button class="px-4 py-2 text-[13px] font-medium border border-gray-900 rounded-full text-gray-900 bg-gray-50 hover:bg-gray-100 transition-colors">Exact dates</button>
                        <button class="px-4 py-2 text-[13px] font-medium border border-gray-300 rounded-full text-gray-700 hover:border-gray-900 hover:text-gray-900 transition-colors">&plusmn; 1 day</button>
                        <button class="px-4 py-2 text-[13px] font-medium border border-gray-300 rounded-full text-gray-700 hover:border-gray-900 hover:text-gray-900 transition-colors">&plusmn; 2 days</button>
                        <button class="px-4 py-2 text-[13px] font-medium border border-gray-300 rounded-full text-gray-700 hover:border-gray-900 hover:text-gray-900 transition-colors">&plusmn; 3 days</button>
                        <button class="px-4 py-2 text-[13px] font-medium border border-gray-300 rounded-full text-gray-700 hover:border-gray-900 hover:text-gray-900 transition-colors">&plusmn; 7 days</button>
                        <button class="px-4 py-2 text-[13px] font-medium border border-gray-300 rounded-full text-gray-700 hover:border-gray-900 hover:text-gray-900 transition-colors">&plusmn; 14 days</button>
                    </div>
                </div>

                {{-- Tab 2: Months (Dial Widget) --}}
                <div x-show="calendarTab === 'months'" style="display: none;" class="py-8"
                     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <h3 class="text-xl font-semibold text-center text-gray-900 mb-12">When's your trip?</h3>
                    <div class="flex flex-col items-center justify-center space-y-12">
                        
                        {{-- Circular UI dial mock --}}
                        <div class="relative w-64 h-64 rounded-full border-[12px] border-rose-100 flex items-center justify-center">
                            {{-- Fake active segment for dial effect --}}
                            <div class="absolute inset-0 rounded-full" style="clip-path: polygon(50% 50%, 50% 0%, 100% 0%, 100% 50%); border: 12px solid #E11D48; margin: -12px;"></div>
                            
                            {{-- Dial nub --}}
                            <div class="absolute right-[-18px] top-1/2 -translate-y-1/2 w-8 h-8 bg-white border border-gray-200 rounded-full shadow-[0_2px_8px_rgba(0,0,0,0.15)] z-10 hidden md:block cursor-pointer"></div>
                            
                            <div class="text-center z-10 relative">
                                <span class="text-7xl font-bold tracking-tighter text-gray-900" x-text="monthsCount"></span>
                                <span class="block text-lg font-medium text-gray-900 mt-1">bulan</span>
                            </div>

                            {{-- Range Dots --}}
                            <div class="absolute inset-0 z-0">
                                @for($i = 0; $i < 12; $i++)
                                    <div class="absolute w-1 h-1 bg-gray-300 rounded-full" 
                                         style="top: 50%; left: 50%; transform: translate(-50%, -50%) rotate({{ $i * 30 }}deg) translateY(-110px);"></div>
                                @endfor
                            </div>
                        </div>

                        <div class="flex items-center space-x-6">
                            <button @click.prevent="monthsCount = Math.max(1, monthsCount - 1)" class="w-10 h-10 border border-gray-300 rounded-full flex items-center justify-center text-xl hover:border-gray-900 transition-colors text-gray-500 hover:text-gray-900">−</button>
                            <span class="text-[17px] font-semibold text-gray-900 border-b border-gray-900 pb-0.5">Wed, Apr 1 to Wed, Jul 1</span>
                            <button @click.prevent="monthsCount = Math.min(12, monthsCount + 1)" class="w-10 h-10 border border-gray-300 rounded-full flex items-center justify-center text-xl hover:border-gray-900 transition-colors text-gray-500 hover:text-gray-900">+</button>
                        </div>
                    </div>
                </div>

                {{-- Tab 3: Flexible --}}
                <div x-show="calendarTab === 'flexible'" style="display: none;" class="py-6"
                     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <h3 class="text-xl font-semibold text-center text-gray-900 mb-6">How long would you like to stay?</h3>
                    
                    {{-- Length Pills --}}
                    <div class="flex justify-center gap-3 mb-10">
                        <button @click.prevent="flexibleLength = 'weekend'" 
                                :class="flexibleLength === 'weekend' ? 'border-gray-900 border-2 bg-gray-50' : 'border-gray-300 border hover:border-gray-900'"
                                class="px-5 py-2.5 rounded-full text-sm font-medium text-gray-800 transition-all">Weekend</button>
                        <button @click.prevent="flexibleLength = 'week'" 
                                :class="flexibleLength === 'week' ? 'border-gray-900 border-2 bg-gray-50' : 'border-gray-300 border hover:border-gray-900'"
                                class="px-5 py-2.5 rounded-full text-sm font-medium text-gray-800 transition-all">Week</button>
                        <button @click.prevent="flexibleLength = 'month'" 
                                :class="flexibleLength === 'month' ? 'border-gray-900 border-2 bg-gray-50' : 'border-gray-300 border hover:border-gray-900'"
                                class="px-5 py-2.5 rounded-full text-sm font-medium text-gray-800 transition-all">Month</button>
                    </div>

                    <h3 class="text-lg font-semibold text-center text-gray-900 mb-6 relative">
                        Go anytime
                        <button class="absolute right-0 top-0 w-8 h-8 border border-gray-300 rounded-full flex items-center justify-center text-sm shadow hover:shadow-md hover:scale-105 transition-all text-gray-600 hidden md:flex"><i class="fas fa-chevron-right"></i></button>
                    </h3>

                    {{-- Months Grid --}}
                    <div class="grid grid-cols-3 md:grid-cols-6 gap-4 px-4">
                        @php
                            $months = ['March', 'April', 'May', 'June', 'July', 'August'];
                        @endphp
                        @foreach($months as $index => $month)
                        <button @click.prevent="flexibleMonth = '{{ strtolower($month) }}'" 
                                :class="flexibleMonth === '{{ strtolower($month) }}' ? 'border-gray-900 border-2 bg-gray-50 shadow-sm' : 'border-gray-200 border hover:border-gray-900'"
                                class="flex flex-col items-center justify-center py-6 px-2 rounded-2xl transition-all h-32 relative">
                            <i class="far fa-calendar-alt text-3xl mb-3 text-gray-700"></i>
                            <span class="text-[15px] font-semibold text-gray-900">{{ $month }}</span>
                            <span class="text-xs text-gray-500 mt-0.5">2026</span>
                            <div x-show="flexibleMonth === '{{ strtolower($month) }}'" class="absolute -top-1 -right-1 w-5 h-5 bg-rose-500 text-white rounded-full flex items-center justify-center text-[10px]">
                                <i class="fas fa-check"></i>
                            </div>
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Peserta / Who --}}
            <div class="flex-[1.2] relative group cursor-pointer" :class="activeField === 'guests' ? 'bg-white rounded-full shadow-[0_6px_20px_rgba(0,0,0,0.15)] z-30' : 'hover:bg-[#DDDDDD] rounded-r-full'">
                <div class="pl-6 pr-2 py-2.5 flex items-center justify-between h-full rounded-full w-full"
                     @click="activeField = 'guests'">
                    <div class="flex-1 pr-2 truncate">
                        <label class="block text-xs font-extrabold text-gray-900 mb-0.5 tracking-tight cursor-pointer">{{ __('Jumlah Tamu') }}</label>
                        <div class="text-[15px] cursor-pointer" :class="guestText !== '{{ __("Tambahkan tamu") }}' ? 'text-gray-900 font-semibold' : 'text-gray-600 font-medium'" x-text="guestText"></div>
                    </div>
                    
                    {{-- The Search Button --}}
                    <button @click.stop="document.getElementById('hero-search-form').submit()" 
                            class="bg-[#FF385C] text-white rounded-full hover:bg-[#D70466] hover:shadow-lg transition-all duration-300 flex items-center justify-center shrink-0"
                            :class="activeField === null ? 'h-12 w-12' : 'h-12 w-[110px] pl-4 pr-5 gap-2'">
                        <i class="fas fa-search text-[15px]"></i>
                        <span class="font-bold text-sm" x-show="activeField !== null">Cari</span>
                    </button>
                </div>

                {{-- Guest Dropdown --}}
                <div x-show="activeField === 'guests'" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute top-[80px] mt-2 right-0 bg-white rounded-[32px] shadow-[0_6px_24px_rgba(0,0,0,0.18)] border border-gray-100 py-4 px-8 z-50 w-full sm:w-[400px]">
                    
                    {{-- Adults --}}
                    <div class="flex items-center justify-between py-6 border-b border-gray-100">
                        <div>
                            <p class="text-[16px] font-semibold text-gray-900">{{ __('Dewasa') }}</p>
                            <p class="text-sm text-gray-500 font-normal">{{ __('Usia 13 tahun ke atas') }}</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button @click.stop="adults = Math.max(1, adults - 1)" 
                                    :disabled="adults <= 1"
                                    class="w-8 h-8 rounded-full border border-gray-400 flex items-center justify-center hover:border-gray-900 hover:text-gray-900 text-gray-400 disabled:opacity-30 transition text-lg">−</button>
                            <span class="text-base font-semibold w-4 text-center text-gray-900" x-text="adults"></span>
                            <button @click.stop="adults++" 
                                    class="w-8 h-8 rounded-full border border-gray-400 flex items-center justify-center hover:border-gray-900 hover:text-gray-900 text-gray-400 transition text-lg">+</button>
                        </div>
                    </div>

                    {{-- Children --}}
                    <div class="flex items-center justify-between py-6">
                        <div>
                            <p class="text-[16px] font-semibold text-gray-900">{{ __('Anak-anak') }}</p>
                            <p class="text-sm text-gray-500 font-normal">{{ __('Usia 2-12') }}</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button @click.stop="children = Math.max(0, children - 1)" 
                                    :disabled="children <= 0"
                                    class="w-8 h-8 rounded-full border border-gray-400 flex items-center justify-center hover:border-gray-900 hover:text-gray-900 text-gray-400 disabled:opacity-30 transition text-lg">−</button>
                            <span class="text-base font-semibold w-4 text-center text-gray-900" x-text="children"></span>
                            <button @click.stop="children++" 
                                    class="w-8 h-8 rounded-full border border-gray-400 flex items-center justify-center hover:border-gray-900 hover:text-gray-900 text-gray-400 transition text-lg">+</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Smart AI Search Form (Minimal, Single Input Airbnb Style) --}}
    @if($aiStatus)
    <div x-show="searchMode === 'ai'" style="display: none;"
         class="bg-white rounded-full border border-gray-200 shadow-md hover:shadow-lg transition-shadow overflow-hidden mx-auto max-w-[700px]">
        <form action="{{ route('frontend.perahu.ai_search') }}" method="GET" class="flex items-center h-16 sm:h-[68px] relative">
            <div class="pl-8 pr-4 flex-grow relative h-full flex flex-col justify-center">
                <label for="aiInput" class="block text-xs font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-rose-500 to-purple-600 mb-0.5 tracking-tight uppercase">Sparkle Search</label>
                <input x-ref="aiInput" id="aiInput" type="text" name="q" x-model="aiQuery" 
                       placeholder="Try: 'Luxury yacht for 10 people in Bali...'" 
                       class="w-full text-[15px] outline-none border-none p-0 focus:ring-0 text-gray-900 font-medium placeholder-gray-400 bg-transparent">
            </div>
            <div class="pr-2.5">
                <button type="submit" 
                        class="bg-gradient-to-r from-rose-500 to-rose-600 text-white rounded-full hover:scale-105 hover:shadow-lg transition-transform flex items-center justify-center shrink-0 h-12 px-6 gap-2">
                    <i class="fas fa-magic text-[14px]"></i>
                    <span class="font-bold text-sm hidden sm:block">Ask AI</span>
                </button>
            </div>
        </form>
    </div>
    @endif

    {{-- Transparent Overlay to handle clicks away without darkening the screen --}}
    <div x-show="activeField !== null && searchMode === 'classic'" 
         @click="activeField = null"
         class="fixed inset-0 bg-transparent z-10 w-screen h-screen">
    </div>

    {{-- Hidden Classic Form --}}
    <form action="{{ route('frontend.perahu') }}" method="GET" id="hero-search-form" class="hidden">
        <input type="hidden" name="location" x-model="location">
        <input type="hidden" name="checkIn" x-model="checkIn">
        <input type="hidden" name="checkOut" x-model="checkOut">
        <input type="hidden" name="adults" x-model="adults">
        <input type="hidden" name="children" x-model="children">
    </form>
</div>
