<p align="center">
  <img src="https://gofishi.com/assets/img/logo_v2.png" alt="GoFishi Logo" width="200">
</p>

# 🛥️ GoFishi - Boat Rental & Fishing Marketplace

**GoFishi** adalah platform marketplace modern (Airbnb Clone) yang dirancang khusus untuk komunitas *saltwater angling*. Platform ini menghubungkan pemilik kapal (Vendor/Host) dengan para pemancing (Users) untuk mempermudah proses penyewaan kapal charter dan pencarian lokasi memancing terbaik di Indonesia.

---

## 🌐 Live Website
Kunjungi platform kami secara langsung: [**gofishi.com**](https://gofishi.com)

---

## 🏗️ Arsitektur & Framework

Platform ini dibangun dengan standar pengembangan modern untuk memastikan skalabilitas dan performa tinggi:

- **Arsitektur:** **MVC (Model-View-Controller)** — Memisahkan logika bisnis, data, dan antarmuka pengguna untuk pemeliharaan kode yang lebih mudah.
- **Backend Framework:** **Laravel** — Framework PHP paling populer yang menawarkan keamanan dan ekosistem library yang luas.
- **Frontend Engine:** **Blade Templating & Tailwind CSS** — Memberikan tampilan yang modern, *clean*, dan sepenuhnya responsif (Airbnb-inspired UI).
- **Asset Bundler:** **Vite** — Mempercepat proses development dan optimasi performa saat *production*.
- **Database:** **MySQL** — Penyimpanan data yang relasional dan handal.

---

## 🚀 Fitur Utama

- **Premium Vendor Dashboard**: Pengelolaan armada, harga dinamis, dan kalender ketersediaan dengan antarmuka yang intuitif.
- **Smart Booking System**: Alur pemesanan *end-to-end* mulai dari pencarian hingga konfirmasi otomatis.
- **Payment Gateway Integration**: Terintegrasi dengan **Midtrans** (QRIS, VA, Bank Transfer) untuk keamanan transaksi.
- **Interactive Maps**: Integrasi presisi titik lokasi dermaga menggunakan **Google Maps API**.
- **Real-time Notifications**: Notifikasi status pesanan dikirimkan secara otomatis via **WhatsApp & Email API**.

---

## 🛠️ Tech Stack & Integrasi

| Category | Technology |
| --- | --- |
| **Framework** | Laravel |
| **Language** | PHP, JavaScript |
| **Styling** | Tailwind CSS / CSS3 |
| **Build Tool** | Vite |
| **Database** | MySQL |
| **Integrations** | Midtrans, Google Maps, WhatsApp API |

---

## 🔧 Panduan Instalasi (Development)

**Clone Repository:**
Pilih salah satu metode berikut:
- **HTTPS:** `https://github.com/Doddy70/gofishi.git`
- **SSH:** `git@github.com:Doddy70/gofishi.git`
- **GitHub CLI:** `gh repo clone Doddy70/gofishi`

```bash
# Contoh menggunakan HTTPS
git clone https://github.com/Doddy70/gofishi.git
cd basecode
```
2.  **Install PHP Dependencies:**
    ```bash
    composer install
    ```
3.  **Install JS Dependencies & Build:**
    ```bash
    npm install && npm run build
    ```
4.  **Setup Environment:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
5.  **Database Migration:**
    ```bash
    php artisan migrate
    ```
6.  **Run Development Server:**
    ```bash
    php artisan serve
    ```

---

## ⚓ Boat Attributes Managed
Setiap armada kapal mengelola data mendalam seperti:
- Nama KM (Kapal Motor) & Nama Kapten.
- Spesifikasi Mesin & Kapasitas Penumpang.
- Fasilitas (AC, Bedroom, Toilet, Wifi, Alat Pancing, dll).
- Alur Sewa Harian & Jam Operasional Dermaga.

---
*Developed with ❤️ for the Angling Community in Indonesia.*
