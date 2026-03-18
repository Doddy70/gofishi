<div class="group bg-white rounded-3xl border border-gray-100 hover:border-gray-200 hover:shadow-2xl transition-all duration-500 overflow-hidden flex flex-col h-full">
    <div class="p-8 pb-4 flex flex-col items-center flex-grow text-center">
        {{-- Avatar --}}
        <a href="{{ route('frontend.vendor.details', ['username' => $vendor->username]) }}" 
           class="relative mb-6">
            <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-lg group-hover:scale-105 transition-transform duration-500 bg-gray-50">
                @if ($isAdmin)
                    <img src="{{ asset('assets/img/admins/' . $vendor->image) }}" class="w-full h-full object-cover" alt="Admin">
                @else
                    <img src="{{ $vendor->photo ? asset('assets/admin/img/vendor-photo/' . $vendor->photo) : asset('assets/img/blank-user.jpg') }}" 
                         class="w-full h-full object-cover" alt="Vendor">
                @endif
            </div>
            @if($isAdmin || ($vendor->is_verified_17_plus ?? false))
                <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-blue-500 rounded-full border-4 border-white flex items-center justify-center text-white shadow-sm" title="Verified Vendor">
                    <i data-lucide="check" class="w-4 h-4"></i>
                </div>
            @endif
        </a>

        {{-- Info --}}
        <h3 class="text-lg font-bold text-gray-900 group-hover:text-airbnb-red transition truncate w-full px-4">
            <a href="{{ route('frontend.vendor.details', ['username' => $vendor->username]) }}">
                {{ $isAdmin ? ($vendor->first_name . ' ' . $vendor->last_name) : ($vendor->vendor_info->name ?? $vendor->username) }}
            </a>
        </h3>
        <p class="text-sm text-gray-400 font-medium mb-4">@ @{{ $vendor->username }}</p>

        <div class="flex items-center gap-2 mb-6">
            <div class="flex items-center text-airbnb-red text-xs font-bold bg-rose-50 px-3 py-1 rounded-full">
                <i data-lucide="anchor" class="w-3 h-3 mr-1.5"></i>
                {{ App\Models\Hotel::where('vendor_id', $isAdmin ? 0 : $vendor->id)->count() }} {{ __('Lokasi') }}
            </div>
        </div>

        {{-- Contact Details --}}
        <div class="space-y-3 w-full text-left bg-gray-50/50 p-4 rounded-2xl mb-6">
            @php
                $address = $isAdmin ? $vendor->address : ($vendor->vendor_info->address ?? null);
            @endphp
            @if($address)
                <div class="flex items-start text-xs text-gray-500 font-light">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5 mr-2 shrink-0 mt-0.5"></i>
                    <span class="line-clamp-1">{{ $address }}</span>
                </div>
            @endif
            
            @if(($isAdmin ? $vendor->show_phone_number : ($vendor->show_phone_number ?? 0)) == 1 && $vendor->phone)
                <div class="flex items-center text-xs text-gray-500 font-light">
                    <i data-lucide="phone" class="w-3.5 h-3.5 mr-2 shrink-0"></i>
                    <span>{{ $vendor->phone }}</span>
                </div>
            @endif

            @if(($isAdmin ? $vendor->show_email_address : ($vendor->show_email_addresss ?? 0)) == 1)
                <div class="flex items-center text-xs text-gray-500 font-light">
                    <i data-lucide="mail" class="w-3.5 h-3.5 mr-2 shrink-0"></i>
                    <span class="truncate">{{ $vendor->email }}</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Action --}}
    <div class="p-6 pt-0 mt-auto">
        <a href="{{ route('frontend.vendor.details', ['username' => $vendor->username]) }}" 
           class="flex items-center justify-center w-full py-3.5 bg-gray-900 group-hover:bg-airbnb-red text-white text-sm font-bold rounded-2xl shadow-lg transition-all transform hover:scale-[1.02] active:scale-95">
            {{ __('Visit Profile') }}
            <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
        </a>
    </div>
</div>
