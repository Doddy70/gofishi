<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Verifikasi Identitas') }} | {{ $websiteInfo->website_title ?? 'GoFishi' }}</title>
    <!-- Tailwind CSS (via CDN for standalone wizard layout) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #ffffff;
            color: #222222;
        }
        .form-radio {
            accent-color: #222222;
        }
    </style>
</head>
<body x-data="{ 
        step: 1, 
        uploadMethod: 'file', 
        docType: 'ktp',
        frontImage: null,
        backImage: null,
        handleFileSelect(event, type) {
            const file = event.target.files[0];
            if(file) {
                this[type] = URL.createObjectURL(file);
            }
        }
    }">

    <!-- Top Navigation / Header -->
    <header class="border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <!-- System Logo / Mimicing Airbnb left logo positioning -->
                @if (isset($websiteInfo) && $websiteInfo->logo)
                    <img src="{{ asset('assets/img/' . $websiteInfo->logo) }}" alt="Logo" class="h-8 object-contain">
                @else
                    <span class="text-[#FF385C] font-bold text-xl tracking-tighter">GoFishi</span>
                @endif
            </div>
            <div class="flex items-center gap-4 text-sm font-semibold">
                <span class="hidden md:inline">{{ __('Mode Vendor') }}</span>
                <div class="w-8 h-8 rounded-full bg-neutral-800 text-white flex items-center justify-center font-bold">
                    {{ strtoupper(substr($vendor->fname, 0, 1)) }}
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="max-w-3xl mx-auto px-6 py-12">
        <form action="{{ route('vendor.verify.identity.submit') }}" method="POST" enctype="multipart/form-data" id="verificationForm">
            @csrf

            <!-- STEP 1: Introduction -->
            <div x-show="step === 1" x-transition.opacity.duration.300ms>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <div>
                        <h1 class="text-3xl font-semibold mb-6 tracking-tight">{{ __('Mari tambahkan bukti identitas resmi Anda') }}</h1>
                        <p class="text-gray-600 font-light leading-relaxed mb-6">
                            {{ __('Anda perlu menambahkan bukti identitas resmi. Langkah ini membantu memastikan keaslian identitas Anda.') }}
                        </p>
                        <p class="text-gray-600 font-light leading-relaxed mb-12">
                            {{ __('Tergantung negara asal Anda, Anda bisa menambahkan SIM, paspor, atau kartu identitas resmi.') }}
                        </p>
                        
                        <button type="button" @click="step = 2" class="bg-[#222222] hover:bg-black text-white font-semibold py-3.5 px-6 rounded-lg w-full md:w-auto transition-colors">
                            {{ __('Tambahkan identitas') }}
                        </button>
                    </div>
                    
                    <div>
                        <div class="border border-gray-200 rounded-xl p-8 shadow-sm">
                            <h3 class="font-semibold text-lg mb-4">{{ __('Privasi Anda') }}</h3>
                            <p class="text-gray-600 font-light text-sm leading-relaxed mb-4">
                                {{ __('Kami berupaya menjaga kerahasiaan dan keamanan data yang Anda berikan selama proses ini. Pelajari selengkapnya di Kebijakan Privasi.') }}
                            </p>
                            <a href="#" class="text-sm font-semibold underline">{{ __('Cara kerja verifikasi identitas') }}</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 2: Choose Method -->
            <div x-show="step === 2" x-transition.opacity.duration.300ms style="display: none;">
                <div class="max-w-2xl">
                    <h1 class="text-3xl font-semibold mb-8 tracking-tight">{{ __('Bagaimana Anda ingin menambahkan identitas resmi Anda?') }}</h1>
                    
                    <div class="space-y-4 mb-12">
                        <label class="block border border-gray-300 rounded-xl p-6 cursor-pointer hover:border-black transition-colors"
                               :class="uploadMethod === 'file' ? 'border-black ring-1 ring-black' : ''">
                            <div class="flex items-center gap-4">
                                <input type="radio" x-model="uploadMethod" value="file" class="w-5 h-5 form-radio text-black border-gray-300">
                                <div>
                                    <div class="font-semibold">{{ __('Unggah foto yang sudah ada') }}</div>
                                    <div class="text-sm text-gray-500">{{ __('Direkomendasikan') }}</div>
                                </div>
                            </div>
                        </label>

                        <label class="block border border-gray-300 rounded-xl p-6 cursor-pointer hover:border-black transition-colors"
                               :class="uploadMethod === 'webcam' ? 'border-black ring-1 ring-black' : ''">
                            <div class="flex items-center gap-4">
                                <input type="radio" x-model="uploadMethod" value="webcam" class="w-5 h-5 form-radio text-black border-gray-300">
                                <div class="font-semibold">{{ __('Ambil foto dengan webcam') }}</div>
                            </div>
                        </label>
                        
                        <label class="block border border-gray-300 rounded-xl p-6 cursor-pointer hover:border-black transition-colors"
                               :class="uploadMethod === 'app' ? 'border-black ring-1 ring-black' : ''">
                            <div class="flex items-center gap-4">
                                <input type="radio" x-model="uploadMethod" value="app" class="w-5 h-5 form-radio text-black border-gray-300">
                                <div class="font-semibold">{{ __('Ambil foto dengan aplikasi seluler GoFishi') }}</div>
                            </div>
                        </label>
                    </div>

                    <div class="flex justify-between items-center border-t border-gray-200 pt-6">
                        <button type="button" @click="step = 1" class="font-semibold underline text-gray-800 hover:text-black">{{ __('Kembali') }}</button>
                        <button type="button" @click="step = 3" class="bg-[#222222] hover:bg-black text-white font-semibold py-3.5 px-8 rounded-lg">{{ __('Lanjutkan') }}</button>
                    </div>
                </div>
            </div>

            <!-- STEP 3: Choose ID Type -->
            <div x-show="step === 3" x-transition.opacity.duration.300ms style="display: none;">
                <div class="max-w-2xl">
                    <h1 class="text-3xl font-semibold mb-8 tracking-tight">{{ __('Pilih jenis identitas untuk ditambahkan') }}</h1>
                    
                    <div class="mb-6">
                        <label class="block text-xs text-gray-500 font-semibold mb-1 ml-1 uppercase">{{ __('Negara/wilayah penerbit') }}</label>
                        <select class="w-full border border-gray-300 rounded-xl p-4 appearance-none font-semibold focus:outline-none focus:border-black">
                            <option>Indonesia</option>
                        </select>
                    </div>

                    <div class="space-y-4 mb-8">
                        <label class="block border border-gray-300 rounded-xl p-6 cursor-pointer hover:border-black transition-colors"
                               :class="docType === 'sim' ? 'border-black ring-1 ring-black' : ''">
                            <div class="flex items-center gap-4">
                                <input type="radio" x-model="docType" value="sim" class="w-5 h-5 form-radio text-black border-gray-300">
                                <div class="font-semibold flex items-center gap-3">
                                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    {{ __('SIM') }}
                                </div>
                            </div>
                        </label>

                        <label class="block border border-gray-300 rounded-xl p-6 cursor-pointer hover:border-black transition-colors"
                               :class="docType === 'paspor' ? 'border-black ring-1 ring-black' : ''">
                            <div class="flex items-center gap-4">
                                <input type="radio" x-model="docType" value="paspor" class="w-5 h-5 form-radio text-black border-gray-300">
                                <div class="font-semibold flex items-center gap-3">
                                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                                    {{ __('Paspor') }}
                                </div>
                            </div>
                        </label>

                        <label class="block border border-gray-300 rounded-xl p-6 cursor-pointer hover:border-black transition-colors"
                               :class="docType === 'ktp' ? 'border-black ring-1 ring-black' : ''">
                            <div class="flex items-center gap-4">
                                <input type="radio" x-model="docType" value="ktp" class="w-5 h-5 form-radio text-black border-gray-300">
                                <div class="font-semibold flex items-center gap-3">
                                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                    {{ __('Kartu identitas (KTP)') }}
                                </div>
                            </div>
                        </label>
                    </div>

                    <p class="text-xs text-gray-500 mb-8 leading-relaxed">
                        {{ __('Data identitas Anda akan ditangani sesuai dengan Kebijakan Privasi GoFishi dan tidak akan dibagikan kepada Tuan Rumah atau tamu Anda.') }}
                    </p>

                    <div class="flex justify-between items-center border-t border-gray-200 pt-6">
                        <button type="button" @click="step = 2" class="font-semibold underline text-gray-800 hover:text-black">{{ __('Kembali') }}</button>
                        <button type="button" @click="step = 4" class="bg-[#222222] hover:bg-black text-white font-semibold py-3.5 px-8 rounded-lg">{{ __('Lanjutkan') }}</button>
                    </div>
                </div>
            </div>

            <!-- STEP 4: Upload Screen -->
            <div x-show="step === 4" x-transition.opacity.duration.300ms style="display: none;">
                <div class="max-w-2xl">
                    <h1 class="text-3xl font-semibold mb-4 tracking-tight">
                        <span x-text="docType === 'sim' ? 'Unggah gambar SIM Anda' : (docType === 'paspor' ? 'Unggah Paspor Anda' : 'Unggah gambar KTP Anda')"></span>
                    </h1>
                    <p class="text-gray-600 font-light mb-8">
                        {{ __('Pastikan foto Anda tidak buram dan menampilkan wajah Anda dengan jelas beserta detail dokumen lainnya.') }}
                    </p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                        <!-- Depan -->
                        <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-black transition-colors cursor-pointer bg-gray-50 flex flex-col items-center justify-center min-h-[200px] overflow-hidden">
                            <input type="file" :name="docType === 'sim' ? 'driving_license_file' : 'ktp_file'" 
                                   @change="handleFileSelect($event, 'frontImage')" 
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" 
                                   accept="image/*">
                            
                            <template x-if="!frontImage">
                                <div>
                                    <svg class="w-10 h-10 text-gray-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <div class="font-semibold text-gray-900">{{ __('Unggah dokumen') }}</div>
                                    <div class="text-xs text-gray-500 mt-1 uppercase tracking-wider">JPEG atau PNG saja</div>
                                </div>
                            </template>

                            <template x-if="frontImage">
                                <img :src="frontImage" class="absolute inset-0 w-full h-full object-cover z-0">
                            </template>
                        </div>
                    </div>

                    <div class="flex justify-between items-center border-t border-gray-200 pt-6">
                        <button type="button" @click="step = 3" class="font-semibold underline text-gray-800 hover:text-black">
                            ← {{ __('Kembali') }}
                        </button>
                        <button type="button" @click="if(frontImage) step = 5; else alert('Harap unggah gambar dokumen Anda terlebih dahulu.')" 
                                :class="frontImage ? 'bg-[#222222] hover:bg-black' : 'bg-gray-200 text-gray-400 cursor-not-allowed'" 
                                class="text-white font-semibold py-3.5 px-8 rounded-lg transition-colors">
                            {{ __('Lanjutkan') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- STEP 5: Name Confirmation -->
            <div x-show="step === 5" x-transition.opacity.duration.300ms style="display: none;">
                <div class="max-w-xl">
                    <h1 class="text-3xl font-semibold mb-4 tracking-tight">{{ __('Apakah ini nama resmi Anda?') }}</h1>
                    <p class="text-gray-600 font-light mb-8">
                        {{ __('Kami mengambil nama ini dari identitas yang Anda berikan. Jika salah, Anda bisa memperbarui nama Anda di bawah ini secara manual.') }}
                    </p>
                    
                    <div class="space-y-4 mb-4">
                        <div class="relative border border-gray-300 rounded-xl focus-within:border-black focus-within:ring-1 focus-within:ring-black">
                            <label class="absolute text-xs text-gray-500 font-semibold top-3 left-4">{{ __('Nama depan pada identitas') }}</label>
                            <input type="text" name="fname" value="{{ $vendor->fname }}" class="w-full pt-8 pb-3 px-4 rounded-xl outline-none text-gray-900 font-semibold" required>
                        </div>
                        
                        <div class="relative border border-gray-300 rounded-xl focus-within:border-black focus-within:ring-1 focus-within:ring-black">
                            <label class="absolute text-xs text-gray-500 font-semibold top-3 left-4">{{ __('Nama belakang pada identitas') }}</label>
                            <input type="text" name="lname" value="{{ $vendor->lname }}" class="w-full pt-8 pb-3 px-4 rounded-xl outline-none text-gray-900 font-semibold" required>
                        </div>
                    </div>

                    <p class="text-sm font-light text-gray-600 mb-8 border-b border-gray-200 pb-8">
                        {{ __('Ini akan menjadi nama resmi pada akun Anda.') }} <a href="#" class="underline font-semibold">{{ __('Pelajari selengkapnya') }}</a>
                    </p>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="bg-[#222222] hover:bg-black text-white font-semibold py-3.5 px-8 rounded-lg shadow-sm">
                            {{ __('Konfirmasikan') }}
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </main>

</body>
</html>
