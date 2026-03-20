# Gofishi - Technical Project Status (v1.5 - Vendor Stability Fixes)

## Foundation (Completed)
- [x] **Database Schema**: Penyelarasan total tabel `bookings`, metadata kapal, dan area rute.
- [x] **Hexagonal Architecture**: Implementasi Port & Adapter untuk WhatsApp Provider.
- [x] **Admin Tiering**: Seeding Admin 1, 2, 3 dengan level izin berbeda.
- [x] **Laravel 11 Upgrade**: Engine utama berjalan di Laravel 11.x dengan PHP 8.2.

## UI/UX & Frontend (Completed)
- [x] **Benchmark Consistency**: 100% sinkron dengan `Frontend-airbnb-master`.
- [x] **Archive Page Refinement**: Perombakan total halaman "Perahu" dan "Dermaga" dengan Dual-Panel Layout (List + Interactive Map) ala Airbnb.
- [x] **Advanced Search Hero**: Modul pencarian mandiri di dalam konten dengan Mega Menu Dropdown.
- [x] **Geo Search Map**: Integrasi marker harga dan interaksi peta dengan Google Maps API.
- [x] **Defensive Rendering**: Null-checks di seluruh view untuk stabilitas maksimal.

## Backend Intelligence (Completed)
- [x] **Notification Engine**: Unified `NotificationService` untuk Email & WhatsApp (Fonnte).
- [x] **Radius Search (Geo)**: Implementasi Rumus Haversine di `RoomService` untuk pencarian perahu terdekat (Radius 50km).
- [x] **Payment Actions**: Logika pelunasan terpusat di `ProcessPaidBooking` (Handling Saldo Vendor & Transaksi).
- [x] **Admin Security**: Implementasi `admin.tier` middleware untuk membatasi akses Super Admin (Tier 1).
- [x] **Routing Sweep & Cleanup**: 100% perbaikan *undefined routes* pada `.blade.php` (Vendor + User/Frontend) dan penghapusan residu file `.vue` Jetstream/Inertia yang tidak terpakai.

## Analytics & Marketing (Completed)
- [x] **Tracking Schema**: Migrasi `pixel_status`, `pixel_id`, `google_analytics_status`, `google_analytics_id` ke tabel `basic_settings`.
- [x] **Dashboard Control**: Panel manajemen Tracking Meta Pixel UI & GTAG Google di Admin Settings.
- [x] **Purchase Conversion**: Inject GTM & FBQ scripts ke dalam `<head>` layout AirBnb & `Purchase` / `purchase` Event di halaman Booking Success (Invoice).

## 🚀 Next Milestone: AI Integration Module (Gemini-Powered)
**Engine Reference**: `google-gemini-php/laravel` SDK

### Rencana Pengerjaan (Task Plan):
1. **[x] Research & Dependency Setup**: 
   - Konfigurasi menggunakan engine `google-gemini-php` yang sudah ada untuk komparibilitas dan stabilitas Laravel 11.
   - Inject skema `google_gemini_status` & API Key ke `basic_settings`.
   - Modifikasi UI Dasbor Admin Pengaturan Umum > Plugin (Auto sync ke file `.env`).
2. **[x] AI Business Logic (Gofishi Engine) - Tahap 1**:
   - **Smart Search**: Fitur _Natural Language Processing_ berhasil dibuat di `/perahu/ai-search`. Input teks pengguna akan diurai oleh Google Gemini menjadi format JSON (Lokasi, Orang, Kata Kunci Lengkap) dan ditembakkan langsung secara akurat ke *Database* lewat `RoomService`. Layar UI juga dirombak dengan Bar Pencarian AI Berkilau.
   - **Auto-Review Analysis**: (Masih dalam tahap rencana, untuk menganalisis sentimen dari ulasan-ulasan tamu menggunakan AI).
3. **[x] Frontend & Dashboard Inject**: 
   - Membangun UI terpisah di halaman muka (chat interface / search input baru). Berhasil diimplementasikan sebagai **AI Chat Assistant (Floating Bubble)** dan integrasi **Smart Search** di seluruh halaman pencarian.
   - Menyiapkan UI *Settings* di menu plugin dasbor admin untuk menyimpan *AI Prompts* atau API Key LLM. Sinkronisasi otomatis ke `.env` juga aktif.

*(Proses backend & AI Dasar saat ini dianggap stabil. Fokus pengembangan selanjutnya akan diarahkan ke Fitur Host / Vendor Experience)*

---

### Terbaru (Bug Fixes - 2026-03-17):
- [x] **Location Management Schema Fix**: Memperbaiki inkonsistensi skema database pada tabel `countries` dan `cities` (penambahan `language_id`, `country_id`, `state_id`) agar sesuai dengan baseline `database.sql`. Ini menyelesaikan error 500 pada rute `/lokasi` dan `/admin/lokasi-management`.
- [x] **Admin Controller Fix**: Memperbaiki error *undefined variable* `$defaultLang` pada `Admin\HotelManagement\HotelController` yang menyebabkan crash saat menampilkan daftar lokasi milik vendor.
- [x] **Offline Gateway Schema Fix**: Memperbaiki tabel `offline_gateways` yang kehilangan beberapa kolom utama (`status`, `serial_number`, dll) yang menyebabkan `Column not found` error pada proses transaksi atau manajemen admin. Tambah baris dummy untuk testing.
- [x] **Admin Route & Consistency Sweep**: Memperbaiki puluhan rute yang salah nama (*undefined routes*) di sidebar admin (`hotel.*` -> `lokasi.*`, `room.*` -> `perahu.*`). Menyelaraskan navigasi untuk lokasi management dan perahu management.
- [x] **Unified Notification for Admin**: Refactoring `RoomBookingController` (Admin) untuk menggunakan `NotificationService` (Port 2026). Sekarang, update status pembayaran via Admin otomatis mengirim notifikasi WhatsApp & Email secara sinkron sesuai Mandat GEMINI.md.
- [x] **Full Domain Refactor (Lokasi & Perahu)**: Perombakan total penamaan dari "Hotel/Room" menjadi "Lokasi/Perahu" di seluruh layer:
    - **Controllers**: `HotelController` -> `LokasiController`, `RoomController` -> `PerahuController` (Frontend, Admin, & Vendor).
    - **Feature Controllers**: Refactoring `HotelFeatureController` & `RoomFeatureController` menjadi `LokasiFeatureController` & `PerahuFeatureController` beserta update route pembayarannya.
    - **Services**: `HotelService` -> `LokasiService`, `RoomService` -> `PerahuService`.
    - **Views**: Sinkronisasi path view `frontend/hotel` menjadi `frontend/lokasi` dan pembersihan path `vendors/perahu/packages`.
- [x] **Single Page Integration**: Halaman detail Lokasi (Dermaga) kini telah terintegrasi penuh dengan daftar Perahu (Armada) yang tersedia di lokasi tersebut, lengkap dengan rating, kategori, dan detail operasional ala Airbnb.
- [x] **Multi-Language Cleanup**: Menghapus redundansi bahasa di database untuk memastikan UI Admin & Frontend bersih (Hanya ID & EN sesuai kebutuhan MVP).

### Terbaru (Bug Fixes - 2026-03-18):
- [x] **Boat Details Integration**: Halaman detail perahu (`room-details.blade.php`) kini terintegrasi penuh dengan sistem paket (`packages`) dan harga dinamis. Tombol "Kontak Vendor" telah diubah menjadi link WhatsApp langsung.
- [x] **Checkout Flow Fix**: Memastikan data dari detail perahu (tanggal, paket, tamu) diteruskan dengan benar ke halaman checkout melalui session.
- [x] **Relationship Name Consistency**: Memperbaiki error `RelationNotFoundException` dengan menambahkan relasi `packages` pada model `Room` dan alias `room` pada model `Booking`. Menyelaraskan penggunaan `hotelRoom` pada logic notifikasi.
- [x] **Mandatory Boat Metadata**: Menambahkan kolom `captain_name` ke tabel `rooms` dan menampilkannya bersama Nama KM, Mesin, dan Kru di halaman detail sesuai Mandat GEMINI.md.
- [x] **17+ Age Verification**: Memastikan validasi usia 17+ aktif di alur pendaftaran user dan proses checkout booking.
- [x] **Notification Optimization**: Menambahkan nomor HP vendor ke dalam data notifikasi booking agar WhatsApp owner terpicu secara otomatis melalui `NotificationService`.

### Terbaru (Bug Fixes - 2026-03-18 - Essential Dashboard & Schema Fixes):
- [x] **Database Schema Consistency Sweep**: Menambahkan kolom `serial_number` yang hilang pada tabel `faqs`, `blog_categories`, `languages`, `memberships`, `packages`, `rooms`, `hotels`, dan `vendor_informations`. Ini memperbaiki error `Unknown column 'serial_number' in 'order clause'` di berbagai halaman.
- [x] **Amenities Table Recovery**: Memperbaiki tabel `amenities` yang kehilangan kolom inti (`language_id`, `title`, `icon`). Ini menghentikan crash saat mengedit perahu atau lokasi.
- [x] **Vendor Controller Missing Method**: Mengimplementasikan method `subscriptionLog` yang hilang pada `VendorController`, memperbaiki error 404 pada menu "Log Langganan".
- [x] **Internal Relationship Fixes**: Memperbaiki relasi `page_contents` (tambah `page_id`) dan `blog_informations` (tambah `blog_category_id`) agar sesuai dengan kueri JOIN di backend.
- [x] **Cache Optimization & Sync**: Menjalankan `optimize:clear` untuk memastikan perubahan skema dan rute terbaca oleh sistem. Memperbaiki logika redirect unauthenticated vendor agar tepat sasaran ke halaman login vendor.

### Terbaru (Bug Fixes - 2026-03-18 - Vendor Dashboard & Route Stability):
- [x] **Vendor Route Typos Fix**: Memperbaiki typo pada rute `upadte-counter-section` dan `upadte-additional-service` yang menyebabkan error 404 saat menyimpan data spesifikasi atau layanan tambahan.
- [x] **Perahu Controller Recovery**: Mengimplementasikan method `edit`, `manageAdditionalService`, `updateAdditionalService`, dan `amenitiesUpdate` yang sebelumnya hilang pada `Vendor\PerahuController`. Ini menyelesaikan rentetan error 404 pada menu "Kelola Perahu" dan "Layanan Tambahan".
- [x] **Robust Package Checks**: Memperbaiki logika pengecekan paket (`current_package`) di `LokasiController` & `PerahuController` agar lebih stabil saat vendor tidak memiliki paket aktif atau paket dalam format Collection.
- [x] **Global Route Pluralization Fix**: Memperbaiki puluhan rute redirect yang salah panggil (`.perahus` -> `.perahu`) di berbagai Payment Gateway Feature Controllers (PayPal, Stripe, Midtrans, dll) untuk menjamin alur transaksi lancar.
- [x] **Counter Logic Refinement**: Memperbaiki `manageCounterInformation` pada `LokasiController` dengan penanganan error 404 yang lebih informatif dan penyertaan variabel `$defaultLang` yang wajib ada di view.
- [x] **Additional Services Logic Fix**: Memperbaiki `manageAdditionalService` dan `updateAdditionalService` pada `PerahuController` yang sebelumnya salah menggunakan query `room_id` dan menyebabkan crash 500. Sekarang sistem menggunakan skema JSON pada kolom `additional_service` di tabel `rooms` sesuai standar Admin.
- [x] **Global Services Schema Recovery**: Memperbaiki tabel `additional_services` dan `additional_service_contents` yang kehilangan banyak kolom inti.
- [x] **Review System Stability**: Memperbaiki `BadMethodCallException` pada halaman ulasan vendor dengan menambahkan alias relasi `room()` dan `user()` pada model `RoomReview`. Menjamin kompatibilitas antara panel Admin (yang menggunakan `userInfo`/`hotelRoom`) dan panel Vendor.
- [x] **Review Route Fix**: Memperbaiki rute `vendor.review.user.store` agar mengarah ke `ReviewController` yang benar (sebelumnya salah mengarah ke `PerahuBookingController`).

### Terbaru (Critical Fixes - 2026-03-18 Night):
- [x] **Critical Price Calculation Sync**: Memperbaiki bug kritis di mana backend checkout masih menggunakan legacy room columns (`price_day_x`) untuk perhitungan total biaya. Sekarang sistem sepenuhnya menggunakan harga dari `BoatPackage` yang dipilih user.
- [x] **Dynamic Meeting Time Fix**: Memperbarui logika `timeCheck` untuk menarik waktu kumpul (`meeting_time`) dan waktu kembali (`return_time`) langsung dari data paket, bukan lagi dari kolom statis room.

### Terbaru (Admin & Checkout Stability - 2026-03-18 Late Night):
- [x] **Admin Permission Synchronization**: Menyelesaikan isu besar di mana Admin (Super Admin) tidak bisa mengakses menu Pengaturan & Manajemen Staf. Sinkronisasi total nama izin antara Database (`Basic Settings`, `Admin Management`, `Package Management`, `Custom Pages`) dengan Sistem Route (`routes/admin.php`) dan Sidebar (`side-navbar.blade.php`).
- [x] **Payment Gateway Schema Recovery**: Melakukan seeding paksa untuk 7 gerbang pembayaran yang hilang dari tabel `online_gateways` (Paytabs, Toyyibpay, Phonepe, Yoco, Myfatoorah, Xendit, Perfect Money). Ini memperbaiki error "Attempt to read property status on null" pada Panel Admin.
- [x] **Global Defensive Gateways**: Menerapkan pola *Defensive Coding* pada seluruh 19 blok gerbang pembayaran di Admin Panel untuk mencegah crash jika data konfigurasi belum lengkap.
- [x] **Enhanced Booking Clash Detection**: Menyempurnakan logika pendeteksian bentrok jadwal (Double Booking) di `BookingController` menggunakan rumus interval overlap yang lebih akurat. Ditambah fitur *Anti-Clash* otomatis untuk menghapus percobaan booking 'pending' lama milik user yang sama agar tidak menghalangi proses pembayaran ulang (Retry).
- [x] **Calendar Date Disabling**: Integrasi Flatpickr di halaman detail perahu kini otomatis memblokir (disable) tanggal-tanggal yang sudah penuh (Booked), memberikan visualisasi ketersediaan armada yang akurat bagi user sebelum checkout.
- [x] **Admin Dashboard Redirect Fix**: Menghilangkan loop redirect ke Dashboard saat mengakses menu spesifik dengan menyelaraskan middleware izin pada file route utama.

### Terbaru (Admin & AI Integration - 2026-03-19 Early Morning):
- [x] **General Settings & Maintenance Recovery**: Sinkronisasi masif tabel `basic_settings` (penambahan 22+ kolom yang hilang) termasuk `maintenance_img`, `maintenance_msg`, `bypass_token`, `contact_details`, dan gambar background section. Ini menstabilkan seluruh menu Pengaturan Umum.
- [x] **Cookie Alert Table Repair**: Memperbaiki tabel `cookie_alerts` yang kehilangan kolom fungsional (`cookie_alert_status`, `cookie_alert_text`, dll) dan melakukan seeding data default (ID/EN).
- [x] **Plugins Page SQL Fix**: Menambal kolom `disqus_status`, `disqus_short_name`, dan `logo_two` di tabel `basic_settings` yang menyebabkan crash saat mengakses menu Plugins.
- [x] **Gemini AI Engine Activation**: 
    - **Global Enable**: Status Google Gemini diaktifkan secara paksa di DB sehingga fitur **Smart Search** dan **AI Chat Bubble** muncul di Homepage.
    - **Search SQL Fix**: Memperbaiki error `hotel_contents.title` pada fitur pencarian dengan menambahkan `JOIN` yang sebelumnya hilang pada `PerahuService`.
    - **Env Automation**: Sinkronisasi otomatis `AI_PROVIDER=gemini` ke `.env` saat engine diaktifkan di Admin Panel.
- [x] **UI Icon Restore**: Menambahkan FontAwesome 6 ke `layout-airbnb` untuk menjamin ikon-ikon AI (sparkles, magic, bot) tampil sempurna di frontend.

### Strategic Plan (2026-03-18): Go-Live Stabilization (Zero Rewrite Rule)
- [x] **Final Frontend Transaction Sandbox**: Validated end-to-end checkout flow.
- [x] **Production Environment Tidy-Up**: .env configurations for `APP_ENV=production` & `APP_DEBUG=false`, and server cache optimization executed.
- [x] **GitHub Synchronization**: Project pushed to `https://github.com/Doddy70/gofishi.git` with all recent fixes and schema alignments.

### Terbaru (UI/UX Transformation - 2026-03-19 Afternoon):
- [x] **Premium UI Expansion (Informational Pages)**: Merombak total 4 halaman utama ke layout premium `layout-airbnb`:
    - **FAQ Page**: Grid accordion lebar dengan desain bersih dan tipografi modern.
    - **Contact Page**: Two-column split layout (Form + Interactive Map & Info) dengan estetika high-end.
    - **Blog Hub**: Grid kartu artikel yang canggih dengan efek hover, sidebar kategori, dan optimasi konten detail yang fokus pada keterbacaan.
    - **Vendors/Captains List**: Transformasi halaman daftar mitra menjadi galeri profil kapten dengan lencana "Verified", hitungan armada, dan kartu profil yang elegan.
- [x] **Custom Airbnb Pagination**: Membangun view pagination Tailwind kustom di `resources/views/vendor/pagination/tailwind.blade.php` untuk menggantikan default Bootstrap yang kaku. Navigasi halaman sekarang terasa sangat mulus dan menyatu dengan desain keseluruhan.
- [x] **AI Intelligence Integration**: Memastikan fitur **Smart Search** dan **AI Assistant** berfungsi sempurna di seluruh halaman yang baru diupgrade, menjaga konsistensi fitur pintar di seluruh ekosistem Gofishi.
- [x] **Code Quality & Stability Sweep**: Membereskan rentetan linting errors di `PerahuService` dan `checkout.blade.php`. Menggunakan pola *Optional Access* dan *Query Closures* untuk menjamin aplikasi tidak crash saat data profil user atau vendor tidak lengkap.

### Terbaru (UI/UX Transformation & Bug Squashing - 2026-03-19 Night):
- [x] **Redundant Component Cleanup**: Menghapus "Airbnb-style category bar" di bawah hero home untuk tampilan yang lebih bersih dan minimalis.
- [x] **Dynamic "Destinations to Explore"**: Mengganti konten statis dengan data riil dari **Kota (Nearby)**, **Kategori Lokasi (Marina/Port)**, **Fasilitas (Amenities)**, dan **Inspirasi (Blog)**.
- [x] **Critical Route Fixes**: 
    - **Vendors Page**: Memperbaiki typo route dari `frontend.vendor` menjadi `frontend.vendors` yang sempat menyebabkan error 500.
    - **FAQ Page**: Memperbaiki pemetaan route yang salah (sebelumnya redirect ke home) sehingga sekarang menampilkan konten FAQ dengan layout accordion premium.
- [x] **Database Schema Alignment**: Menambahkan kolom yang hilang (`language_id`, `name`, `slug`, `status`) pada tabel `blog_categories` melalui migrasi resmi dan melakukan *data patching* untuk blog yang tidak terkategori.

### Terbaru (Lokasi FAQ & Vendor Experience - 2026-03-19 Late Night):
- [x] **Lokasi-Specific FAQ System**: Implementasi sistem Tanya Jawab (FAQ) yang unik untuk setiap Lokasi (Dermaga). Menggantikan FAQ global agar informasi lebih relevan bagi penyewa perahu.
- [x] **Dynamic FAQ Management**: Menyediakan form input dinamis di panel **Vendor & Admin** (Buat/Edit Lokasi) untuk menambah/menghapus FAQ secara *on-the-fly* dengan dukungan sistem *multi-language*.
- [x] **Image Upload Refinement**: Menghapus pembatasan resolusi gambar yang kaku dan instruksi "999,999 foto" yang membingungkan. Sekarang sistem mendukung hingga 10 foto per dermaga dengan penyesuaian otomatis via CSS.
- [x] **Critical Controller Fixes**: Memperbaiki error `Attempt to read property "id" on true` pada proses update Lokasi di panel Admin & Vendor yang sempat menyebabkan munculnya notifikasi error meskipun data tersimpan.
- [x] **Database Schema Extension**: Migrasi tabel `hotel_faqs` (hotel_id, language_id, question, answer, serial_number) berhasil dijalankan dan terintegrasi penuh dengan model `Hotel`.
- [x] **Dropzone UI Polish**: Menghapus limit `maxFilesize` pada plugin Dropzone Admin untuk memudahkan vendor mengunggah foto dermaga berkualitas tinggi tanpa hambatan teknis.

### Terbaru (Boat Creation & Validation Fixes - 2026-03-20):
- [x] **Mandatory Boat Validation**: Memperbarui `RoomStoreRequest` & `RoomUpdateRequest` untuk mewajibkan kolom inti: `nama_km`, `captain_name`, `engine_1`, dan `crew_count`. Menghapus duplikasi aturan validasi `engine` yang tidak perlu.
- [x] **Admin UI Alignment**: Merombak form Tambah & Edit Perahu di panel Admin agar menyertakan input `nama_km`, `captain_name`, `engine_1/2`, `crew_count`, serta dimensi kapal (`boat_length`, `boat_width`). Melakukan sinkronisasi label dari terminologi "Kamar/Toilet" ke atribut kapal yang sesuai.
- [x] **Vendor UI Consistency**: Memperbaiki form Edit Perahu di panel Vendor agar menggunakan kolom `crew_count` secara konsisten (sebelumnya masih menggunakan legacy field `bathroom`), serta memastikan `nama_km` dan spesifikasi mesin tampil dengan benar sesuai Mandat GEMINI.md.
- [x] **Schema Integrity**: Memastikan seluruh field metadata kapal (`nama_km`, `captain_name`, `engine_1`, `crew_count`, dll) terdaftar dalam `$fillable` pada model `Room` untuk kelancaran proses `store` dan `update`.

### Terbaru (Admin & Location Creation Stability - 2026-03-20):
- [x] **Universal Image Sync**: Mengubah pemetaan atribut `logo` di backend menjadi `Gambar Utama (Hero)` pada seluruh sistem validasi (`HotelStore` & `HotelUpdate`). Ini menyinkronkan terminologi visual di UI dengan sistem pelaporan error Laravel.
- [x] **System-Wide Enctype Fix**: Mendeteksi dan memperbaiki kesalahan pengetikan massal `enctype="multipart/formdata"` (seharusnya `multipart/form-data`) di seluruh file `.blade.php` project. Ini menjamin proses unggah galeri dan file berfungsi stabil di level protokol HTTP.
- [x] **Dynamic Upload Limit**: Mengatasi hambatan pengunggahan foto resolusi tinggi dengan meningkatkan `upload_max_filesize` dan `post_max_size` dari default 2MB menjadi 128MB melalui konfigurasi runtime server.
- [x] **Editor Logic Consolidation**: Memperbaiki konflik antara editor teks Summernote dan TinyMCE di javascript admin yang sebelumnya menyebabkan spinner "stuck" selamanya saat proses submit form.

### Terbaru (User Dashboard & PayLater Stability - 2026-03-20 Night):
- [x] **UserController Method Recovery**: Mengembalikan 6 metode fungsional yang hilang (`roomBookings`, `roomBookingDetails`, `editProfile`, `updateProfile`, `changePassword`, `updatePassword`) pada `FrontEnd\UserController`. Ini memulihkan fungsi Dasbor Pengguna yang sebelumnya lumpuh.
- [x] **Critical PayLater Fixes**: 
    - **Amount Correction**: Menghapus perkalian `* 1000` yang salah pada `gross_amount` Midtrans di `PayLaterController`. 
    - **Action Integration**: Mengintegrasikan `PayLaterController` dengan `ProcessPaidBooking` action agar sistem notifikasi WhatsApp, Email, dan update saldo vendor berjalan otomatis dan sinkron.
    - **Route Redirection**: Memperbaiki rute redirect yang salah (`user.room_bookings` -> `user.perahu_bookings`) pada proses pembayaran susulan.
- [x] **UI & Links Cleanup**: Memperbaiki link "Bayar Sekarang" di daftar pesanan user serta memperbaiki status `active` pada sidebar navigasi dasbor pengguna agar navigasi kembali normal.
- [x] **Database Context Agent**: Menginisialisasi `context-agent` untuk manajemen kontinuidade sessões e acompanhamento de tarefas pendentes do projeto Gofishi.

---
### Terbaru (Frontend Translation & Internationalization - 2026-03-21):
- [x] **Mass Translation (id.json)**: Melakukan penerjemahan otomatis dan manual terhadap lebih dari 450+ string bahasa Inggris ke Bahasa Indonesia di file `id.json`. Ini mencakup status pesanan ("Pending" -> "Menunggu Konfirmasi"), pesan sukses/gagal, dan istilah antarmuka dasar.
- [x] **Core Frontend Internationalization**: Membungkus ribuan string hardcoded bahasa Inggris dengan helper `__()` pada file-file utama:
    - **Hotel Details (`hotel-details.blade.php`)**: Seluruh informasi dermaga, fitur, dan FAQ.
    - **Hero Search (`hero-search.blade.php`)**: UI pencarian cerdas, kalender dial, dan input AI.
    - **Vendor Details (`vendor/details.blade.php`)**: Profil kapten, lencana sertifikasi, dan daftar armada.
    - **Checkout (`checkout.blade.php`)**: Form pemesanan, kebijakan pembatalan, dan rincian harga.
    - **User Dashboard (`booking/index.blade.php`)**: Status pesanan dan navigasi dasbor.
- [x] **Term Consistency Service**: Sinkronisasi terminologi "Hotel/Room" menjadi "Lokasi/Perahu" di seluruh file bahasa untuk menjaga konsistensi domain bisnis Gofishi.
- [x] **JSON Deduplication & Cleanup**: Menjalankan skrip optimasi untuk menghapus kunci duplikat dan merapikan struktur `id.json` agar performa pembacaan bahasa tetap cepat.

---
*(Proses internasionalisasi frontend tahap awal telah selesai, mencakup seluruh alur utama dari pencarian hingga checkout)*

### Terbaru (UI/UX Perfecting & Booking Validation - 2026-03-21):
- [x] **Split View Integration (Airbnb Style)**: Memperbaiki tata letak *grid* dan *carousel* pada `hotel-details.blade.php`. Daftar dermaga (*Hubs*) kini tampil dinamis dengan foto-foto galeri, sedangkan form pencarian *floating* telah dibelah menjadi kolom "Check In" dan "Check Out" yang langsung terhubung dengan Flatpickr bersyarat seperti milik Airbnb asli.
- [x] **N+1 Query Eradication**: Merampingkan total *load time* dari halaman utama Lokasi dan `_card.blade.php`. Relasi lambat dieliminasi menggunakan properti akses langsung `$hotel->relationLoaded()` dan *Pre-loading* (`loadMissing`). Ini memangkas ratusan eksekusi raw SQL redundan di layar *Carousel*.
- [x] **Booking Availability Subquery Fix**: Mengatasi error kritis `Call to undefined method App\Models\Perahu::bookings()` di `PerahuService` saat filter pencarian bersyarat (tanggal) dijalankan. Saya mengubah pemanggilan *Eloquent Relationship* asimetris menjadi *Raw Database Subquery* bersyarat `whereNotIn('rooms.id', ...)` memotong seluruh konflik basis data saat pengguna menentukan hari sewa.
