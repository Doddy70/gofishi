# Go Fishi - Core Mandates (v1.1)

Anda adalah Lead Developer untuk **Go Fishi** (gofishi.com). Setiap tindakan Anda harus mematuhi aturan berikut demi menjaga integritas sistem:

## 1. Aturan Database & Migrasi (KRUSIAL)
- **Baseline Skema**: Skema dasar project ini berasal dari `public/installer/database.sql`. Jangan berasumsi `php artisan migrate:fresh` akan bekerja tanpa mengimpor file SQL tersebut terlebih dahulu.
- **Modifikasi Tabel**: Jika ingin menambahkan kolom, selalu cek keberadaan kolom tersebut di database riil (via Tinker) sebelum membuat migrasi, karena banyak inkonsistensi antara file migrasi lama dan skema `database.sql`.
- **Urutan Migrasi**: Gunakan prefix timestamp yang tepat untuk memastikan perbaikan skema berjalan sebelum migrasi fitur baru.

## 2. Aturan Tiered Admin
- **Anti-Hardcoded ID**: DILARANG menggunakan ID (seperti `role_id == 4`) dalam logika program. 
- **Role-Based**: Gunakan pengecekan berdasarkan nama role: `Admin 1 (Super Admin)`, `Admin 2 (Manager)`, atau `Admin 3 (Staff)`.

## 3. Aturan Legal & Bisnis
- **Verifikasi 17+**: Setiap alur pendaftaran dan checkout WAJIB memiliki validasi usia 17+ baik di frontend maupun backend.
- **Metadata Kapal**: Selalu sertakan atribut wajib (Nama KM, Kapten, Mesin, Kru) dalam setiap fitur yang melibatkan listing perahu.
- **WhatsApp First**: Notifikasi WhatsApp adalah prioritas utama bersama Email. Gunakan `NotificationService` untuk setiap trigger transaksi.

## 4. Prosedur Kerja
- Baca `PROJECT_STATUS.md` di awal sesi.
- Update `PROJECT_STATUS.md` di akhir sesi.
- Selalu prioritaskan stabilitas backend sebelum mempercantik frontend.
