# Gofishi Development Project

<div align="center">
  <img src="public/images/logo.png" alt="Gofishi Logo" width="200" onerror="this.src='https://placehold.co/200x200?text=Go+Fishi'">
  
  **Boat Rental Marketplace untuk Pemancingan & Wisata**
  <br>
  *Akses laut menjadi lebih mudah dan terpercaya bersama Gofishi.*

  [![Status](https://img.shields.io/badge/status-active_development-success.svg)]()
  [![Laravel](https://img.shields.io/badge/laravel-framework-red.svg)](https://laravel.com)
</div>

---

## 🌊 Tentang Project
**Gofishi** (gofishi.com) adalah platform business-to-consumer (B2C) skala menengah hingga besar yang dirancang khusus sebagai *Boat Rental Marketplace*. Sistem ini dibangun untuk mempermudah pelanggan dalam mencari, membooking, dan mengamankan perjalanan penyewaan kapal laut (untuk memancing maupun pariwisata), dengan manajemen inventaris (kapal/lokasi) di sisi admin dan vendor.

Di balik platform yang dinamis ini, kami mementingkan **stabilitas sistem backend**, **integritas database**, dan **kelancaran bisnis proses**.

## 🎯 Goals & Objektif Bisnis
Tujuan utama pengembangan platform Gofishi meliputi:
1. **Digitalisasi Penyewaan Kapal**: Menghadirkan portal tunggal untuk menghubungkan para nahkoda/pemilik kapal dengan pelanggan.
2. **Validasi & Keamanan Transaksi (Legalitas 17+)**: Menerapkan validasi umur yang ketat pada saat registrasi maupun checkout, memastikan bahwa penyewaan kapal mematuhi regulasi keamanan.
3. **Standarisasi Operasional Laut**: Mewajibkan kelengkapan data administratif kapal seperti *Nama KM, Nama Kapten, Deskripsi Mesin,* dan *Daftar Kru* pada setiap listing untuk keamanan penumpang.
4. **Komunikasi Cepat dan Andal (WhatsApp First)**: Memprioritaskan jalur komunikasi melalui WhatsApp Notifications dan Email untuk semua *trigger* transaksi, menjamin notifikasi realtime yang tepercaya.
5. **Akses Terdesentralisasi dengan Tier-Admin**: Menerapkan kontrol akses berjenjang (Super Admin, Manager, Staff) tanpa hardcode logic ID, memastikan pembagian wewenang yang aman dan mudah di-maintain.

---

## 👨‍💻 Identitas Developer & Pengembangan
Proyek pengembangan Gofishi di-maintained dan di-lead oleh instruksi arsitektur pengembangan yang sangat ketat untuk menjamin ketersediaan jangka panjang:

**Lead Developer**: Doddy Kapisha (`Doddy70`)  
**Repository Repo**: [https://github.com/Doddy70/gofishi.git](https://github.com/Doddy70/gofishi.git)

### Core Mandates & Rules Development
Setiap kolaborator dan proses pengembangan dalam project ini harus secara ketat mematuhi *Core Mandates (v1.1)*:
- **Baseline Skema**: Skema database dasar merujuk pada `public/installer/database.sql`. Pendekatan *migration* digunakan untuk *patching*, bukan untuk build-from-scratch.
- **Role-Based Security**: Pengecekan otorisasi ketat berbasis nama role (*Admin 1, Admin 2, Admin 3*), tanpa melibatkan *hardcoded* ID.
- **Workflow Stabil**: Mengutamakan stabilitas fungsional backend dan integrasi database terlebih dahulu sebelum mempercantik antarmuka (frontend).

---

## 🛠️ Stack Teknologi Indikatif
- **Backend**: Laravel Ecosystem (PHP)
- **Frontend**: Blade Templating, Tailwind CSS, Alpine.js / Frontend Frameworks
- **Database**: MySQL / PostgreSQL (Merujuk pada baseline DB `database.sql`)
- **Notifikasi**: Integrasi WhatsApp API Service, SMTP Email Service

---

*Informasi lebih lanjut mengenai status terkini dapat dilihat pada file `PROJECT_STATUS.md`.*
