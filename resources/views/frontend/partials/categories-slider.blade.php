@php
    $lang = $currentLanguageInfo ?? get_lang();
    $categories = \App\Models\RoomCategory::where('language_id', $lang->id)->where('status', 1)->orderBy('serial_number', 'asc')->get();
    
    // Icon mapping for boat categories
    $iconMap = [
        'mancing' => 'fish',
        'wisata' => 'palmtree',
        'lokasi' => 'anchor',
        'yacht' => 'ship',
        'speedboat' => 'zap',
        'default' => 'compass'
    ];
@endphp

<div class="sticky top-[80px] z-40 bg-white border-b border-gray-200" x-data="{ 
    scrollAtStart: true, 
    scrollAtEnd: false,
    updateScroll() {
        const el = this.$refs.slider;
        this.scrollAtStart = el.scrollLeft <= 0;
        this.scrollAtEnd = el.scrollLeft + el.clientWidth >= el.scrollWidth - 1;
    },
    scroll(dir) {
        this.$refs.slider.scrollBy({ left: dir * 300, behavior: 'smooth' });
    }
}" @init="updateScroll()">
    <div class="max-w-[2520px] mx-auto px-4 sm:px-6 lg:px-20 py-4 relative group">
        
        {{-- Previous Arrow --}}
        <button x-show="!scrollAtStart" 
                @click="scroll(-1)"
                class="absolute left-10 top-1/2 -translate-y-1/2 z-10 p-1 bg-white border border-gray-300 rounded-full shadow-md hover:scale-110 transition hidden md:flex items-center justify-center w-8 h-8">
            <i data-lucide="chevron-left" class="w-5 h-5"></i>
        </button>

        {{-- Categories Scroll --}}
        <div x-ref="slider" 
             @scroll.debounce.100ms="updateScroll()"
             class="flex items-center gap-8 overflow-x-auto no-scrollbar py-2">
            
            {{-- All Perahu --}}
            <a href="{{ route('frontend.perahu') }}" 
               class="flex flex-col items-center gap-2 group border-b-2 transition pb-2 {{ !request('category') ? 'border-black text-black' : 'border-transparent text-gray-500 hover:text-black hover:border-gray-200' }}">
                <i data-lucide="waves" class="w-6 h-6"></i>
                <span class="text-xs font-semibold whitespace-nowrap">{{ __('Semua Perahu') }}</span>
            </a>

            @foreach($categories as $cat)
                @php 
                    $catSlug = optional($cat)->slug ?? '';
                    $slug = strtolower($catSlug);
                    $icon = $iconMap[$slug] ?? $iconMap['default'];
                    $active = request('category') == $catSlug;
                @endphp
                @if($catSlug)
                <a href="{{ route('frontend.perahu', ['category' => $catSlug]) }}" 
                   class="flex flex-col items-center gap-2 group border-b-2 transition pb-2 {{ $active ? 'border-black text-black' : 'border-transparent text-gray-500 hover:text-black hover:border-gray-200' }}">
                    <i data-lucide="{{ $icon }}" class="w-6 h-6"></i>
                    <span class="text-xs font-semibold whitespace-nowrap">{{ optional($cat)->name }}</span>
                </a>
                @endif
            @endforeach

            {{-- Lokasi/Dermaga Link as Category --}}
            <a href="{{ route('frontend.lokasi') }}" 
               class="flex flex-col items-center gap-2 group border-b-2 transition pb-2 border-transparent text-gray-500 hover:text-black hover:border-gray-200">
                <i data-lucide="map-pin" class="w-6 h-6"></i>
                <span class="text-xs font-semibold whitespace-nowrap">{{ __('Dermaga') }}</span>
            </a>
        </div>

        {{-- Next Arrow --}}
        <button x-show="!scrollAtEnd" 
                @click="scroll(1)"
                class="absolute right-10 top-1/2 -translate-y-1/2 z-10 p-1 bg-white border border-gray-300 rounded-full shadow-md hover:scale-110 transition hidden md:flex items-center justify-center w-8 h-8">
            <i data-lucide="chevron-right" class="w-5 h-5"></i>
        </button>

        {{-- Filters Button --}}
        <div class="absolute right-20 top-1/2 -translate-y-1/2 h-8 flex items-center bg-white pl-4">
             <button class="hidden lg:flex items-center gap-2 px-4 py-3 border border-gray-300 rounded-xl text-xs font-semibold hover:border-black transition">
                 <i data-lucide="sliders-horizontal" class="w-4 h-4"></i>
                 {{ __('Filter') }}
             </button>
        </div>
    </div>
</div>
