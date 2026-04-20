@extends('frontend.layout-airbnb')

@section('pageHeading')
{{ !empty($pageHeading->room_checkout_page_title) ? $pageHeading->room_checkout_page_title : __('Konfirmasi dan Bayar') }}
@endsection

@php
    $roomContent = $room->room_content()->where('language_id', $language->id)->first();
    $roomCategory = $roomContent->room_category_name ?? __('Armada');
    $roomTitle = $roomContent->title ?? __('Perahu');
    $taxData = App\Models\BasicSettings\Basic::select('hotel_tax_amount')->first();
    $taxPercent = (float)($taxData->hotel_tax_amount ?? 0);
    // Airbnb-like behavior:
    // - Guest always starts at Step 1 (auth)
    // - Logged-in user may resume on Step 2 (or explicit valid step from query)
    $requestedStep = (int) request()->query('step', 2);
    if ($requestedStep < 1 || $requestedStep > 3) {
        $requestedStep = 2;
    }
    $initialStep = $authUser ? $requestedStep : 1;
    $needsCheckoutProfile = (session()->has('checkout_complete_profile') || $errors->has('dob') || $errors->has('age_agreement') || $errors->has('first_name') || $errors->has('last_name')) && $authUser;
    $initialAuthTab = old('form_type') === 'signup' ? 'signup' : 'login';
    $openAuthModal = !$authUser && in_array(old('form_type'), ['login', 'signup'], true);
@endphp

@section('content')
<div class="bg-white min-h-screen pb-20" x-data="{ 
    step: {{ $initialStep }}, 
    showAuthModal: {{ $openAuthModal ? 'true' : 'false' }},
    showProfileCompletionModal: {{ $needsCheckoutProfile ? 'true' : 'false' }},
    showPaymentInfo: false,
    authTab: '{{ $initialAuthTab }}',
    paymentPlan: 'full',
    isLoggedIn: {{ Auth::guard('web')->check() ? 'true' : 'false' }},
    photoPreview: null,
    showPhotoPreviewModal: false,
    
    // Validation States
    guestMessage: '',
    errorStep3: false,
    errorStep4: false,
    policyAge: false,
    policyTerms: false,
    errorStep5: false,
    
    // Global Error Modal
    showErrorModal: false,
    errorModalTitle: '',
    errorModalText: '',
    errorModalActionText: '',
    errorModalActionStep: 1,
    
    // Recovery System
    showRecoveryModal: false,
    draftedStep: 1,

    init() {
        this.loadDraft();

        // Background Auto-Save Listeners
        this.$watch('step', val => this.saveDraft());
        this.$watch('guestMessage', val => this.saveDraft());
        this.$watch('paymentPlan', val => this.saveDraft());
        this.$watch('policyAge', val => this.saveDraft());
        this.$watch('policyTerms', val => this.saveDraft());
    },
    
    saveDraft() {
        if(this.step === 6) return; // Disengage auto-save at the final review point to avoid collision post-submit
        const draftKey = 'gofishi_booking_draft_id' + '{{ $room->id ?? '0' }}';
        localStorage.setItem(draftKey, JSON.stringify({
            step: this.step,
            msg: this.guestMessage,
            plan: this.paymentPlan,
            age: this.policyAge,
            terms: this.policyTerms
        }));
    },
    
    loadDraft() {
        const draftKey = 'gofishi_booking_draft_id' + '{{ $room->id ?? '0' }}';
        const rawSaved = localStorage.getItem(draftKey);
        if(rawSaved) {
            try {
                let data = JSON.parse(rawSaved);
                if(data.step > 1 && data.step <= 6) {
                    this.guestMessage = data.msg || '';
                    this.paymentPlan = data.plan || 'full';
                    this.policyAge = data.age || false;
                    this.policyTerms = data.terms || false;
                    
                    this.draftedStep = data.step;
                    this.showRecoveryModal = true;
                }
            } catch(e) {}
        }
    },
    
    recoverSession() {
        this.step = this.draftedStep;
        this.showRecoveryModal = false;
        
        // Suppress any stray validation UI
        this.errorStep3 = false;
        this.errorStep4 = false;
        this.errorStep5 = false;
    },
    
    discardSession() {
        const draftKey = 'gofishi_booking_draft_id' + '{{ $room->id ?? '0' }}';
        localStorage.removeItem(draftKey);
        this.showRecoveryModal = false;
    },

    next() { 
        if(!this.isLoggedIn) {
            this.showAuthModal = true;
            return;
        }

        // Validate Step 3: Pesan Kapten
        if(this.step === 3) {
            if(this.guestMessage.trim() === '') {
                this.errorStep3 = true;
                this.showErrorModal = true;
                this.errorModalTitle = 'Info tambahan diperlukan';
                this.errorModalText = 'Tambahkan pesan kepada Kapten untuk melanjutkan pemesanan.';
                this.errorModalActionText = 'Tambahkan pesan';
                this.errorModalActionStep = 3;
                return;
            }
            this.errorStep3 = false;
        }

        // Validate Step 4: Foto Profil
        if(this.step === 4) {
            if(!this.photoPreview) {
                this.errorStep4 = true;
                this.showErrorModal = true;
                this.errorModalTitle = 'Info tambahan diperlukan';
                this.errorModalText = 'Tambahkan foto profil Anda untuk melanjutkan pemesanan. Kapten berhak mengetahui siapa penumpangnya.';
                this.errorModalActionText = 'Tambahkan foto';
                this.errorModalActionStep = 4;
                return;
            }
            this.errorStep4 = false;
        }

        // Validate Step 5: Policies
        if(this.step === 5) {
            if(!this.policyAge || !this.policyTerms) {
                this.errorStep5 = true;
                this.showErrorModal = true;
                this.errorModalTitle = 'Baca kebijakan terkait keamanan';
                this.errorModalText = 'Untuk melanjutkan ke proses pembayaran, harap setujui kebijakan kami terkait operasional laut dan kerusakan penunjang.';
                this.errorModalActionText = 'Tinjau kebijakan';
                this.errorModalActionStep = 5;
                return;
            }
            this.errorStep5 = false;
        }

        if(this.step < 6) this.step++; 
    },
    goTo(s) { 
        if(this.isLoggedIn || s === 1) {
            this.step = s; 
            if(s === 3) this.errorStep3 = false;
            if(s === 4) this.errorStep4 = false;
            if(s === 5) this.errorStep5 = false;
        }
    },
    handlePhotoUpload(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.photoPreview = e.target.result;
                this.showPhotoPreviewModal = true;
            };
            reader.readAsDataURL(file);
        }
    }
}">
    <div class="max-w-[1280px] mx-auto xl:px-20 md:px-10 px-4 pt-12">
        
        {{-- Header Checkout (Airbnb Style) --}}
        <div class="flex items-center gap-6 mb-12">
            <button onclick="window.history.back()" class="p-2 hover:bg-neutral-100 rounded-full transition group">
                <i class="fas fa-chevron-left text-sm group-hover:-translate-x-0.5 transition-transform"></i>
            </button>
            <h1 class="text-3xl font-bold text-neutral-900 tracking-tight">{{ __('Konfirmasikan dan bayar') }}</h1>
        </div>

        <form id="payment-form" action="{{ route('frontend.perahu.booking') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-col md:flex-row gap-16 relative">
                
                {{-- LEFT COLUMN: Steps Accordion --}}
                <div class="flex-[1.5] flex flex-col gap-4">
                    
                    {{-- Auth Notice (Appears if Guest) --}}
                    @if (!$authUser)
                    <div class="mb-6 p-6 border border-neutral-200 rounded-3xl flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-white shadow-sm">
                        <div class="flex-1">
                            <p class="text-xl font-bold text-neutral-900 mb-1">{{ __('Masuk atau daftar untuk memesan') }}</p>
                            <p class="text-[15px] text-neutral-500 font-light">{{ __('Dapatkan akses cepat ke riwayat pemesanan dan isi data otomatis.') }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="button" @click="showAuthModal = true" class="px-6 py-3 border-2 border-neutral-900 rounded-xl font-bold text-[14px] hover:bg-neutral-50 transition-colors">
                                {{ __('Masuk') }}
                            </button>
                        </div>
                    </div>
                    @endif

                    {{-- Step 1: Payment Schedule --}}
                    <div class="border border-neutral-200 rounded-3xl overflow-hidden transition-all duration-300" 
                         :class="step === 1 ? 'shadow-xl border-neutral-300 ring-1 ring-black/5' : 'bg-white opacity-90'">
                        <div @click="goTo(1)" class="p-8 cursor-pointer group flex justify-between items-center bg-white">
                            <h2 class="text-[22px] font-semibold text-neutral-900 tracking-tight flex items-center">
                                <span class="mr-4 text-neutral-400 font-light" :class="step > 1 ? 'text-emerald-500' : ''">
                                    <template x-if="step > 1"><i class="fas fa-check-circle"></i></template>
                                    <template x-if="step <= 1">1.</template>
                                </span>
                                {{ __('Pilih tanggal pembayaran') }}
                            </h2>
                            <template x-if="step > 1">
                                <button type="button" class="text-sm font-bold underline text-neutral-900">{{ __('Ubah') }}</button>
                            </template>
                        </div>
                        
                        <div x-show="step === 1" x-collapse class="px-8 pb-10 pt-4">
                            <div class="border border-neutral-200 rounded-2xl overflow-hidden bg-white shadow-sm">
                                {{-- Option: Pay Full --}}
                                <label @click="paymentPlan = 'full'" class="flex justify-between items-center p-6 cursor-pointer transition-all hover:bg-neutral-50/50 group border-b border-neutral-100"
                                       :class="paymentPlan === 'full' ? 'bg-neutral-50/30' : ''">
                                    <div class="flex flex-col gap-1 pr-12">
                                        <p class="font-bold text-neutral-900 text-[16px]">{{ __('Bayar penuh sekarang') }} <span id="step-1-total">—</span></p>
                                        <p class="text-[14px] text-neutral-500 font-light">{{ __('Bayar penuh sekarang untuk mengonfirmasi pesanan Anda seketika.') }}</p>
                                    </div>
                                    <div class="relative flex items-center justify-center flex-shrink-0">
                                        <input type="radio" value="full" name="payment_plan" x-model="paymentPlan" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                                        <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all duration-200"
                                             :class="paymentPlan === 'full' ? 'border-black' : 'border-neutral-300'">
                                            <div x-show="paymentPlan === 'full'" class="w-3 h-3 rounded-full bg-black animate-in zoom-in duration-200"></div>
                                        </div>
                                    </div>
                                </label>
                                
                                {{-- Option: Pay Part --}}
                                <label @click="paymentPlan = 'part'" class="flex justify-between items-center p-6 cursor-pointer transition-all hover:bg-neutral-50/50 group border-b border-neutral-100"
                                       :class="paymentPlan === 'part' ? 'bg-neutral-50/30' : ''">
                                    <div class="flex flex-col gap-1 pr-12">
                                        <p class="font-bold text-neutral-900 text-[16px]">{{ __('Bayar sebagian sekarang, sebagian lagi nanti') }}</p>
                                        <div class="text-[14px] text-neutral-500 font-light leading-relaxed">
                                            <span id="part-payment-now">—</span> {{ __('sekarang,') }} <span id="part-payment-next">—</span> {{ __('akan ditagih tanggal') }} <span class="font-medium text-neutral-800">{{ \Carbon\Carbon::now()->addMonth()->translatedFormat('d F') }}</span>. 
                                            {{ __('Tanpa biaya tambahan.') }} 
                                            <button type="button" @click.stop="showPaymentInfo = true" class="font-bold underline text-neutral-900 hover:text-black focus:outline-none">{{ __('Info selengkapnya') }}</button>
                                        </div>
                                    </div>
                                    <div class="relative flex items-center justify-center flex-shrink-0">
                                        <input type="radio" value="part" name="payment_plan" x-model="paymentPlan" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                                        <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all duration-200"
                                             :class="paymentPlan === 'part' ? 'border-black' : 'border-neutral-300'">
                                            <div x-show="paymentPlan === 'part'" class="w-3 h-3 rounded-full bg-black animate-in zoom-in duration-200"></div>
                                        </div>
                                    </div>
                                </label>

                                {{-- Option: Pay Rp0 (Disabled) --}}
                                <div class="flex justify-between items-center p-6 bg-neutral-50/30 opacity-40 cursor-not-allowed">
                                    <div class="flex flex-col gap-1 pr-12">
                                        <p class="font-bold text-neutral-300 text-[16px]">{{ __('Bayar Rp0 sekarang') }}</p>
                                        <p class="text-[14px] text-neutral-300 font-light">{{ __('Opsi ini tidak tersedia untuk paket ini.') }}</p>
                                    </div>
                                    <div class="w-6 h-6 rounded-full border-2 border-neutral-100 flex-shrink-0"></div>
                                </div>
                            </div>
                            
                            <div class="mt-8 flex justify-end">
                                <button type="button" @click="next()" class="px-10 py-3.5 bg-neutral-900 text-white font-bold rounded-xl hover:bg-black transition-all active:scale-95 shadow-lg opacity-100 visible">
                                    {{ __('Berikutnya') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Step 2: Payment Method --}}
                    <div class="border border-neutral-200 rounded-3xl overflow-hidden transition-all duration-300"
                         :class="step === 2 ? 'shadow-xl border-neutral-300 ring-1 ring-black/5' : 'bg-white opacity-90'">
                        <div @click="goTo(2)" class="p-8 cursor-pointer flex justify-between items-center bg-white" :class="step < 2 ? 'pointer-events-none' : ''">
                            <h2 class="text-[22px] font-semibold text-neutral-900 tracking-tight flex items-center" :class="step < 2 ? 'text-neutral-300' : ''">
                                <span class="mr-4 text-neutral-300 font-light" :class="step > 2 ? 'text-emerald-500' : (step === 2 ? 'text-neutral-400' : '')">
                                    <template x-if="step > 2"><i class="fas fa-check-circle"></i></template>
                                    <template x-if="step <= 2">2.</template>
                                </span>
                                {{ __('Tambahkan metode pembayaran') }}
                            </h2>
                            <template x-if="step > 2">
                                <button type="button" class="text-sm font-bold underline text-neutral-900">{{ __('Ubah') }}</button>
                            </template>
                        </div>

                        <div x-show="step === 2" x-collapse class="px-8 pb-8 pt-0">
                            {{-- Gateways Logic - Premium Redesign --}}
                            <div class="space-y-8">
                                {{-- Section: Digital / Instant --}}
                                @if(count($onlineGateways) > 0)
                                <div class="flex flex-col gap-4">
                                    <div class="flex justify-between items-center">
                                        <h4 class="text-[14px] font-bold text-neutral-900 uppercase tracking-widest">{{ __('Pembayaran Otomatis & Instan') }}</h4>
                                        <button type="button" onclick="toggleCancelPolicyModal()" class="text-sm font-bold text-neutral-500 hover:text-black underline transition-colors">{{ __('Kebijakan Pembatalan') }}</button>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach ($onlineGateways as $og)
                                        <label class="group relative flex items-start gap-4 p-5 rounded-2xl cursor-pointer border-2 transition-all 
                                            has-[:checked]:border-neutral-900 has-[:checked]:bg-neutral-50/50 
                                            border-neutral-100 opacity-90 hover:opacity-100 hover:border-neutral-300">
                                            <input type="radio" name="gateway" value="{{ $og->keyword }}" class="mt-1 w-5 h-5 accent-neutral-900 shrink-0" 
                                                   {{ ($loop->first && count($onlineGateways) == 1) || old('gateway') == $og->keyword ? 'checked' : '' }}>
                                            <div class="flex flex-col flex-1 gap-1">
                                                <div class="flex justify-between items-center mb-1">
                                                    <p class="font-bold text-neutral-900 text-[16px]">{{ $og->keyword == 'midtrans' ? 'QRIS / E-Wallet / VA' : $og->name }}</p>
                                                    <div class="h-6 flex items-center shrink-0 ml-2">
                                                        @if($og->keyword == 'midtrans' || $og->keyword == 'xendit')
                                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Logo_QRIS.svg/120px-Logo_QRIS.svg.png" class="h-6 object-contain">
                                                        @else
                                                            <i class="fas fa-bolt text-amber-400"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                <p class="text-sm text-neutral-500 font-light leading-relaxed">{{ __('Dikonfirmasi detik ini juga secara sistem.') }}</p>
                                            </div>
                                            <!-- Interactive Ring -->
                                            <div class="absolute inset-0 rounded-2xl ring-2 ring-transparent group-active:ring-black/5 transition-all"></div>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                {{-- Section: Manual / Offline --}}
                                @if(count($offline_gateways) > 0)
                                <div class="flex flex-col gap-4">
                                    <div class="flex justify-between items-center pt-4 border-t border-neutral-100">
                                        <h4 class="text-[14px] font-bold text-neutral-900 uppercase tracking-widest">{{ __('Transfer Manual Bank') }}</h4>
                                        <button type="button" onclick="toggleCancelPolicyModal()" class="text-sm font-bold text-neutral-500 hover:text-black underline transition-colors">{{ __('Kebijakan Pembatalan') }}</button>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach ($offline_gateways as $fg)
                                        <label class="group relative flex items-start gap-4 p-5 rounded-2xl cursor-pointer border-2 transition-all 
                                            has-[:checked]:border-neutral-900 has-[:checked]:bg-neutral-50/50 
                                            border-neutral-100 opacity-90 hover:opacity-100 hover:border-neutral-300">
                                            <input type="radio" name="gateway" value="{{ $fg->id }}" class="mt-1 w-5 h-5 accent-neutral-900 shrink-0 border-neutral-300">
                                            <div class="flex flex-col flex-1 gap-1">
                                                <div class="flex justify-between items-center mb-1">
                                                    <p class="font-bold text-neutral-900 text-[16px]">{{ $fg->name }}</p>
                                                    <i class="fas fa-university text-neutral-300 h-6"></i>
                                                </div>
                                                <p class="text-sm text-neutral-500 font-light leading-relaxed">{{ __('Wajib mengunggah bukti transfer manual.') }}</p>
                                            </div>
                                            <!-- Interactive Ring -->
                                            <div class="absolute inset-0 rounded-2xl ring-2 ring-transparent group-active:ring-black/5 transition-all"></div>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="mt-10 flex justify-between items-center pt-8 border-t border-neutral-100">
                                <button type="button" @click="step = 1" class="text-sm font-bold underline">{{ __('Kembali') }}</button>
                                <button type="button" @click="next()" class="px-10 py-3.5 bg-neutral-900 text-white font-bold rounded-xl hover:bg-black transition-all active:scale-95 shadow-md">
                                    {{ __('Berikutnya') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Step 3: Write Message (New) --}}
                    <div class="border border-neutral-200 rounded-3xl overflow-hidden transition-all duration-300"
                         :class="step === 3 ? 'shadow-xl border-neutral-300 ring-1 ring-black/5' : 'bg-white opacity-90'">
                        <div @click="goTo(3)" class="p-8 cursor-pointer flex justify-between items-center bg-white" :class="step < 3 ? 'pointer-events-none' : ''">
                            <h2 class="text-[22px] font-semibold text-neutral-900 tracking-tight flex items-center" :class="step < 3 ? 'text-neutral-300' : ''">
                                <span class="mr-4 text-neutral-300 font-light" :class="step > 3 ? 'text-emerald-500' : (step === 3 ? 'text-neutral-400' : '')">
                                    <template x-if="step > 3"><i class="fas fa-check-circle"></i></template>
                                    <template x-if="step <= 3">3.</template>
                                </span>
                                {{ __('Tulis pesan kepada Kapten') }}
                            </h2>
                            <template x-if="step > 3">
                                <button type="button" class="text-sm font-bold underline text-neutral-900">{{ __('Ubah') }}</button>
                            </template>
                        </div>
                        <div x-show="step === 3" x-collapse class="px-8 pb-8 pt-0">
                            <div class="flex flex-col gap-6">
                                <p class="text-[15px] font-light text-neutral-600 leading-relaxed">{{ __('Sebelum melanjutkan, beritahu Kapten sedikit tentang perjalanan Anda dan siapa saja yang akan memancing atau bersantai bersama Anda.') }}</p>
                                
                                <div class="flex items-center gap-4 mb-2">
                                    <div class="h-12 w-12 rounded-xl overflow-hidden shrink-0 border border-neutral-200 bg-neutral-100 flex items-center justify-center">
                                       <i class="fas fa-ship text-neutral-400 text-xl"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-neutral-900 text-[16px]">{{ $room->vendor->username ?? 'Kapten GoFishi' }}</span>
                                        <span class="text-sm font-light text-neutral-500">{{ __('Mitra Kapten Berlisensi') }}</span>
                                    </div>
                                </div>
                                
                                <textarea x-model="guestMessage" name="guest_message" rows="4" 
                                          class="w-full p-4 border rounded-2xl outline-none transition-all resize-none text-[15px] font-light placeholder:text-neutral-400"
                                          :class="errorStep3 ? 'border-red-500 bg-red-50/20 text-red-900 focus:border-red-600 focus:ring-1 focus:ring-red-600' : 'border-neutral-300 focus:border-neutral-900 focus:ring-1 focus:ring-neutral-900'" 
                                          placeholder="{{ __('Contoh: Halo Kapten, rombongan kami memiliki alergi makanan laut, dan penginapan Anda sangat dekat dengan lokasi acara.') }}"></textarea>

                                <template x-if="errorStep3">
                                    <div class="flex items-center gap-2 mt-1">
                                        <i class="fas fa-exclamation-circle text-[#c13515] text-sm"></i>
                                        <span class="text-sm font-bold text-[#c13515]">{{ __('Anda harus menuliskan pesan untuk tuan rumah.') }}</span>
                                    </div>
                                </template>

                                <div class="mt-2 flex justify-between items-center pt-6 border-t border-neutral-100">
                                    <button type="button" @click="step = 2" class="text-sm font-bold underline transition-all hover:text-rose-500">{{ __('Kembali') }}</button>
                                    <button type="button" @click="next()" class="px-10 py-3.5 bg-neutral-900 text-white font-bold rounded-xl hover:bg-black transition-all active:scale-95 shadow-md">
                                        {{ __('Berikutnya') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Step 4: Profile Photo (New) --}}
                    <div class="border border-neutral-200 rounded-3xl overflow-hidden transition-all duration-300"
                         :class="step === 4 ? 'shadow-xl border-neutral-300 ring-1 ring-black/5' : 'bg-white opacity-90'">
                        <div @click="goTo(4)" class="p-8 cursor-pointer flex justify-between items-center bg-white" :class="step < 4 ? 'pointer-events-none' : ''">
                            <h2 class="text-[22px] font-semibold text-neutral-900 tracking-tight flex items-center" :class="step < 4 ? 'text-neutral-300' : ''">
                                <span class="mr-4 text-neutral-300 font-light" :class="step > 4 ? 'text-emerald-500' : (step === 4 ? 'text-neutral-400' : '')">
                                    <template x-if="step > 4"><i class="fas fa-check-circle"></i></template>
                                    <template x-if="step <= 4">4.</template>
                                </span>
                                {{ __('Tambahkan foto profil') }}
                            </h2>
                            <template x-if="step > 4">
                                <button type="button" class="text-sm font-bold underline text-neutral-900">{{ __('Ubah') }}</button>
                            </template>
                        </div>
                        <div x-show="step === 4" x-collapse class="px-8 pb-8 pt-0">
                            <div class="flex flex-col gap-6">
                                <p class="text-[15px] font-light text-neutral-600 leading-relaxed">{{ __('Kapten ingin mengetahui siapa yang akan menaiki kapalnya. Pilihlah foto yang menampilkan wajah Anda. Kapten tidak akan bisa melihat foto profil Anda sebelum reservasi Anda terkonfirmasi.') }}</p>
                                
                                <div class="flex flex-col items-center gap-4 py-8 rounded-2xl transition-all"
                                     :class="errorStep4 ? 'border border-red-500 bg-red-50/20' : ''">
                                    <div class="h-[140px] w-[140px] rounded-full overflow-hidden shrink-0 border border-neutral-200 bg-neutral-900 flex items-center justify-center relative shadow-inner">
                                        <template x-if="!photoPreview">
                                            <span class="text-white text-6xl font-bold font-sans select-none">{{ strtoupper(substr(optional($authUser)->username ?? 'G', 0, 1)) }}</span>
                                        </template>
                                        <template x-if="photoPreview">
                                            <img :src="photoPreview" class="h-full w-full object-cover">
                                        </template>
                                    </div>
                                    
                                    <label class="px-6 py-2.5 bg-white border rounded-full cursor-pointer hover:shadow-sm transition-all focus-within:ring-2 flex items-center gap-2 shadow-sm"
                                           :class="errorStep4 ? 'border-red-500 text-red-600 focus-within:ring-red-500 hover:border-red-600' : 'border-neutral-200 text-neutral-900 focus-within:ring-black hover:border-black'">
                                        <i class="fas fa-camera"></i>
                                        <span class="font-bold text-sm">{{ __('Tambahkan foto') }}</span>
                                        <input type="file" name="guest_profile_photo" class="hidden" accept="image/*" @change="handlePhotoUpload">
                                    </label>
                                </div>
                                
                                <template x-if="errorStep4">
                                    <div class="flex items-center gap-2 mt-[-10px] mb-2">
                                        <i class="fas fa-exclamation-circle text-[#c13515] text-sm"></i>
                                        <span class="text-sm font-bold text-[#c13515]">{{ __('Anda harus mengunggah foto profil untuk melanjutkan.') }}</span>
                                    </div>
                                </template>

                                <div class="mt-4 flex justify-between items-center pt-6 border-t border-neutral-100">
                                    <button type="button" @click="step = 3" class="text-sm font-bold underline transition-all hover:text-rose-500">{{ __('Kembali') }}</button>
                                    <button type="button" @click="next()" class="px-10 py-3.5 bg-neutral-900 text-white font-bold rounded-xl hover:bg-black transition-all active:scale-95 shadow-md">
                                        {{ __('Berikutnya') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Step 5: Community Policies (Shifted from 4) --}}
                    <div class="border border-neutral-200 rounded-3xl overflow-hidden transition-all duration-300"
                         :class="step === 5 ? 'shadow-xl border-neutral-300 ring-1 ring-black/5' : 'bg-white opacity-90'">
                        <div @click="goTo(5)" class="p-8 cursor-pointer flex justify-between items-center bg-white" :class="step < 5 ? 'pointer-events-none' : ''">
                            <h2 class="text-[22px] font-semibold text-neutral-900 tracking-tight flex items-center" :class="step < 5 ? 'text-neutral-300' : ''">
                                <span class="mr-4 text-neutral-300 font-light" :class="step > 5 ? 'text-emerald-500' : (step === 5 ? 'text-neutral-400' : '')">
                                    <template x-if="step > 5"><i class="fas fa-check-circle"></i></template>
                                    <template x-if="step <= 5">5.</template>
                                </span>
                                {{ __('Tinjau kebijakan komunitas') }}
                            </h2>
                            <template x-if="step > 5">
                                <button type="button" class="text-sm font-bold underline text-neutral-900">{{ __('Ubah') }}</button>
                            </template>
                        </div>
                        <div x-show="step === 5" x-collapse class="px-8 pb-8 pt-0">
                            {{-- Policy 1: Age Verification (Airbnb Layout) --}}
                            <div class="flex items-start justify-between gap-6 mb-2">
                                <div class="flex-1">
                                    <label for="chk_age" class="text-[16px] leading-relaxed text-neutral-900 cursor-pointer text-normal block">
                                        {{ __('Saya menjamin bahwa saya telah berusia minimal 17 tahun dan cakap hukum untuk melakukan perjalanan laut ini.') }}
                                    </label>
                                </div>
                                <input type="checkbox" x-model="policyAge" name="checkout_age_agreement" id="chk_age" class="mt-1 h-6 w-6 accent-neutral-900 rounded-[4px] cursor-pointer border-neutral-300 shadow-sm shrink-0" required>
                            </div>
                            <p class="text-[15px] text-neutral-500 leading-relaxed font-light mb-8 pt-1">
                                {{ __('Regulasi penyeberangan mewajibkan batas usia aman untuk aktivitas ini. Kapten berhak membatalkan jadwal perjalanan secara sepihak di dermaga bila batas usia minimal dilanggar.') }}
                            </p>

                            {{-- Policy 2: Terms & Damage Rules (Airbnb Layout) --}}
                            <div class="flex items-start justify-between gap-6 mb-2 mt-4 pt-8 border-t border-neutral-100">
                                <div class="flex-1">
                                    <label for="chk_terms" class="text-[16px] leading-relaxed text-neutral-900 cursor-pointer block">
                                        {{ __('Saya memahami bahwa saya dapat dibebankan biaya jika menyebabkan kerusakan pada alat pancing atau perahu milik Kapten.') }}
                                        <a href="{{ route('frontend.custom_page', 'kebijakan-kerusakan-armada') }}" target="_blank" class="font-bold underline hover:text-rose-600 block mt-1">{{ __('Pelajari selengkapnya') }}</a>
                                    </label>
                                </div>
                                <input type="checkbox" x-model="policyTerms" name="checkout_terms_agreement" id="chk_terms" class="mt-1 h-6 w-6 accent-neutral-900 rounded-[4px] cursor-pointer border-neutral-300 shadow-sm shrink-0" required>
                            </div>
                            <p class="text-[15px] text-neutral-500 leading-relaxed font-light mb-4 pt-1">
                                {{ __('Meski jarang, insiden bisa saja terjadi di laut. Persetujuan ini juga menandakan Anda patuh pada Aturan Dasar dan Kebijakan Pembatalan, di mana penggantian rugi dan denda mutlak ditanggung oleh pemesan.') }}
                            </p>
                            
                            <template x-if="errorStep5">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-exclamation-circle text-[#c13515] text-sm"></i>
                                    <span class="text-sm font-bold text-[#c13515]">{{ __('Harap tinjau dan setujui.') }}</span>
                                </div>
                            </template>

                            <div class="mt-4 flex justify-between items-center pt-6 border-t border-neutral-100">
                                <button type="button" @click="step = 4" class="text-sm font-bold underline transition-all hover:text-rose-500">{{ __('Kembali') }}</button>
                                <button type="button" @click="next()" class="px-10 py-3.5 bg-neutral-900 text-white font-bold rounded-xl hover:bg-black transition-all active:scale-95 shadow-md">
                                    {{ __('Berikutnya') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Step 6: Tinjau dan Konfirmasi (Shifted from 5) --}}
                    <div class="border border-neutral-200 rounded-3xl overflow-hidden transition-all duration-300"
                         :class="step === 6 ? 'shadow-xl border-neutral-300 ring-1 ring-black/5' : 'bg-white opacity-90'">
                        <div @click="goTo(6)" class="p-8 cursor-pointer flex items-center bg-white" :class="step < 6 ? 'pointer-events-none' : ''">
                            <h2 class="text-[22px] font-semibold text-neutral-900 tracking-tight flex items-center" :class="step < 6 ? 'text-neutral-300' : ''">
                                <span class="mr-4 text-neutral-300 font-light">6.</span>
                                {{ __('Tinjau permohonan Anda') }}
                            </h2>
                        </div>

                        <div x-show="step === 6" x-collapse class="px-8 pb-8 pt-0">
                            {{-- Field Inputs (Retained logically but restyled) --}}
                            <div class="flex flex-col gap-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6 mb-2">
                                    <div class="flex flex-col gap-2 group">
                                        <label class="text-[12px] font-bold uppercase text-neutral-400 group-focus-within:text-black transition-colors">{{ __('Nama Lengkap') }}</label>
                                        <input type="text" name="booking_name" class="border-b border-neutral-300 focus:border-black transition-all py-3 outline-none font-light text-lg bg-transparent" 
                                               placeholder="{{ __('Nama') }}" value="{{ optional($authUser)->username ?? old('booking_name') }}" required>
                                    </div>
                                    <div class="flex flex-col gap-2 group">
                                        <label class="text-[12px] font-bold uppercase text-neutral-400 group-focus-within:text-black transition-colors">{{ __('Nomor Telepon') }}</label>
                                        <input type="text" name="booking_phone" class="border-b border-neutral-300 focus:border-black transition-all py-3 outline-none font-light text-lg bg-transparent" 
                                               placeholder="{{ __('08xx...') }}" value="{{ old('booking_phone') }}" required>
                                    </div>
                                </div>

                                <p class="text-[15px] font-light text-neutral-800 leading-relaxed mt-2">
                                    {{ __('Kapten memiliki waktu 24 jam untuk menyetujui permohonan Anda. Pembayaran akan dilakukan sekarang, tetapi Anda akan mendapatkan pengembalian uang penuh jika pemesanan tidak terkonfirmasi.') }}
                                </p>

                                <div class="pt-6 border-t border-neutral-200 flex flex-col gap-5 mt-2">
                                    <p class="text-[14px] text-neutral-700 font-light">
                                        {{ __('Dengan menekan tombol, saya menyetujui ') }}
                                        <button type="button" onclick="toggleTermsModal()" class="font-bold underline text-neutral-900 hover:text-black">{{ __('ketentuan pemesanan') }}</button>.
                                    </p>

                                    <div class="flex items-center gap-4">
                                        <button type="button" @click="step = 5" class="p-4 rounded-full border border-neutral-300 hover:border-black transition-all group shrink-0 bg-white shadow-sm hover:shadow-md">
                                            <i class="fas fa-chevron-left text-neutral-500 group-hover:text-black transition-colors"></i>
                                        </button>
                                        <button type="submit" class="w-full py-4 bg-[#e51d53] hover:bg-[#d81146] active:scale-[0.98] text-white font-bold rounded-xl transition-all duration-300 text-[16px] flex items-center justify-center shadow-lg shadow-rose-200">
                                            {{ __('Ajukan pemesanan') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- RIGHT COLUMN: Sticky Summary --}}
                <div class="flex-1">
                    <div class="sticky top-28 p-8 rounded-[32px] border border-neutral-200 shadow-2xl bg-white flex flex-col gap-8">
                        
                        <div class="flex gap-4">
                            <div class="h-24 w-24 rounded-2xl overflow-hidden flex-shrink-0 bg-neutral-100 border border-neutral-100">
                                <img src="{{ asset('assets/img/perahu/featureImage/' . $room->feature_image) }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex flex-col justify-center overflow-hidden">
                                <span class="text-xs text-neutral-400 font-bold uppercase tracking-widest mb-1">{{ $roomCategory }}</span>
                                <span class="text-[18px] font-bold text-neutral-900 leading-tight">{{ $roomTitle }}</span>
                                <div class="flex items-center gap-1 text-sm mt-2">
                                    <i class="fas fa-star text-[10px] text-neutral-900"></i>
                                    <span class="font-bold text-neutral-900">{{ number_format($room->average_rating, 1) }}</span>
                                    <span class="text-neutral-400 ml-1">(15 ulasan)</span>
                                </div>
                            </div>
                        </div>

                        <hr class="border-neutral-100">

                        <div class="flex flex-col gap-6">
                            <h3 class="text-2xl font-bold text-neutral-900 leading-none">{{ __('Rincian harga') }}</h3>
                            <div class="flex justify-between text-[16px] text-neutral-600 font-light">
                                <span>{{ $package->name }}</span>
                                <span id="base-price" class="text-neutral-900 font-normal" data-value="{{ (float)$package->price }}">{{ symbolPrice($package->price) }}</span>
                            </div>

                            <div id="services-summary" class="flex flex-col gap-4" style="display: none;"></div>

                            <div class="flex justify-between text-[16px] text-neutral-600 font-light">
                                <span class="underline">{{ __('Pajak (IDR)') }}</span>
                                <span id="tax-amount" class="text-neutral-900 font-normal"></span>
                                <span id="tax-percent" class="hidden" data-value="{{ $taxPercent }}"></span>
                            </div>
                        </div>

                        <hr class="border-neutral-100">

                        <div class="flex justify-between items-center">
                            <span class="text-[18px] font-bold text-neutral-900">{{ __('Total (IDR)') }}</span>
                            <span id="grand-total" class="text-[18px] font-bold text-neutral-900">—</span>
                        </div>
                        
                        <button type="button" onclick="togglePriceBreakdown()" class="text-left text-[14px] font-bold underline text-neutral-900 hover:text-black transition-colors">{{ __('Lihat perincian harga') }}</button>
                        
                        {{-- Cancellation Policy Preview --}}
                        @php
                            $cancelDateLimit = \Carbon\Carbon::parse($checkInDate)->subDays(1)->translatedFormat('d F');
                        @endphp
                        <div class="mt-1 text-[15px] leading-relaxed text-neutral-800">
                            {{ __('Jika dibatalkan sebelum check-in pada tanggal ') }}<span class="font-bold">{{ $cancelDateLimit }}</span>{{ __(' WIB, Anda akan mendapatkan pengembalian uang sebagian.') }}
                            <button type="button" onclick="toggleCancelPolicyModal()" class="font-bold underline text-neutral-900 hover:text-black inline-block mt-1">{{ __('Kebijakan lengkap') }}</button>
                        </div>
                        
                        <div class="p-5 bg-rose-50/50 border border-rose-100/50 rounded-2xl text-[12px] font-medium text-rose-700 leading-relaxed italic">
                            <p>{{ __('Keputusan keberangkatan oleh Kapten. Trip dapat di-reschedule jika cuaca membahayakan.') }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </form>

        {{-- Modal (Identical to reference narrow version) --}}
                <div id="price-breakdown-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/40 backdrop-blur-[2px] transition-all duration-300 overflow-y-auto">
                    <div class="bg-white w-full max-w-xl rounded-[24px] shadow-2xl overflow-hidden animate-in zoom-in duration-200" style="max-width: 560px;">
                        <div class="flex justify-between items-center px-8 py-6 border-b border-neutral-100 relative">
                            <button type="button" onclick="togglePriceBreakdown()" class="absolute left-6 p-2 hover:bg-neutral-100 rounded-full transition">
                                <i class="fas fa-times text-neutral-500 text-lg"></i>
                            </button>
                            <h3 class="text-xl font-bold text-neutral-900 text-center w-full">{{ __('Perincian harga') }}</h3>
                        </div>
                        <div class="p-8 flex flex-col gap-5">
                             <div class="flex justify-between items-start pb-2">
                                <div class="flex flex-col gap-1">
                                    <span class="text-neutral-900 font-normal text-[16px]">{{ $package->name }}</span>
                                    <span class="text-sm text-neutral-500 font-light">{{ $package->duration_days }} {{ __('days') }}</span>
                                </div>
                                <span class="font-normal text-neutral-900 text-[16px]">{{ symbolPrice($package->price) }}</span>
                            </div>
                            <div id="modal-services" class="flex flex-col gap-5"></div>
                            <div class="flex justify-between items-center pt-2">
                                <div class="flex flex-col gap-1">
                                    <span class="text-neutral-900 font-normal text-[16px]">{{ __('Pajak') }}</span>
                                    <span class="text-sm text-neutral-500 font-light">{{ __('Sudah termasuk PPN.') }}</span>
                                </div>
                                <span id="modal-tax" class="font-normal text-neutral-900 text-[16px]"></span>
                            </div>
                            <div class="flex justify-between items-center pt-6 mt-2 border-t border-neutral-200">
                                <span class="text-[16px] font-bold text-neutral-900">{{ __('Total (IDR)') }}</span>
                                <span id="modal-total" class="text-[16px] font-bold text-neutral-900"></span>
                            </div>
                        </div>
                    </div>
                </div>

        {{-- Cancellation Policy Modal (Airbnb Clone structure) --}}
                <div id="cancel-policy-modal" class="fixed inset-0 z-[250] hidden flex items-center justify-center p-4 bg-black/40 backdrop-blur-[2px] transition-all duration-300 overflow-y-auto">
                    <div class="bg-white w-full max-w-xl rounded-[24px] shadow-2xl overflow-hidden animate-in zoom-in duration-200">
                        <div class="flex justify-between items-center px-8 py-6 border-b border-neutral-100 relative">
                            <button type="button" onclick="toggleCancelPolicyModal()" class="absolute left-6 p-2 hover:bg-neutral-100 rounded-full transition">
                                <i class="fas fa-times text-neutral-500 text-lg"></i>
                            </button>
                            <h3 class="text-[16px] font-bold text-neutral-900 w-full text-center">{{ __('Kebijakan pembatalan') }}</h3>
                        </div>
                        <div class="p-8 flex flex-col gap-6">
                            
                            <div class="flex flex-col sm:flex-row pb-6 border-b border-neutral-100">
                                <div class="w-[120px] shrink-0 font-medium text-[16px] text-neutral-900 flex flex-col mb-2 sm:mb-0">
                                    <span>{{ __('Sebelum') }}</span>
                                    <span class="leading-tight">{{ \Carbon\Carbon::parse($checkInDate)->subDays(1)->translatedFormat('d M') }}</span>
                                    <span class="text-sm font-light text-neutral-500">15.00 WIB</span>
                                </div>
                                <div class="flex-1 flex flex-col gap-2">
                                    <span class="font-bold text-[16px] text-neutral-900">{{ __('Pengembalian uang sebagian') }}</span>
                                    <span class="text-[15px] font-light text-neutral-600 leading-relaxed">{{ __('Dapatkan pengembalian uang 50% untuk setiap malam kecuali malam pertama. Tidak ada pengembalian uang untuk malam pertama maupun biaya layanan pelabuhan.') }}</span>
                                </div>
                            </div>
                            
                            <div class="text-[14px] font-light text-neutral-500 leading-relaxed mb-4">
                                <p class="mb-4">{{ __('Waktu yang ditampilkan berdasarkan lokasi tempat armada bersandar.') }}</p>
                                <p class="font-bold text-neutral-900 mb-1 text-[15px]">{{ __('Kelayakan pengembalian uang') }}</p>
                                <p>{{ __('Jika Anda melakukan pembayaran terjadwal, pengembalian uang atau jumlah yang harus dibayarkan akan bergantung pada jumlah yang telah Anda bayarkan pada saat pembatalan.') }}</p>
                            </div>
                            
                            <div class="pt-2">
                                <a href="{{ route('frontend.custom_page', 'kebijakan-pembatalan-pengembalian-uang') }}" target="_blank" class="font-bold underline text-neutral-900 text-[15px] hover:text-black">{{ __('Baca kebijakan lengkap') }}</a>
                            </div>

                        </div>
                    </div>
                </div>

        {{-- Terms of Booking Modal (Airbnb Clone structure) --}}
                <div id="terms-modal" class="fixed inset-0 z-[250] hidden flex items-center justify-center p-4 bg-black/40 backdrop-blur-[2px] transition-all duration-300 overflow-y-auto">
                    <div class="bg-white w-full max-w-xl rounded-[24px] shadow-2xl overflow-hidden animate-in zoom-in duration-200">
                        <div class="flex justify-between items-center px-8 py-6 border-b border-neutral-100 relative">
                            <button type="button" onclick="toggleTermsModal()" class="absolute left-6 p-2 hover:bg-neutral-100 rounded-full transition">
                                <i class="fas fa-times text-neutral-500 text-lg"></i>
                            </button>
                            <h3 class="text-[16px] font-bold text-neutral-900 w-full text-center">{{ __('Ketentuan pemesanan') }}</h3>
                        </div>
                        <div class="p-8 flex flex-col gap-6 text-[15px] font-light text-neutral-700 leading-relaxed">
                            <p><strong>{{ __('Persetujuan Khusus Kelautan:') }}</strong> {{ __('Dengan mengajukan pemesanan ini, Anda tunduk penuh pada aturan keamanan laut dari Kapten perahu. Keputusan otoritas Kapten di atas perahu maupun sebelum melaut terkait keselamatan bersifat mutlak dan tidak dapat digugat.') }}</p>
                            
                            <p><strong>{{ __('Kebijakan Keterlambatan:') }}</strong> {{ __('Tamu wajib hadir di dermaga selambat-lambatnya 30 menit sebelum jadwal keberangkatan. Armada kapal berhak untuk berangkat meninggalkan Anda jika Anda terlambat melewati batas toleransi, demi mengejar siklus pasang surut ombak laut yang aman.') }}</p>
                            
                            <p><strong>{{ __('Perubahan Cuaca / Force Majeure:') }}</strong> {{ __('Perjalanan dapat ditunda atau dijadwalkan ulang (Reschedule) secara sepihak oleh armada tanpa ganti rugi materi tambahan apabila himbauan Syahbandar melarang segala aktivitas pelayaran akibat kondisi badai atau cuaca buruk yang mengancam nyawa.') }}</p>
                            
                            <div class="pt-4 border-t border-neutral-100">
                                <a href="{{ route('frontend.custom_page', 'ketentuan-pemesanan') }}" target="_blank" class="font-bold underline text-neutral-900 text-[15px] hover:text-black">{{ __('Baca selengkapnya di Pusat Bantuan') }}</a>
                            </div>
                        </div>
                    </div>
                </div>

        {{-- Floating Error Validation Modal (From Picture 1 & 2) --}}
                <div x-show="showErrorModal" style="display: none;" 
                     class="fixed inset-0 z-[600] flex items-center justify-center p-4 bg-black/40 backdrop-blur-[2px] transition-all duration-300 overflow-y-auto">
                    <div @click.outside="showErrorModal = false" class="bg-white border border-neutral-200 w-full max-w-md rounded-2xl shadow-2xl p-6 flex gap-4 relative animate-in zoom-in duration-200">
                        <div class="shrink-0 mt-1">
                            <i class="fas fa-exclamation-circle text-[#c13515] text-[32px]"></i>
                        </div>
                        <div class="flex-1 pr-6">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-bold text-neutral-900 text-[16px] leading-tight" x-text="errorModalTitle"></h4>
                            </div>
                            <p class="text-[14px] text-neutral-500 leading-relaxed mb-4 font-light" x-text="errorModalText"></p>
                            <button type="button" @click="showErrorModal = false; step = errorModalActionStep" class="text-[14px] font-bold text-neutral-900 hover:text-black underline" x-text="errorModalActionText"></button>
                        </div>
                        <button type="button" @click="showErrorModal = false" class="absolute top-4 right-4 p-2 hover:bg-neutral-100 rounded-full transition">
                            <i class="fas fa-times text-neutral-500"></i>
                        </button>
                    </div>
                </div>

        {{-- Intelligent Session Recovery Modal (Abandoned Checkout Recovery) --}}
                <div x-show="showRecoveryModal" style="display: none;" 
                     class="fixed inset-0 z-[700] flex items-center justify-center p-4 bg-black/50 backdrop-blur-[4px] transition-all duration-300 overflow-y-auto">
                    <div class="bg-white border border-neutral-200 w-full max-w-md rounded-[28px] shadow-2xl p-8 flex flex-col relative animate-in zoom-in duration-300">
                        <div class="flex items-center gap-5 mb-8">
                            <div class="w-14 h-14 rounded-full bg-blue-50/50 flex items-center justify-center shrink-0 border border-blue-100">
                                <i class="fas fa-undo-alt text-blue-600 text-2xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-neutral-900 text-[18px] leading-tight mb-2">{{ __('Lanjutkan pesanan Anda?') }}</h4>
                                <p class="text-[14px] text-neutral-500 leading-relaxed font-light">{{ __('Sistem mendeteksi Anda memiliki riwayat sesi pesanan pada armada ini yang belum diselesaikan.') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex gap-4">
                            <button type="button" @click="discardSession()" class="flex-1 px-4 py-3.5 bg-white border border-neutral-300 text-neutral-700 font-bold rounded-xl hover:bg-neutral-50 hover:border-black hover:text-black transition-all">
                                {{ __('Mulai Baru') }}
                            </button>
                            <button type="button" @click="recoverSession()" class="flex-1 px-4 py-3.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 active:scale-95 transition-all shadow-[0_8px_16px_-6px_rgba(37,99,235,0.4)]">
                                {{ __('Lanjutkan Sesi') }}
                            </button>
                        </div>
                    </div>
                </div>

        {{-- Airbnb Style Auth Modal --}}
                <div id="auth-modal" 
                     x-show="showAuthModal" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-black/40 backdrop-blur-[2px]"
                     x-cloak>
                    <div @click.away="showAuthModal = false" 
                         class="bg-white w-full max-w-xl rounded-[32px] shadow-2xl overflow-hidden animate-in zoom-in duration-300" style="max-width: 560px;">
                        
                        {{-- Modal Header --}}
                        <div class="flex justify-between items-center px-8 py-6 border-b border-neutral-100 relative">
                            <button type="button" @click="showAuthModal = false" class="absolute left-6 p-2 hover:bg-neutral-100 rounded-full transition">
                                <i class="fas fa-times text-neutral-500 text-sm"></i>
                            </button>
                            <h3 class="text-[16px] font-bold text-neutral-900 w-full text-center">{{ __('Masuk atau daftar') }}</h3>
                        </div>
                        
                        <div class="p-8 max-h-[85vh] overflow-y-auto custom-scrollbar">
                            <h4 class="text-[22px] font-semibold text-neutral-900 mb-8 tracking-tight">{{ __('Selamat datang di GoFishi') }}</h4>

                            {{-- Auth Tabs --}}
                            <div class="flex rounded-xl border border-neutral-200 p-1 mb-6">
                                <button type="button" @click="authTab = 'login'"
                                        class="flex-1 py-2.5 rounded-lg text-sm font-bold transition-colors"
                                        :class="authTab === 'login' ? 'bg-neutral-900 text-white' : 'text-neutral-700 hover:bg-neutral-100'">
                                    {{ __('Masuk') }}
                                </button>
                                <button type="button" @click="authTab = 'signup'"
                                        class="flex-1 py-2.5 rounded-lg text-sm font-bold transition-colors"
                                        :class="authTab === 'signup' ? 'bg-neutral-900 text-white' : 'text-neutral-700 hover:bg-neutral-100'">
                                    {{ __('Daftar') }}
                                </button>
                            </div>
                            
                            {{-- Social Logins --}}
                            <div class="flex flex-col gap-4 mb-6">
                                <a href="{{ route('user.login.provider', ['provider' => 'facebook', 'checkout_redirect' => 1]) }}" class="flex items-center justify-between w-full p-3.5 border border-neutral-400 rounded-xl hover:bg-neutral-50 transition-all group">
                                    <i class="fab fa-facebook text-[#1877F2] text-xl"></i>
                                    <span class="font-semibold text-[14px] flex-1 text-center text-neutral-800">{{ __('Lanjutkan dengan Facebook') }}</span>
                                    <div class="w-5"></div>
                                </a>
                                <a href="{{ route('user.login.provider', ['provider' => 'google', 'checkout_redirect' => 1]) }}" class="flex items-center justify-between w-full p-3.5 border border-neutral-400 rounded-xl hover:bg-neutral-50 transition-all group">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"><path fill="#4285F4" d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.874 2.684-6.615z"/><path fill="#34A853" d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.258c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18z"/><path fill="#FBBC05" d="M3.964 10.707c-.18-.54-.282-1.117-.282-1.707s.102-1.167.282-1.707V4.96H.957A8.996 8.996 0 0 0 0 9c0 1.491.366 2.9 1.008 4.141l2.956-2.434z"/><path fill="#EA4335" d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.582C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.958L3.964 7.29c.708-2.127 2.692-3.71 5.036-3.71z"/></svg>
                                    <span class="font-semibold text-[14px] flex-1 text-center text-neutral-800">{{ __('Lanjutkan dengan Google') }}</span>
                                    <div class="w-5"></div>
                                </a>
                            </div>

                            <div class="relative my-6 text-center">
                                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-neutral-100"></div></div>
                                <span class="relative px-4 bg-white text-[12px] font-normal text-neutral-500 tracking-tight">{{ __('atau') }}</span>
                            </div>

                            {{-- Login Form --}}
                            <form x-show="authTab === 'login'" x-cloak action="{{ route('user.login_submit') }}" method="POST" class="flex flex-col gap-4">
                                @csrf
                                <input type="hidden" name="form_type" value="login">
                                <input type="hidden" name="checkout_redirect" value="true">
                                <div class="flex flex-col border border-neutral-400 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-black transition-all">
                                    <input type="text" name="username" placeholder="{{ __('Username atau Email') }}" class="p-4 outline-none border-b border-neutral-400 font-light text-[15px] focus:bg-neutral-50" required>
                                    <input type="password" name="password" placeholder="{{ __('Password') }}" class="p-4 outline-none font-light text-[15px] focus:bg-neutral-50" required>
                                </div>
                                <button type="submit"
                                        formaction="{{ route('user.login_submit') }}"
                                        formmethod="POST"
                                        formnovalidate
                                        class="w-full py-3.5 bg-rose-500 text-white font-bold rounded-xl hover:bg-rose-600 transition-all text-[16px] shadow-sm">
                                    {{ __('Lanjutkan') }}
                                </button>
                            </form>

                            {{-- Signup Form --}}
                            <form x-show="authTab === 'signup'" x-cloak action="{{ route('user.signup_submit') }}" method="POST" class="flex flex-col gap-4">
                                @csrf
                                <input type="hidden" name="form_type" value="signup">
                                <input type="hidden" name="checkout_redirect" value="true">
                                <input type="text" name="username" placeholder="{{ __('Username') }}" class="p-4 border border-neutral-300 rounded-xl outline-none focus:border-neutral-900 text-[15px]" value="{{ old('username') }}" required>
                                <input type="email" name="email" placeholder="{{ __('Email') }}" class="p-4 border border-neutral-300 rounded-xl outline-none focus:border-neutral-900 text-[15px]" value="{{ old('email') }}" required>
                                <input type="date" name="dob" class="p-4 border border-neutral-300 rounded-xl outline-none focus:border-neutral-900 text-[15px]" value="{{ old('dob') }}" required>
                                <input type="password" name="password" placeholder="{{ __('Password') }}" class="p-4 border border-neutral-300 rounded-xl outline-none focus:border-neutral-900 text-[15px]" required>
                                <input type="password" name="password_confirmation" placeholder="{{ __('Konfirmasi Password') }}" class="p-4 border border-neutral-300 rounded-xl outline-none focus:border-neutral-900 text-[15px]" required>
                                <label class="flex items-start gap-3 p-4 rounded-xl border border-neutral-200 bg-neutral-50">
                                    <input type="checkbox" name="age_agreement" value="1" class="mt-1 h-5 w-5 accent-neutral-900 rounded" required>
                                    <span class="text-[13px] text-neutral-700 leading-relaxed">
                                        {{ __('Saya berusia 17 tahun ke atas dan menyetujui syarat serta kebijakan.') }}
                                    </span>
                                </label>
                                <button type="submit"
                                        formaction="{{ route('user.signup_submit') }}"
                                        formmethod="POST"
                                        formnovalidate
                                        class="w-full py-3.5 bg-rose-500 text-white font-bold rounded-xl hover:bg-rose-600 transition-all text-[16px] shadow-sm">
                                    {{ __('Daftar & lanjutkan') }}
                                </button>
                            </form>

                            <p class="text-[11px] text-neutral-500 font-light mt-8 text-center leading-relaxed">
                                {{ __('Kami akan mengirim email konfirmasi untuk memverifikasi akun Anda. Standar biaya data dari operator Anda mungkin berlaku.') }}
                            </p>
                        </div>
                    </div>
                </div>

        {{-- Checkout Profile Completion Modal (for social-login users without DOB/consent) --}}
                <div x-show="showProfileCompletionModal"
                     class="fixed inset-0 z-[230] flex items-center justify-center p-4 bg-black/40 backdrop-blur-[2px]"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-cloak>
                    <div @click.away="showProfileCompletionModal = false"
                         class="bg-white w-full max-w-2xl rounded-[32px] shadow-2xl overflow-hidden animate-in zoom-in duration-300">
                        <div class="flex justify-between items-center px-8 py-6 border-b border-neutral-100">
                            <h3 class="text-[20px] font-bold text-neutral-900">{{ __('Lengkapi data untuk melanjutkan') }}</h3>
                            <button type="button" @click="showProfileCompletionModal = false" class="p-2 hover:bg-neutral-100 rounded-full transition">
                                <i class="fas fa-times text-neutral-500"></i>
                            </button>
                        </div>
                        <form action="{{ route('user.checkout.complete_profile') }}" method="POST" class="p-8 flex flex-col gap-6">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[12px] font-bold uppercase text-neutral-400 mb-2">{{ __('Nama depan') }}</label>
                                    <input type="text" name="first_name" class="w-full border border-neutral-300 rounded-xl p-3.5 outline-none focus:border-neutral-900"
                                           value="{{ old('first_name', explode(' ', trim($authUser->name ?? ''))[0] ?? '') }}" required>
                                </div>
                                <div>
                                    <label class="block text-[12px] font-bold uppercase text-neutral-400 mb-2">{{ __('Nama belakang') }}</label>
                                    <input type="text" name="last_name" class="w-full border border-neutral-300 rounded-xl p-3.5 outline-none focus:border-neutral-900"
                                           value="{{ old('last_name', trim(implode(' ', array_slice(explode(' ', trim($authUser->name ?? '')), 1)))) }}" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[12px] font-bold uppercase text-neutral-400 mb-2">{{ __('Tanggal lahir') }}</label>
                                <input type="date" name="dob" class="w-full border border-neutral-300 rounded-xl p-3.5 outline-none focus:border-neutral-900"
                                       value="{{ old('dob', $authUser->dob ?? '') }}" required>
                                <p class="text-xs text-neutral-500 mt-2">{{ __('Usia minimal 17 tahun untuk melanjutkan checkout.') }}</p>
                            </div>

                            <div class="p-4 rounded-2xl border border-neutral-200 bg-neutral-50">
                                <label class="flex items-start gap-3 cursor-pointer">
                                    <input type="checkbox" name="age_agreement" value="1" class="mt-1 h-5 w-5 accent-neutral-900 rounded" required>
                                    <span class="text-[14px] text-neutral-700 leading-relaxed">
                                        {{ __('Saya menyatakan bahwa saya berusia 17+ dan menyetujui Ketentuan Layanan, Kebijakan Nondiskriminasi, serta Kebijakan Privasi.') }}
                                    </span>
                                </label>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="px-10 py-3.5 bg-neutral-900 text-white font-bold rounded-xl hover:bg-black transition-all">
                                    {{ __('Setuju dan lanjutkan') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

        {{-- Partial Payment Info Modal --}}
                <div x-show="showPaymentInfo" 
                     class="fixed inset-0 z-[250] flex items-center justify-center p-4 bg-black/40 backdrop-blur-[2px]"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-cloak>
                    <div @click.away="showPaymentInfo = false" class="bg-white w-full max-w-xl rounded-[32px] overflow-hidden shadow-2xl animate-in zoom-in duration-300">
                        <div class="flex justify-between items-center px-8 py-6 border-b border-neutral-100">
                            <button type="button" @click="showPaymentInfo = false" class="p-2 hover:bg-neutral-100 rounded-full transition"><i class="fas fa-times"></i></button>
                            <h3 class="text-base font-bold text-neutral-900 text-center w-full">{{ __('Cara kerja pembayaran sebagian') }}</h3>
                            <div class="w-8"></div>
                        </div>
                        <div class="p-8 space-y-8">
                            <h2 class="text-2xl font-bold leading-tight">{{ __('Bayar sebagian sekarang, sebagian lagi nanti') }}</h3>
                            <p class="text-neutral-500 leading-relaxed">{{ __('Anda bisa membayar sebagian dari reservasi ini sekarang dan melunasi sisanya nanti. Tanpa biaya tambahan.') }}</p>
                            
                            <div class="space-y-6">
                                <div class="flex flex-col gap-1">
                                    <h4 class="font-bold text-neutral-900">{{ __('Bayar sebagian dari nominal total sekarang') }}</h4>
                                    <p class="text-neutral-500 font-light text-[15px]">{{ __('Konfirmasikan reservasi Anda dengan membayar sebagian dari nominal total.') }}</p>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <h4 class="font-bold text-neutral-900">{{ __('Bayar sisanya sebelum check-in') }}</h4>
                                    <p class="text-neutral-500 font-light text-[15px]">{{ __('Metode pembayaran Anda yang semula akan dibebankan biaya di tanggal pembayaran kedua.') }}</p>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <h4 class="font-bold text-neutral-900">{{ __('Pembayaran dilakukan secara otomatis') }}</h4>
                                    <p class="text-neutral-500 font-light text-[15px]">{{ __('Jangan khawatir, kami akan mengirimkan pengingat 3 hari sebelum pembayaran berikutnya.') }}</p>
                                </div>
                            </div>

                            <div class="pt-4">
                                <button type="button" @click="showPaymentInfo = false" class="font-bold underline text-neutral-900">{{ __('Ketentuan Berlaku') }}</button>
                            </div>
                        </div>
                    </div>
                </div>

        
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const getEl = (id) => document.getElementById(id);
    const getAll = (sel) => document.querySelectorAll(sel);
    const formatter = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });
    const formatIDR = (amount) => formatter.format(amount).replace('IDR', 'Rp').trim();

    function updatePrices() {
        const basePrice = parseFloat(getEl('base-price').getAttribute('data-value')) || 0;
        const taxPercent = parseFloat(getEl('tax-percent').getAttribute('data-value')) || 0;
        let servicesTotal = 0;
        
        getEl('services-summary').innerHTML = '';
        getEl('services-summary').style.display = 'none';
        getEl('modal-services').innerHTML = '';
        
        getAll('.service-checkbox:checked').forEach(checkbox => {
            const price = parseFloat(checkbox.getAttribute('data-price')) || 0;
            const label = checkbox.closest('label').querySelector('span.text-sm').textContent.trim();
            servicesTotal += price;
            
            ['services-summary', 'modal-services'].forEach(containerId => {
                const row = document.createElement('div');
                row.className = 'flex justify-between font-light text-neutral-800 animate-in fade-in slide-in-from-top-1';
                row.innerHTML = `<span>${label}</span><span class="text-neutral-900 font-normal">${formatIDR(price)}</span>`;
                getEl(containerId).appendChild(row);
                if(containerId === 'services-summary') getEl(containerId).style.display = 'flex';
            });
        });

        const subtotal = basePrice + servicesTotal;
        const tax = subtotal * (taxPercent / 100);
        const total = subtotal + tax;
        
        const partNow = total * 0.5; // Simulate 50% for display
        const partNext = total - partNow;

        const formattedTax = formatIDR(tax);
        const formattedTotal = formatIDR(total);

        ['tax-amount', 'modal-tax'].forEach(id => getEl(id).textContent = formattedTax);
        ['grand-total', 'modal-total', 'step-1-total'].forEach(id => getEl(id).textContent = formattedTotal);
        
        getEl('part-payment-now').textContent = formatIDR(partNow);
        getEl('part-payment-next').textContent = formatIDR(partNext);
    }

    window.togglePriceBreakdown = function() {
        getEl('price-breakdown-modal').classList.toggle('hidden');
        document.body.classList.toggle('overflow-hidden');
    }
    getEl('price-breakdown-modal').addEventListener('click', function(e) { if (e.target === this) togglePriceBreakdown(); });

    window.toggleCancelPolicyModal = function() {
        getEl('cancel-policy-modal').classList.toggle('hidden');
        document.body.classList.toggle('overflow-hidden');
    }
    getEl('cancel-policy-modal').addEventListener('click', function(e) { if (e.target === this) toggleCancelPolicyModal(); });

    window.toggleTermsModal = function() {
        getEl('terms-modal').classList.toggle('hidden');
        document.body.classList.toggle('overflow-hidden');
    }
    getEl('terms-modal').addEventListener('click', function(e) { if (e.target === this) toggleTermsModal(); });
    
    updatePrices();
});
</script>
@endsection
