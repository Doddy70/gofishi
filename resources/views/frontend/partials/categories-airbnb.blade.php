@php
    $lang = $currentLanguageInfo ?? get_lang();
    $categories = App\Models\RoomCategory::where('language_id', $lang->id)
        ->where('status', 1)
        ->get();

    // Map categories to emoji icons to match the React template
    $iconMapping = [
        'Yacht' => '🌍',
        'Speedboat' => '🌊',
        'Traditional' => '🪵',
        'Fishing' => '🏔️',
        'Sailboat' => '🏛️',
        'Catamaran' => '✨',
    ];
@endphp

{{-- Categories - Center Aligned (Exact match from Advanced-Search-React) --}}
<div class="border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex justify-center space-x-4 overflow-x-auto pb-2 scrollbar-hide">
            @foreach($categories as $category)
                @php
                    $isActive = request()->input('category') == $category->slug;
                @endphp
                <a href="{{ route('frontend.perahu', ['category' => $category->slug]) }}" 
                   class="flex flex-col items-center space-y-2 px-6 py-3 rounded-lg transition min-w-fit 
                          {{ $isActive ? 'bg-gray-100 border-b-2 border-gray-900' : 'hover:bg-gray-50' }}">
                    
                    <span class="text-2xl">{{ $iconMapping[$category->name] ?? '🌊' }}</span>
                    
                    <span class="text-xs font-medium whitespace-nowrap {{ $isActive ? 'text-gray-900' : 'text-gray-600' }}">
                        {{ $category->name }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</div>

<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
