# Go Fishi - Technical Project Status (v1.4 - Final Backend Master)

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
