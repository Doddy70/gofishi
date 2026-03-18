@extends('frontend.layout-airbnb')

@section('pageHeading')
  {{ $vendor->username }} - Certified Fishing Captain
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    
    {{-- Captain Header Card --}}
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-2xl p-8 md:p-12 mb-12 relative overflow-hidden group">
        <div class="absolute -right-20 -top-20 w-96 h-96 bg-rose-50 rounded-full blur-3xl opacity-40 group-hover:scale-110 transition duration-1000"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start gap-10">
            {{-- Avatar & Badge --}}
            <div class="relative shrink-0">
                <div class="w-40 h-40 rounded-full overflow-hidden border-8 border-white shadow-xl bg-gray-50">
                    @if ($vendor_id == 0)
                        <img src="{{ asset('assets/img/admins/' . $vendor->image) }}" class="w-full h-full object-cover" alt="Captain">
                    @else
                        <img src="{{ $vendor->photo ? asset('assets/admin/img/vendor-photo/' . $vendor->photo) : asset('assets/img/blank-user.jpg') }}" 
                             class="w-full h-full object-cover" alt="Captain">
                    @endif
                </div>
                @if(@$vendorInfo->license_number)
                    <div class="absolute bottom-2 right-2 w-10 h-10 bg-blue-500 rounded-full border-4 border-white flex items-center justify-center text-white shadow-lg" title="USCG Licensed">
                        <i data-lucide="check" class="w-5 h-5"></i>
                    </div>
                @endif
            </div>

            {{-- Info Content --}}
            <div class="flex-grow text-center md:text-left">
                <div class="flex flex-col md:flex-row md:items-center gap-4 mb-4">
                    <h1 class="text-4xl font-black text-gray-900 tracking-tight">{{ $vendor->username }}</h1>
                    @if(@$vendorInfo->license_number)
                        <span class="inline-flex items-center px-4 py-1.5 bg-blue-50 text-blue-600 rounded-full text-xs font-bold uppercase tracking-widest border border-blue-100">
                            <i data-lucide="shield-check" class="w-3.5 h-3.5 mr-2"></i>
                            Certified Captain
                        </span>
                    @endif
                </div>
                
                <div class="flex flex-wrap justify-center md:justify-start gap-6 text-gray-500 font-light mb-8">
                    <div class="flex items-center">
                        <i data-lucide="map-pin" class="w-4 h-4 mr-2 text-airbnb-red"></i>
                        {{ @$vendorInfo->city ?: 'Coastal Base' }}, {{ @$vendorInfo->country }}
                    </div>
                    <div class="flex items-center">
                        <i data-lucide="calendar" class="w-4 h-4 mr-2 text-airbnb-red"></i>
                        Member since {{ \Carbon\Carbon::parse($vendor->created_at)->format('Y') }}
                    </div>
                    <div class="flex items-center">
                        <i data-lucide="anchor" class="w-4 h-4 mr-2 text-airbnb-red"></i>
                        {{ count($hotel_contents) }} Active Listings
                    </div>
                </div>

                <div class="flex flex-wrap justify-center md:justify-start gap-4">
                    <button class="px-8 py-3.5 bg-gray-900 text-white font-bold rounded-2xl hover:bg-black transition shadow-lg transform active:scale-95 flex items-center gap-3" 
                            data-bs-toggle="modal" data-bs-target="#contactModal">
                        <i data-lucide="message-square" class="w-5 h-5"></i>
                        {{ __('Tanya Kapten') }}
                    </button>
                    @if($vendor->phone && ($vendor->show_phone_number == 1))
                        <a href="tel:{{ $vendor->phone }}" class="px-8 py-3.5 bg-white border border-gray-200 text-gray-700 font-bold rounded-2xl hover:bg-gray-50 transition transform active:scale-95 flex items-center gap-3">
                            <i data-lucide="phone" class="w-5 h-5"></i>
                            {{ __('WhatsApp') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        
        {{-- Left: Bio & Stats --}}
        <div class="lg:col-span-4 space-y-10">
            <div class="p-8 bg-white rounded-3xl border border-gray-100 shadow-sm">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i data-lucide="info" class="w-5 h-5 mr-3 text-airbnb-red"></i>
                    {{ __('Tentang Kapten') }}
                </h3>
                <p class="text-gray-500 leading-relaxed font-light mb-8">
                    {{ @$vendorInfo->details ?: __('Kapten ini berdedikasi untuk memberikan pengalaman memancing di laut dalam yang tak tertandingi dengan pengetahuan lokal yang mendalam.') }}
                </p>

                <div class="space-y-6">
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">{{ __('Spesialisasi') }}</h4>
                        <div class="flex flex-wrap gap-2">
                            @forelse(explode(',', @$vendorInfo->specializations ?? 'Fly Fishing,Deep Sea,Trolling') as $spec)
                                <span class="px-3 py-1.5 bg-gray-50 text-gray-600 rounded-xl text-xs font-medium border border-gray-100">
                                    {{ trim($spec) }}
                                </span>
                            @empty
                                <span class="text-gray-400 italic text-sm">General</span>
                            @endforelse
                        </div>
                    </div>

                    @php
                        $license = @$vendorInfo->license_number;
                    @endphp
                    @if($license)
                        <div class="p-4 bg-blue-50/50 rounded-2xl border border-blue-100 border-dashed">
                            <div class="flex items-center gap-3 text-blue-700">
                                <i data-lucide="award" class="w-5 h-5"></i>
                                <div class="text-xs font-bold leading-none">
                                    <p class="mb-1 text-[10px] uppercase opacity-70">License Verified</p>
                                    <p>{{ $license }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @include('frontend.partials._trust_badges')
        </div>

        {{-- Right: Gallery & Listings --}}
        <div class="lg:col-span-8 space-y-16">
            
            {{-- Big Catch Gallery --}}
            <section>
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-bold text-gray-900">{{ __('Hasil Tangkapan Utama') }}</h3>
                    <div class="h-px bg-gray-100 flex-grow mx-6"></div>
                    <i data-lucide="trophy" class="w-6 h-6 text-yellow-500"></i>
                </div>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-6">
                    @forelse($captain_galleries as $gallery)
                        <a href="{{ asset('assets/img/captain/gallery/' . $gallery->image) }}" 
                           class="group relative aspect-square rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition block border-4 border-white">
                            <img src="{{ asset('assets/img/captain/gallery/' . $gallery->image) }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-700" 
                                 alt="{{ $gallery->title }}">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex flex-col justify-end p-4">
                                <p class="text-white text-sm font-bold truncate">{{ $gallery->title }}</p>
                                <p class="text-white/80 text-[10px] font-medium">{{ $gallery->weight }}</p>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full py-20 text-center bg-gray-50 rounded-3xl border border-dashed border-gray-200">
                            <i data-lucide="camera" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
                            <p class="text-gray-400 font-light">{{ __('Kapten belum mengunggah foto hasil tangkapan.') }}</p>
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- Charter Listings --}}
            <section>
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-bold text-gray-900">{{ __('Daftar Charter Aktif') }}</h3>
                    <div class="h-px bg-gray-100 flex-grow mx-6"></div>
                    <i data-lucide="anchor" class="w-6 h-6 text-blue-500"></i>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @forelse ($hotel_contents as $boat)
                        <div class="group bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-500 overflow-hidden">
                            <div class="aspect-[16/10] bg-gray-100 overflow-hidden relative">
                                <img src="{{ asset('assets/img/hotel/logo/' . $boat->logo) }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-700" 
                                     alt="{{ $boat->title }}">
                                <div class="absolute top-4 left-4">
                                    <span class="px-4 py-1.5 bg-white/90 backdrop-blur-md rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-900 shadow-sm">
                                        {{ $boat->categoryName }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-6">
                                <h4 class="text-lg font-bold text-gray-900 mb-2 truncate group-hover:text-airbnb-red transition">{{ $boat->title }}</h4>
                                <div class="flex items-center text-xs text-gray-400 mb-6">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 mr-1.5 text-airbnb-red"></i>
                                    {{ $boat->address }}
                                </div>
                                <a href="{{ route('frontend.lokasi.details', ['slug' => $boat->slug, 'id' => $boat->id ?? $boat->hotel_id]) }}" 
                                   class="flex items-center justify-center w-full py-3 bg-gray-50 text-gray-900 font-bold rounded-xl hover:bg-airbnb-red hover:text-white transition shadow-sm">
                                    {{ __('Lihat Detail Charter') }}
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center bg-gray-50 rounded-3xl">
                            <p class="text-gray-400">{{ __('Tidak ada charter yang aktif saat ini.') }}</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</div>

@include('frontend.vendor.contact-modal')
@endsection
