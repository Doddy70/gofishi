# GoFishi - Boat & Rental Booking Platform

GoFishi adalah platform modern untuk penyewaan kapal dan lokasi memancing, yang dirancang untuk memberikan pengalaman terbaik bagi vendor maupun pelanggan.

## Fitur Utama

- **Dashboard Vendor Premium**: Modern, responsif, dan mudah digunakan (Airbnb-inspired).
- **Manajemen Listing**: Kelola kapal, lokasi, dan ketersediaan dengan mudah.
- **Sistem Pemesanan**: Alur pemesanan yang lancar bagi pelanggan.
- **Integrasi Peta**: Penentuan lokasi menggunakan Google Maps API.
- **Notifikasi WhatsApp**: Notifikasi otomatis untuk pesanan baru.

## Tech Stack

- **Backend**: Laravel (PHP)
- **Frontend**: Blade, Tailwind CSS, Vite
- **Database**: MySQL
- **Integrasi**: Google Maps API, WhatsApp API

## Cara Instalasi

1. Clone repository ini:
   ```bash
   git clone https://github.com/username/gofishi-project.git
   ```
2. Install dependensi PHP:
   ```bash
   composer install
   ```
3. Install dependensi JavaScript:
   ```bash
   npm install && npm run build
   ```
4. Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
5. Generate application key:
   ```bash
   php artisan key:generate
   ```
6. Konfigurasi database di file `.env`.
7. Jalankan migrasi:
   ```bash
   php artisan migrate
   ```
8. Jalankan server lokal:
   ```bash
   php artisan serve
   ```

## Lisensi

[Tentukan Lisensi - Contoh: MIT]
