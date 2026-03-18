@extends('frontend.layout-airbnb')

@section('pageHeading')
{{ !empty($pageHeading->room_checkout_page_title) ? $pageHeading->room_checkout_page_title : __('Konfirmasi dan Bayar') }}
@endsection

@php
    $roomContent = $room->room_content()->where('language_id', $language->id)->first();
@endphp

@section('content')
<div class="bg-white min-h-screen pb-20">
    <div class="max-w-[1280px] mx-auto xl:px-20 md:px-10 px-4 pt-12">
        
        {{-- Header Checkout --}}
        <div class="flex items-center gap-4 mb-10">
            <a href="{{ url()->previous() }}" class="p-2 hover:bg-neutral-100 rounded-full transition">
                <i class="fas fa-chevron-left text-sm"></i>
            </a>
            <h1 class="text-3xl font-bold text-neutral-900">{{ __('Konfirmasi dan bayar') }}</h1>
        </div>

        <form id="payment-form" action="{{ route('frontend.perahu.booking') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-col md:flex-row gap-16 relative">
                
                {{-- LEFT COLUMN: Forms --}}
                <div class="flex-[1.5] flex flex-col gap-10">
                    
                    {{-- Section 1: Trip Info Summary --}}
                    <div class="flex flex-col gap-6 pb-10 border-b border-neutral-200">
                        <h2 class="text-2xl font-semibold text-neutral-900">{{ __('Perjalanan Anda') }}</h2>
                         <div class="flex justify-between items-start">
                            <div class="flex flex-col">
                                <span class="font-bold text-sm">{{ __('Paket') }}</span>
                                <span class="text-neutral-600 font-light">{{ $package->name }} ({{ $package->duration_days }} {{ __('hari') }})</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-start">
                            <div class="flex flex-col">
                                <span class="font-bold text-sm">{{ __('Tanggal') }}</span>
                                <span class="text-neutral-600 font-light">{{ \Carbon\Carbon::parse($checkInDate)->format('d F Y') }}</span>
                            </div>
                            <button type="button" onclick="window.history.back()" class="text-sm font-bold underline">{{ __('Edit') }}</button>
                        </div>
                    </div>

                    {{-- Section 2: Billing Details --}}
                    <div class="flex flex-col gap-6 pb-10 border-b border-neutral-200">
                        <h2 class="text-2xl font-semibold text-neutral-900">{{ __('Data Pemesan') }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex flex-col gap-2">
                                <label class="text-[12px] font-bold uppercase text-neutral-500">{{ __('Nama Lengkap') }}</label>
                                <input type="text" name="booking_name" class="border-b border-neutral-300 focus:border-black transition-all py-2 outline-none font-light" 
                                       placeholder="{{ __('Masukkan Nama') }}" value="{{ !empty($authUser) ? $authUser->username : old('booking_name') }}">
                                @error('booking_name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-[12px] font-bold uppercase text-neutral-500">{{ __('Nomor Telepon') }}</label>
                                <input type="text" name="booking_phone" class="border-b border-neutral-300 focus:border-black transition-all py-2 outline-none font-light" 
                                       placeholder="{{ __('Contoh: 0812...') }}" value="{{ old('booking_phone') }}">
                                @error('booking_phone') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-[12px] font-bold uppercase text-neutral-500">{{ __('Email') }}</label>
                                <input type="email" name="booking_email" class="border-b border-neutral-300 focus:border-black transition-all py-2 outline-none font-light" 
                                       placeholder="{{ __('Alamat Email') }}" value="{{ !empty($authUser) ? $authUser->email : old('booking_email') }}">
                                @error('booking_email') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-[12px] font-bold uppercase text-neutral-500">{{ __('Alamat') }}</label>
                                <input type="text" name="booking_address" class="border-b border-neutral-300 focus:border-black transition-all py-2 outline-none font-light" 
                                       placeholder="{{ __('Alamat Lengkap') }}" value="{{ old('booking_address') }}">
                                @error('booking_address') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Section 3: Payment Method --}}
                    <div class="flex flex-col gap-6 pb-10 border-b border-neutral-200">
                        <div class="flex justify-between items-center">
                            <h2 class="text-2xl font-semibold text-neutral-900">{{ __('Pilih cara pembayaran') }}</h2>
                        </div>

                        {{-- Section 3.5: Additional Services (Custom) --}}
                        @if(!empty($additional_services) && count($additional_services) > 0)
                        <div class="bg-neutral-50 p-5 rounded-2xl border border-neutral-100 flex flex-col gap-4">
                            <h3 class="font-semibold text-neutral-900 text-sm">{{ __('Layanan Tambahan (Opsional)') }}</h3>
                            <div class="flex flex-col gap-3">
                                @foreach($additional_services as $service)
                                <label class="flex justify-between items-center cursor-pointer group">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" name="services[]" value="{{ $service->id }}" 
                                               class="w-4 h-4 rounded border-neutral-300 text-rose-500 focus:ring-rose-500 service-checkbox"
                                               data-price="{{ $service->price }}">
                                        <span class="text-sm text-neutral-700 font-light group-hover:text-black transition">{{ $service->title }}</span>
                                    </div>
                                    <span class="text-sm font-semibold text-neutral-900">+ {{ symbolPrice($service->price) }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($room->availability_mode == 2)
                            <div class="p-4 bg-blue-50 border border-blue-100 rounded-xl text-blue-800 text-sm font-light">
                                <i class="fas fa-info-circle mr-2"></i> {{ __('Pesanan ini memerlukan persetujuan Host. Anda belum akan ditagih biaya apa pun sekarang.') }}
                            </div>
                            <input type="hidden" name="gateway" value="approval">
                        @else
                            <div class="flex flex-col gap-3">

                                {{-- Online Gateways (Cards) --}}
                                @foreach ($onlineGateways as $og)
                                <label class="flex items-center gap-4 p-4 border-2 rounded-xl cursor-pointer transition-all has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50 border-neutral-200 hover:border-neutral-400 group">
                                    <input type="radio" name="gateway" value="{{ $og->keyword }}"
                                        class="w-4 h-4 accent-rose-500"
                                        {{ ($loop->first && count($onlineGateways) == 1) || old('gateway') == $og->keyword ? 'checked' : '' }}>
                                    <div class="flex items-center gap-3 flex-1">
                                        @if($og->keyword == 'midtrans')
                                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center shrink-0">
                                                <span class="text-white font-bold text-xs">QRIS</span>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-neutral-900">QRIS / Transfer Bank</p>
                                                <p class="text-xs text-neutral-500 font-light">Bayar via QRIS, Virtual Account, atau Kartu Kredit</p>
                                            </div>
                                        @elseif($og->keyword == 'xendit')
                                            <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center shrink-0">
                                                <span class="text-white font-bold text-xs">QRIS</span>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-neutral-900">QRIS / E-Wallet</p>
                                                <p class="text-xs text-neutral-500 font-light">Bayar via QRIS, OVO, GoPay, atau Dana</p>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 bg-neutral-100 rounded-lg flex items-center justify-center shrink-0">
                                                <i class="fas fa-credit-card text-neutral-500"></i>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-neutral-900">{{ $og->name }}</p>
                                                <p class="text-xs text-neutral-500 font-light">Pembayaran online</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex gap-1 shrink-0">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Logo_QRIS.svg/120px-Logo_QRIS.svg.png" alt="QRIS" class="h-5 object-contain opacity-70">
                                    </div>
                                </label>
                                @endforeach

                                {{-- Offline Gateways --}}
                                @foreach ($offline_gateways as $fg)
                                <label class="flex items-center gap-4 p-4 border-2 rounded-xl cursor-pointer transition-all has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50 border-neutral-200 hover:border-neutral-400">
                                    <input type="radio" name="gateway" value="{{ $fg->id }}"
                                        class="w-4 h-4 accent-rose-500"
                                        {{ old('gateway') == $fg->id ? 'checked' : '' }}>
                                    <div class="flex items-center gap-3 flex-1">
                                        <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center shrink-0">
                                            <i class="fas fa-university text-green-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-neutral-900">{{ $fg->name }}</p>
                                            <p class="text-xs text-neutral-500 font-light">Transfer manual, konfirmasi setelah pembayaran</p>
                                        </div>
                                    </div>
                                </label>

                                {{-- Instructions for this offline gateway --}}
                                <div class="hidden offline-info px-4 py-3 bg-neutral-50 rounded-xl border border-neutral-200 text-sm" id="offline-{{ $fg->id }}">
                                    <p class="font-semibold mb-2">{{ __('Instruksi Pembayaran:') }}</p>
                                    <div class="text-neutral-600 font-light mb-3">{!! $fg->instructions !!}</div>
                                    @if($fg->has_attachment == 1)
                                        <div class="flex flex-col gap-1">
                                            <label class="text-xs font-bold">{{ __('Upload Bukti Transfer') }} *</label>
                                            <input type="file" name="attachment" class="text-sm">
                                        </div>
                                    @endif
                                </div>
                                @endforeach

                                @if($onlineGateways->isEmpty() && $offline_gateways->isEmpty())
                                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-xl text-yellow-800 text-sm">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Belum ada metode pembayaran yang aktif. Hubungi admin.
                                </div>
                                @endif
                            </div>
                        @endif
                    </div>


                    {{-- Section 4: Mandatory Policies --}}
                    <div class="flex flex-col gap-6">
                        <h2 class="text-2xl font-semibold text-neutral-900">{{ __('Kebijakan Penting') }}</h2>
                        <div class="flex flex-col gap-4 text-sm font-light text-neutral-800 leading-relaxed">
                            <div class="flex items-start gap-3">
                                <input type="checkbox" name="age_confirmation" id="age_check" class="mt-1 accent-black h-4 w-4" required>
                                <label for="age_check">
                                    {{ __('Saya menyatakan bahwa saya berusia di atas 17 tahun untuk melakukan pemesanan ini.') }} *
                                </label>
                            </div>
                            <div class="flex items-start gap-3">
                                <input type="checkbox" name="terms" id="terms_check" class="mt-1 accent-black h-4 w-4" required>
                                <label for="terms_check">
                                    {{ __('Saya menyetujui') }} <a href="#" class="underline font-bold">{{ __('Aturan Dasar Tamu') }}</a>, {{ __('Kebijakan Pembatalan') }}, {{ __('dan Kebijakan Kerusakan.') }} *
                                </label>
                            </div>
                        </div>
                        <p class="text-[12px] text-neutral-500 font-light mt-4">
                            {{ __('Dengan memilih tombol di bawah ini, saya menyetujui seluruh aturan dan kebijakan yang berlaku.') }}
                        </p>
                        
                        <button type="submit" class="w-full md:w-64 py-4 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-xl transition duration-200 text-lg">
                            {{ $room->availability_mode == 2 ? __('Minta Persetujuan') : __('Konfirmasi dan Bayar') }}
                        </button>
                    </div>

                </div>

                {{-- RIGHT COLUMN: Sticky Order Summary --}}
                <div class="flex-1">
                    <div class="sticky top-28 p-6 rounded-2xl border border-neutral-200 shadow-lg bg-white flex flex-col gap-6">
                        
                        {{-- Boat Header --}}
                        <div class="flex gap-4 pb-6 border-b border-neutral-200">
                            <div class="h-16 w-24 rounded-lg overflow-hidden flex-shrink-0 bg-neutral-100">
                                <img src="{{ asset('assets/img/perahu/featureImage/' . $room->feature_image) }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex flex-col overflow-hidden">
                                <span class="text-xs text-neutral-500 font-light truncate">KM {{ $room->nama_km ?? 'Perahu' }} / {{ $roomContent->room_category_name ?? 'Armada' }}</span>
                                <span class="text-sm font-semibold text-neutral-900 truncate">{{ $roomContent->title }}</span>
                                <div class="flex items-center gap-1 text-[10px] mt-1">
                                    <i class="fas fa-star text-[8px]"></i>
                                    <span class="font-bold">{{ number_format($room->average_rating, 1) }}</span>
                                    <span class="text-neutral-500 ml-1">· {{ __('Kapt.') }} {{ $room->captain_name ?? 'Profesional' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Price Details --}}
                        <div class="flex flex-col gap-3 pb-6 border-b border-neutral-200">
                            <h3 class="text-lg font-semibold text-neutral-900">{{ __('Rincian harga') }}</h3>
                            <div class="flex justify-between font-light text-neutral-800">
                                <span>{{ $package->name }}</span>
                                <span id="base-price" data-value="{{ (float)$package->price }}">{{ symbolPrice($package->price) }}</span>
                            </div>

                            @php 
                                $taxData = App\Models\BasicSettings\Basic::select('hotel_tax_amount')->first();
                                $taxPercent = (float)($taxData->hotel_tax_amount ?? 0);
                            @endphp

                            <div id="services-summary" class="flex flex-col gap-3" style="display: none;">
                                {{-- Dynamically filled via JS --}}
                            </div>

                            <div class="flex justify-between font-light text-neutral-800 border-t border-dotted border-neutral-200 pt-3">
                                <span class="underline">{{ __('Pajak') }} (<span id="tax-percent" data-value="{{ $taxPercent }}">{{ number_format($taxPercent, 3, '.', '') + 0 }}</span>%)</span>
                                <span id="tax-amount"></span>
                            </div>
                        </div>

                        {{-- Total --}}
                        <div class="flex justify-between items-center text-lg font-bold text-neutral-900">
                            <span>{{ __('Total (IDR)') }}</span>
                            <span id="grand-total">—</span>
                        </div>

                        {{-- Cancellation Info --}}
                        <div class="p-4 bg-rose-50 rounded-xl text-[12px] font-light text-rose-900 leading-relaxed">
                            <i class="fas fa-umbrella mr-2"></i> {{ __('Kebijakan cuaca buruk: reschedule gratis jika syahbandar melarang pelayaran.') }}
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Selector Helpers
    const getEl = (id) => document.getElementById(id);
    const getAll = (sel) => document.querySelectorAll(sel);

    // 2. State & Formatters
    const formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });

    const formatIDR = (amount) => formatter.format(amount).replace('IDR', 'Rp').trim();

    // 3. Calculation Core
    function updatePrices() {
        const basePriceEl = getEl('base-price');
        const taxPercentEl = getEl('tax-percent');
        const summaryEl = getEl('services-summary');
        const taxAmountEl = getEl('tax-amount');
        const grandTotalEl = getEl('grand-total');

        if (!basePriceEl || !taxPercentEl) return;

        const basePrice = parseFloat(basePriceEl.getAttribute('data-value')) || 0;
        const taxPercent = parseFloat(taxPercentEl.getAttribute('data-value')) || 0;
        let servicesTotal = 0;
        
        // Reset Summary
        summaryEl.innerHTML = '';
        summaryEl.style.display = 'none';
        
        // Loop through checked services
        getAll('.service-checkbox:checked').forEach(checkbox => {
            const price = parseFloat(checkbox.getAttribute('data-price')) || 0;
            const label = checkbox.closest('label');
            const name = label ? label.querySelector('span.text-sm').textContent.trim() : 'Layanan';
            
            servicesTotal += price;
            
            // Build summary row
            const row = document.createElement('div');
            row.className = 'flex justify-between font-light text-neutral-800 animate-in fade-in slide-in-from-top-1 duration-200';
            row.innerHTML = `<span>${name}</span><span>${formatIDR(price)}</span>`;
            summaryEl.appendChild(row);
            summaryEl.style.display = 'flex';
        });

        const subtotal = basePrice + servicesTotal;
        const tax = subtotal * (taxPercent / 100);
        const grandTotal = subtotal + tax;

        if (taxAmountEl) taxAmountEl.textContent = formatIDR(tax);
        if (grandTotalEl) grandTotalEl.textContent = formatIDR(grandTotal);
    }

    // 4. Event Listeners
    // Handle Gateway Change
    const gatewaySelect = getEl('gateway');
    if (gatewaySelect) {
        gatewaySelect.addEventListener('change', function() {
            const val = this.value;
            getAll('.offline-info').forEach(el => el.classList.add('hidden'));
            if (val && !isNaN(val)) {
                const infoEl = getEl(`offline-${val}`);
                if (infoEl) infoEl.classList.remove('hidden');
            }
        });
    }

    // Handle Service Checkboxes (Event Delegation for robustness)
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('service-checkbox')) {
            updatePrices();
        }
    });
    
    // 5. Initial Execution
    updatePrices();
});
</script>
@endsection
