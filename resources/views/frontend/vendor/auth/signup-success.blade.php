@extends('frontend.layout-airbnb')

@section('pageHeading')
  {{ __('Pendaftaran Berhasil') }}
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-20">
    <div class="max-w-2xl w-full px-6">
        <div class="bg-white rounded-[40px] shadow-2xl border border-gray-100 p-8 sm:p-16 text-center overflow-hidden relative">
            
            {{-- Decorative elements --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-rose-50 rounded-full -mr-16 -mt-16 opacity-50"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-blue-50 rounded-full -ml-12 -mb-12 opacity-50"></div>

            {{-- Success Icon Animation --}}
            <div class="relative mb-10">
                <div class="w-24 h-24 bg-rose-50 rounded-3xl flex items-center justify-center mx-auto animate-bounce duration-1000">
                    <i data-lucide="mail-check" class="w-12 h-12 text-airbnb-red"></i>
                </div>
            </div>

            {{-- Content --}}
            <h1 class="text-3xl sm:text-4xl font-black text-gray-900 mb-6 tracking-tight">
                {{ __('Pendaftaran Berhasil Dikirim!') }}
            </h1>

            <div class="space-y-6 mb-12">
                @if($setting->vendor_email_verification == 1)
                    <p class="text-lg text-gray-600 font-light leading-relaxed">
                        {{ __('Terima kasih telah bergabung dengan GoFishi. Kami telah mengirimkan link verifikasi ke email Anda.') }}
                    </p>
                    <div class="bg-rose-50/50 rounded-2xl p-4 border border-rose-100">
                        <p class="text-sm font-semibold text-rose-600">
                            {{ __('Mohon periksa kotak masuk (atau spam) email Anda untuk mengaktifkan akun.') }}
                        </p>
                    </div>
                @else
                    <p class="text-lg text-gray-600 font-light leading-relaxed">
                        {{ __('Akun Host Anda telah berhasil dibuat. Silakan login untuk mulai mendaftarkan kapal pancing atau yacht Anda.') }}
                    </p>
                @endif

                @if($setting->vendor_admin_approval == 1)
                <div class="flex items-center justify-center gap-2 text-gray-400">
                    <i data-lucide="info" class="w-4 h-4"></i>
                    <p class="text-xs italic">{{ __('Catatan: Akun memerlukan persetujuan admin setelah pendaftaran.') }}</p>
                </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('vendor.login') }}" 
                   class="bg-airbnb-red text-white px-10 py-4 rounded-2xl font-bold text-lg hover:bg-rose-600 hover:shadow-xl hover:scale-[1.02] transition-all duration-300 shadow-lg active:scale-95">
                    {{ __('Ke Halaman Login') }}
                </a>
                <a href="{{ route('index') }}" 
                   class="bg-gray-50 text-gray-600 px-10 py-4 rounded-2xl font-bold text-lg hover:bg-gray-100 transition-colors active:scale-95 border border-gray-100">
                    {{ __('Beranda') }}
                </a>
            </div>
        </div>

        {{-- Help Footer --}}
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-400">
                {{ __('Butuh bantuan?') }} 
                <a href="{{ route('contact') }}" class="text-airbnb-red font-semibold hover:underline">{{ __('Hubungi Support') }}</a>
            </p>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
@endsection
