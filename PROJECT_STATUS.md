# Go Fishi - Technical Project Status (v1.5 - Vendor Stability Fixes)

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
2. **[x] AI Business Logic (Go Fishi Engine) - Tahap 1**:
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

### Strategic Plan (2026-03-18): Go-Live Stabilization (Zero Rewrite Rule)
- [x] **Final Frontend Transaction Sandbox**: Validated end-to-end checkout flow.
- [x] **Production Environment Tidy-Up**: .env configurations for `APP_ENV=production` & `APP_DEBUG=false`, and server cache optimization executed.
- [x] **GitHub Synchronization**: Project pushed to `https://github.com/Doddy70/gofishi.git` with all recent fixes and schema alignments.
