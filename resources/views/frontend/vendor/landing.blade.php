@extends('frontend.layout-airbnb')

@section('pageHeading')
  {{ __('Menjadi Host GoFishi - Ubah Perahu Anda Menjadi Penghasilan') }}
@endsection

@section('content')
<div x-data="{ price: 500000, nights: 1 }" class="bg-white overflow-hidden">
    
    {{-- Hero Section with p5.js Ripple Effect --}}
    <section class="relative h-[80vh] flex items-center justify-center overflow-hidden">
        {{-- Canvas Container for p5.js Ripple --}}
        <div id="ripple-canvas" class="absolute inset-0 z-0 opacity-40"></div>
        
        {{-- Hero Content --}}
        <div class="relative z-10 max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <h1 class="text-5xl md:text-7xl font-bold text-airbnb-dark leading-tight tracking-tight">
                    GoFishi-kan perahu Anda.<br>
                    <span class="text-airbnb-red">Dapatkan Cuan.</span>
                </h1>
                <p class="text-xl text-airbnb-gray font-light max-w-md">
                    Bergabunglah dengan komunitas pemilik kapal pancing dan perahu wisata terbesar di Indonesia. Mulai hasilkan uang hari ini.
                </p>
                <div class="pt-4">
                    <a href="{{ route('vendor.login') }}" class="inline-block bg-airbnb-red hover:bg-[#D70466] text-white text-lg font-bold py-4 px-10 rounded-xl transition shadow-lg hover:scale-105 transform">
                        Mulai Jadi Host
                    </a>
                </div>
            </div>
            
            {{-- Calculator Card (Airbnb Style) --}}
            <div class="bg-white p-8 rounded-3xl shadow-2xl border border-neutral-100 hidden md:block">
                <h3 class="text-2xl font-bold mb-6">Hitung Potensi Anda</h3>
                <div class="space-y-8">
                    <div>
                        <label class="block text-sm font-semibold text-airbnb-gray mb-4 uppercase tracking-wider">Harga Sewa per Trip</label>
                        <input type="range" x-model="price" min="200000" max="5000000" step="100000" 
                               class="w-full h-2 bg-neutral-200 rounded-lg appearance-none cursor-pointer accent-airbnb-red">
                        <div class="mt-2 text-3xl font-bold" x-text="'Rp ' + (parseInt(price).toLocaleString('id-ID'))"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-airbnb-gray mb-4 uppercase tracking-wider">Estimasi Trip per Bulan</label>
                        <input type="range" x-model="nights" min="1" max="30" step="1" 
                               class="w-full h-2 bg-neutral-200 rounded-lg appearance-none cursor-pointer accent-airbnb-red">
                        <div class="mt-2 text-3xl font-bold" x-text="nights + ' Trip'"></div>
                    </div>
                    <div class="pt-6 border-t border-neutral-100">
                        <p class="text-sm text-airbnb-gray uppercase font-semibold">Estimasi Penghasilan Bulanan</p>
                        <h2 class="text-5xl font-black text-airbnb-red mt-2" x-text="'Rp ' + (parseInt(price * nights).toLocaleString('id-ID'))"></h2>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Background Image (Low Opacity Backdrop) --}}
        <img src="{{ asset('assets/img/gofishi_host_hero.png') }}" class="absolute inset-0 w-full h-full object-cover -z-10 brightness-75 grayscale-[20%]">
    </section>

    {{-- GoFishi Shield (AirCover Style) --}}
    <section class="max-w-7xl mx-auto px-6 py-24">
        <div class="text-center space-y-4 mb-20">
            <h2 class="text-5xl font-bold flex items-center justify-center gap-4">
                <span class="text-airbnb-red italic">GoFishi</span> Shield
            </h2>
            <p class="text-2xl text-airbnb-gray font-light">Perlindungan menyeluruh untuk setiap perjalanan.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-16 border-b border-neutral-200 pb-20">
            <div class="space-y-4">
                <div class="w-12 h-12 text-airbnb-red"><i data-lucide="shield-check" class="w-full h-full"></i></div>
                <h4 class="text-xl font-bold">Verifikasi Identitas Host</h4>
                <p class="text-lg text-airbnb-gray font-light leading-relaxed">Sistem verifikasi keamanan kami bersifat komprehensif, memeriksa KTP, Surat Kelayakan Kapal, hingga Driving License kapten secara manual.</p>
            </div>
            <div class="space-y-4">
                <div class="w-12 h-12 text-airbnb-red"><i data-lucide="map-pin" class="w-full h-full"></i></div>
                <h4 class="text-xl font-bold">Lokasi Tervisualisasi</h4>
                <p class="text-lg text-airbnb-gray font-light leading-relaxed">Peta interaktif membantu pemancing menemukan perahu Anda di titik dermaga yang tepat tanpa rasa bingung.</p>
            </div>
            <div class="space-y-4">
                <div class="w-12 h-12 text-airbnb-red"><i data-lucide="heart-handshake" class="w-full h-full"></i></div>
                <h4 class="text-xl font-bold">Dukungan Darurat 24/7</h4>
                <p class="text-lg text-airbnb-gray font-light leading-relaxed">Tim bantuan khusus kami siap membantu Anda menangani kendala teknis atau masalah pesanan kapan saja, siang maupun malam.</p>
            </div>
        </div>
    </section>

    {{-- Steps Section --}}
    <section class="bg-neutral-50 py-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-20 items-center">
                <div class="space-y-12">
                    <h2 class="text-4xl font-bold leading-tight">Mulai jadi Host dalam 3 langkah mudah.</h2>
                    <div class="space-y-10">
                        <div class="flex gap-6">
                            <span class="text-3xl font-black text-neutral-300">1</span>
                            <div>
                                <h4 class="text-xl font-bold mb-2">Daftarkan Perahu Anda</h4>
                                <p class="text-airbnb-gray leading-relaxed text-lg">Buat akun, lengkapi profil, dan unggah dokumen legal Anda secara rahasia dan aman.</p>
                            </div>
                        </div>
                        <div class="flex gap-6">
                            <span class="text-3xl font-black text-neutral-300">2</span>
                            <div>
                                <h4 class="text-xl font-bold mb-2">Tentukan Jadwal & Harga</h4>
                                <p class="text-airbnb-gray leading-relaxed text-lg">Atur hari keberangkatan perahu dan pasang harga terbaik untuk pasar pemancing lokal.</p>
                            </div>
                        </div>
                        <div class="flex gap-6">
                            <span class="text-3xl font-black text-neutral-300">3</span>
                            <div>
                                <h4 class="text-xl font-bold mb-2">Terima Tamu & Cuan!</h4>
                                <p class="text-airbnb-gray leading-relaxed text-lg">Konfirmasi pesanan melalui WhatsApp atau dashboard, dan terima pembayaran langsung ke rekening Anda.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative group">
                    <img src="{{ asset('assets/img/speedboat.png') }}" class="rounded-3xl shadow-lg hover:scale-105 transition duration-500 w-full object-cover h-[500px]">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent rounded-3xl flex items-end p-10 text-white">
                        <p class="text-2xl font-bold italic text-white">"GoFishi membantu perahu saya tetap penuh pesanan setiap akhir pekan!" - Capt. Mas Jawir</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ Section --}}
    <section class="max-w-3xl mx-auto px-6 py-32">
        <h2 class="text-4xl font-bold mb-16 text-center">Tanya Jawab Host</h2>
        <div x-data="{ active: null }" class="space-y-4">
            {{-- FAQ 1 --}}
            <div class="border-b border-neutral-200">
                <button @click="active === 1 ? active = null : active = 1" class="w-full py-6 flex justify-between items-center text-left hover:text-airbnb-red transition">
                    <span class="text-xl font-bold">Apakah pendaftaran ini dipungut biaya?</span>
                    <i data-lucide="chevron-down" class="transition" :class="active === 1 ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="active === 1" x-collapse x-cloak class="pb-6 text-lg text-airbnb-gray leading-relaxed">
                    Sama sekali GRATIS. Kami tidak memungut biaya pendaftaran awal. Kami hanya mengambil komisi kecil dari setiap transaksi yang sukses untuk membantu operasional website.
                </div>
            </div>
            {{-- FAQ 2 --}}
            <div class="border-b border-neutral-200">
                <button @click="active === 2 ? active = null : active = 2" class="w-full py-6 flex justify-between items-center text-left hover:text-airbnb-red transition">
                    <span class="text-xl font-bold">Dokumen apa saja yang harus saya siapkan?</span>
                    <i data-lucide="chevron-down" class="transition" :class="active === 2 ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="active === 2" x-collapse x-cloak class="pb-6 text-lg text-airbnb-gray leading-relaxed">
                    Anda memerlukan KTP, Surat Kepemilikan Perahu (Boat Ownership), dan Lisensi Nahkoda (Driving License) yang aktif untuk menjaga standar keamanan.
                </div>
            </div>
            {{-- FAQ 3 --}}
            <div class="border-b border-neutral-200">
                <button @click="active === 3 ? active = null : active = 3" class="w-full py-6 flex justify-between items-center text-left hover:text-airbnb-red transition">
                    <span class="text-xl font-bold">Bagaimana jika tamu (Angler) membatalkan sewa?</span>
                    <i data-lucide="chevron-down" class="transition" :class="active === 3 ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="active === 3" x-collapse x-cloak class="pb-6 text-lg text-airbnb-gray leading-relaxed">
                    Kami memiliki kebijakan pembatalan yang fleksibel namun adil. Anda tetap akan mendapatkan biaya kompensasi tertentu tergantung pada berapa hari sebelum keberangkatan pembatalan dilakukan.
                </div>
            </div>
        </div>
    </section>

    {{-- Final CTA --}}
    <footer class="bg-airbnb-dark text-white py-24 text-center">
        <h2 class="text-5xl font-black mb-8 leading-tight">Siap Meluncurkan Bisnis<br>Mancing Anda?</h2>
        <a href="{{ route('vendor.login') }}" class="inline-block bg-white text-airbnb-dark hover:bg-neutral-100 text-xl font-bold py-5 px-12 rounded-xl transition shadow-xl hover:scale-110 transform">
            Mulai Jadi Host Sekarang
        </a>
    </footer>

</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.7.0/p5.min.js"></script>
<script>
    // Algorithmic Art: Water Ripples Effect (Seeded)
    // Philosophy: Discrete entities reacting to external forces creating organic waves.
    
    let cols;
    let rows;
    let current = [];
    let previous = [];
    let dampening = 0.99;

    function setup() {
        let container = document.getElementById('ripple-canvas');
        let canvas = createCanvas(container.offsetWidth, container.offsetHeight);
        canvas.parent('ripple-canvas');
        pixelDensity(1);
        cols = width;
        rows = height;
        for (let i = 0; i < cols; i++) {
            current[i] = [];
            previous[i] = [];
            for (let j = 0; j < rows; j++) {
                current[i][j] = 0;
                previous[i][j] = 0;
            }
        }
    }

    function mouseDragged() {
        if (mouseX > 0 && mouseX < width && mouseY > 0 && mouseY < height) {
            previous[floor(mouseX)][floor(mouseY)] = 255;
        }
    }

    function draw() {
        background(255, 255, 255, 0); // Transparent background
        loadPixels();

        for (let i = 1; i < cols - 1; i++) {
            for (let j = 1; j < rows - 1; j++) {
                current[i][j] =
                    (previous[i - 1][j] +
                        previous[i + 1][j] +
                        previous[i][j - 1] +
                        previous[i][j + 1]) / 2 - current[i][j];
                current[i][j] = current[i][j] * dampening;
                
                let index = (i + j * cols) * 4;
                pixels[index + 0] = 255; // Red
                pixels[index + 1] = 56;  // Green (Airbnb Red hex)
                pixels[index + 2] = 92;  // Blue
                pixels[index + 3] = current[i][j] * 5; // Alpha linked to ripple
            }
        }
        updatePixels();

        let temp = previous;
        previous = current;
        current = temp;
        
        // Random drops to keep it "alive"
        if (frameCount % 60 == 0) {
            previous[floor(random(1, cols - 1))][floor(random(1, rows - 1))] = 255;
        }
    }
    
    window.onresize = function() {
        let container = document.getElementById('ripple-canvas');
        resizeCanvas(container.offsetWidth, container.offsetHeight);
    };
</script>
@endsection
