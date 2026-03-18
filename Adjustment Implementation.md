# Gofishi Project - Technical Handoff Memo (15 Maret 2026)

## Status Terakhir
Sistem telah berhasil bermigrasi ke **Hybrid Blade-Tailwind System** dengan estetika Airbnb Master. Homepage dan alur pencarian utama telah dipulihkan ke konsep asli Gofishi namun dengan pembungkus navigasi premium.

---

## 1. Implementasi Frontend (UI/UX) - SELESAI
- **Master Layout:** `layout-airbnb.blade.php` menggunakan Tailwind CSS, Alpine.js, dan Flatpickr.
- **Navbar Airbnb:** 
    - Baris 1: Logo, Tab Navigasi, User Menu.
    - Baris 2: Floating Search Bar dengan **Contextual Dropdowns** (Bukan Modal).
- **Search System:**
    - **Lokasi:** Vertical Scroll List (Data dari tabel `cities`).
    - **Tanggal (3-Mode):** Integrasi Flatpickr untuk mode Dates (Kalender Ganda), Months (Slider), dan Flexible (Card Selection).
    - **Peserta:** Dropdown counter untuk Adults & Children.
- **Listing Cards:** `_card.blade.php` dirombak total (Aspect Ratio 1:1, Hover Scale, Image Slider).

## 2. Perbaikan Backend & Database - ON PROGRESS
- **Controller Restore:** `HomeController` dan `UserController` telah dikembalikan dari Inertia ke **Blade** untuk menghindari konflik render.
- **Fix SQL Errors:** 
    - Menghapus referensi kolom hantu: `is_featured`, `summary`, `serial_number` (pada tabel tertentu).
    - Memperbaiki join query di `HomeController` untuk mendukung data lokasi dermaga.
- **Infrastructure:** Tabel `cache` dan `sessions` telah dibuat untuk mencegah Error 500 pada level middleware.

## 3. Temuan Penting & Hutang Teknis (Debt)
- **Database Divergence:** Ada perbedaan antara kolom yang diminta oleh `GofishiDemoSeeder.php` (seperti `boat_length`, `engine_1`) dengan skema database saat ini. 
- **JS Conflict:** Masih ada sisa-sisa script lama yang memicu `require is not defined`. Ini tidak mematikan sistem tapi perlu dibersihkan.
- **Image Assets:** Banyak gambar demo masih "Broken" karena path di database menunjuk ke file yang tidak ada secara fisik.

---

## Panduan untuk Agent Berikutnya (Next Tasks)
1. **Schema Audit:** Jalankan migrasi yang mencakup kolom teknis perahu (lihat model `Perahu`) sebelum menjalankan `GofishiDemoSeeder`.
2. **Execute Seeder:** Jalankan `php artisan db:seed --class=GofishiDemoSeeder` untuk mendapatkan data produk yang relevan dengan Gofishi (perahu pancing).
3. **Admin Dashboard:** Teruskan pemolesan UI Admin menggunakan Tailwind (saat ini baru di level Layout CSS).
4. **Search Logic:** Pastikan input dari `nav-search-form-final` di Navbar benar-benar memfilter data di halaman `/perahu`.

**Teknologi Utama:** Laravel 11, Tailwind CSS, Alpine.js, Flatpickr.
