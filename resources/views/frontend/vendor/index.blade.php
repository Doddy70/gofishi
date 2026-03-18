@extends('frontend.layout-airbnb')

@section('pageHeading')
  {{ !empty($pageHeading) ? $pageHeading->vendor_page_title : __('Our Partners & Vendors') }}
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20" x-data="{ expanded: false }">
    
    {{-- Search Header --}}
    <div class="bg-gray-50 rounded-3xl p-10 mb-16 border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-rose-50 rounded-full blur-3xl opacity-50"></div>
        <div class="relative z-10">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">
                {{ __('Temukan Mitra & Kapten Terbaik') }}
            </h1>
            <p class="text-gray-500 font-light mb-10 max-w-xl">
                {{ __('Pelajari lebih lanjut tentang vendor kapal berlisensi kami yang siap memberikan pengalaman memancing terbaik untuk Anda.') }}
            </p>

            <form action="{{ route('frontend.vendors') }}" method="GET" class="flex flex-col md:flex-row items-center gap-4 bg-white p-3 rounded-2xl shadow-xl border border-gray-100 max-w-3xl">
                <div class="flex-grow flex items-center px-4 w-full">
                    <i data-lucide="user" class="w-5 h-5 text-gray-400 mr-3"></i>
                    <input type="text" name="name" value="{{ request('name') }}"
                           class="w-full py-3 outline-none bg-transparent text-sm font-medium border-none focus:ring-0" 
                           placeholder="{{ __('Nama Vendor atau Username') }}">
                </div>
                <div class="hidden md:block w-px h-8 bg-gray-100 mx-2"></div>
                <div class="flex-grow flex items-center px-4 w-full">
                    <i data-lucide="map-pin" class="w-5 h-5 text-gray-400 mr-3"></i>
                    <input type="text" name="location" value="{{ request('location') }}"
                           class="w-full py-3 outline-none bg-transparent text-sm font-medium border-none focus:ring-0" 
                           placeholder="{{ __('Lokasi (Kota/Dermaga)') }}">
                </div>
                <button type="submit" class="w-full md:w-auto px-8 py-3 bg-airbnb-red text-white font-bold rounded-xl hover:bg-rose-600 transition shadow-md flex items-center justify-center gap-2">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    {{ __('Cari') }}
                </button>
            </form>
        </div>
    </div>

    {{-- Vendors Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @if ($admin)
            @include('frontend.vendor._card', ['vendor' => $admin, 'isAdmin' => true])
        @endif

        @forelse ($vendors as $vendor)
            @include('frontend.vendor._card', ['vendor' => $vendor, 'isAdmin' => false])
        @empty
            <div class="col-span-full py-20 text-center bg-gray-50 rounded-3xl border border-dashed border-gray-200">
                <i data-lucide="users" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
                <h6 class="text-gray-500 font-medium">{{ __('Tidak ada vendor yang ditemukan.') }}</h6>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-16 flex justify-center">
        {{ $vendors->links('pagination::tailwind') }}
    </div>

    @if (!empty(showAd(3)))
        <div class="mt-20 flex justify-center">
            {!! showAd(3) !!}
        </div>
    @endif
</div>
@endsection
