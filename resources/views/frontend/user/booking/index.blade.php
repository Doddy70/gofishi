@extends('frontend.layout-airbnb')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->room_bookings_page_title ?? __('My Bookings') }}
  @else
    {{ __('My Bookings') }}
  @endif
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="mb-8">
      <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ __('My Bookings') }}</h1>
      <p class="text-gray-600">
        @if(count($bookings) > 0)
          {{ __('You have') }} {{ count($bookings) }} {{ __('bookings in your history') }}
        @else
          {{ __('You haven\'t made any bookings yet') }}
        @endif
      </p>
    </div>

    {{-- Filter Tabs (Conceptual for now as Laravel handles filtering via query params usually) --}}
    <div class="mb-6 flex space-x-2 overflow-x-auto no-scrollbar">
        <a href="{{ route('user.perahu_bookings') }}" class="px-6 py-2 rounded-lg font-medium transition whitespace-nowrap {{ !request()->status ? 'bg-airbnb-red text-white' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200' }}">
            {{ __('All Bookings') }}
        </a>
        <a href="{{ route('user.perahu_bookings', ['status' => 'pending']) }}" class="px-6 py-2 rounded-lg font-medium transition whitespace-nowrap {{ request()->status == 'pending' ? 'bg-airbnb-red text-white' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200' }}">
            {{ __('Pending') }}
        </a>
        <a href="{{ route('user.perahu_bookings', ['status' => 'approved']) }}" class="px-6 py-2 rounded-lg font-medium transition whitespace-nowrap {{ request()->status == 'approved' ? 'bg-airbnb-red text-white' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200' }}">
            {{ __('Upcoming') }}
        </a>
    </div>

    {{-- Bookings List --}}
    @if(count($bookings) == 0)
      <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-16 text-center">
        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-300">
            <i data-lucide="calendar" class="w-10 h-10"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ __('No bookings found') }}</h2>
        <p class="text-gray-500 font-light mb-8 max-w-sm mx-auto">
          {{ __('You haven\'t made any bookings yet. Start exploring amazing properties!') }}
        </p>
        <a href="{{ route('frontend.perahu') }}" 
           class="inline-block px-8 py-3 bg-airbnb-red text-white rounded-xl font-bold hover:bg-rose-600 transition shadow-md active:scale-95">
          {{ __('Explore Boats') }}
        </a>
      </div>
    @else
      <div class="space-y-6">
        @foreach($bookings as $booking)
            @php
                $room = $booking->hotelRoom; // Based on the original view's relationship
                $roomContent = $room ? $room->room_content()->where('language_id', $currentLanguageInfo->id)->first() : null;
                
                // Status Logic
                $statusText = ucfirst($booking->order_status);
                $statusColor = 'bg-gray-100 text-gray-800';
                if($booking->order_status == 'pending') $statusColor = 'bg-amber-100 text-amber-800';
                elseif($booking->order_status == 'approved') $statusColor = 'bg-green-100 text-green-800';
                elseif($booking->order_status == 'rejected') $statusColor = 'bg-red-100 text-red-800';
            @endphp
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                <div class="flex flex-col md:flex-row">
                    {{-- Image --}}
                    <div class="md:w-1/3 lg:w-1/4 h-48 md:h-auto overflow-hidden bg-gray-100">
                        @if($room && $room->feature_image)
                            <img src="{{ asset('assets/img/perahu/featureImage/' . $room->feature_image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <i data-lucide="image" class="w-12 h-12"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 p-6 sm:p-8">
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 group-hover:text-airbnb-red transition">
                                    {{ $roomContent->title ?? __('Boat Rental') }}
                                </h3>
                                <p class="text-gray-500 font-light text-sm flex items-center gap-1 mt-1">
                                    <i data-lucide="map-pin" class="w-3 h-3"></i>
                                    {{ $booking->address ?? ($roomContent->address ?? __('Jakarta Utara')) }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2 px-4 py-1.5 rounded-full text-[10px] font-bold uppercase {{ $statusColor }} self-start">
                                <span class="w-2 h-2 rounded-full bg-current opacity-50"></span>
                                {{ $statusText }}
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8 pb-6 border-b border-gray-100">
                            <div>
                                <p class="text-[10px] font-extrabold text-gray-400 uppercase mb-1">{{ __('Check-in') }}</p>
                                <p class="font-bold text-gray-900 flex items-center gap-2">
                                    <i data-lucide="calendar" class="w-4 h-4 text-airbnb-red"></i>
                                    {{ Carbon\Carbon::parse($booking->arrival_date)->format('d M Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] font-extrabold text-gray-400 uppercase mb-1">{{ __('Check-out') }}</p>
                                <p class="font-bold text-gray-900 flex items-center gap-2">
                                    <i data-lucide="calendar" class="w-4 h-4 text-airbnb-red"></i>
                                    {{ Carbon\Carbon::parse($booking->departure_date)->format('d M Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] font-extrabold text-gray-400 uppercase mb-1">{{ __('Tamu') }}</p>
                                <p class="font-bold text-gray-900 flex items-center gap-2">
                                    <i data-lucide="users" class="w-4 h-4 text-airbnb-red"></i>
                                    {{ $booking->adult }} {{ __('Tamu') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                            <div>
                                <p class="text-xs text-gray-400 uppercase font-bold">{{ __('Total Pembayaran') }}</p>
                                <p class="text-2xl font-black text-gray-900">
                                    {{ symbolPrice($booking->grand_total) }}
                                </p>
                            </div>
                            <div class="flex items-center gap-3 w-full sm:w-auto">
                                <a href="{{ route('user.perahu_booking_details', ['id' => $booking->id]) }}" 
                                   class="flex-1 sm:flex-none text-center px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition font-bold text-sm">
                                    {{ __('Details') }}
                                </a>
                                @if ($booking->order_status == 'approved' && $booking->payment_status == 0)
                                    <a href="{{ route('user.perahu_bookings', ['id' => $booking->id]) }}" 
                                       class="flex-1 sm:flex-none text-center px-6 py-2.5 bg-green-600 text-white rounded-xl hover:bg-green-700 transition font-bold text-sm shadow-sm">
                                        {{ __('Bayar Sekarang') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
      </div>
    @endif

    {{-- Help Card --}}
    <div class="mt-12 bg-gradient-to-r from-rose-50 to-orange-50 border border-rose-100 rounded-2xl p-8 flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('Butuh Bantuan?') }}</h3>
            <p class="text-gray-600 font-light max-w-xl">
              {{ __('Jika Anda memiliki pertanyaan tentang pemesanan Anda atau perlu membuat perubahan, jangan ragu untuk menghubungi host perahu atau tim dukungan kami.') }}
            </p>
        </div>
        <a href="{{ route('contact') }}" class="px-8 py-3 bg-white text-gray-900 border border-gray-200 rounded-xl font-bold hover:shadow-md transition">
            {{ __('Hubungi Kami') }}
        </a>
    </div>
  </div>
</div>
@endsection
