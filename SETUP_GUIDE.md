# Gofishi - Setup & Migration Guide (aaPanel & Local)

Panduan ini membantu Anda melakukan instalasi di localhost dan migrasi ke Live Server (aaPanel).

## 1. Persiapan Localhost (XAMPP / Laragon / Mac Native)
1.  **Database**: Buat database kosong bernama `rental_perahu`.
2.  **Import Schema**: JANGAN langsung `php artisan migrate`. Import file `public/installer/database.sql` terlebih dulu ke database Anda via phpMyAdmin atau CLI.
3.  **Konfigurasi .env**: 
    - Sesuaikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD`.
    - Pastikan `APP_URL=http://localhost:8000`.
4.  **Dependencies**:
    ```bash
    composer install
    npm install
    npm run build
    ```
5.  **Running**:
    ```bash
    php artisan serve --port=8000
    ```

## 2. Migrasi ke Live Server (aaPanel / Cloud Hosting)
1.  **Upload Files**: Upload semua file ke `/www/wwwroot/domain-anda.com`.
2.  **Permissions (PENTING)**:
    - Setel owner ke `www:www` (default aaPanel).
    - Berikan izin tulis ke folder berikut:
      ```bash
      chmod -R 775 storage
      chmod -R 775 bootstrap/cache
      chmod -R 775 public/assets # Penting untuk upload gambar
      ```
3.  **Database Live**:
    - Buat database di panel aaPanel.
    - Import `public/installer/database.sql` ke database LIVE tersebut.
4.  **Update .env Live**:
    - Ubah `APP_ENV=production` dan `APP_DEBUG=false`.
    - Sesuaikan kredensial database live.
    - Jalankan `php artisan key:generate`.
5.  **Perbaiki URL Database**:
    Jika ada data (seperti `about_us`) yang memiliki link hardcoded `https://hottlo.test`, ubahlah manual via Admin Panel atau jalankan query SQL:
    ```sql
    UPDATE about_us SET button_url = REPLACE(button_url, 'https://hottlo.test', 'https://domain-anda.com');
    ```
6.  **Storage Link**:
    ```bash
    php artisan storage:link
    ```
7.  **Optimasi Nginx**:
    - Gunakan template di `AAPANEL_NGINX.conf`.
    - Pastikan root directory mengarah ke `/public`.
    - Gunakan PHP version 8.2+.

## 3. Catatan Penting
- **Anti-Hardcoded Role**: Gunakan nama role ("Admin 1", etc) bukan ID.
- **Validasi 17+**: Wajib ada di form pendaftaran dan checkout.
- **WA Notification**: Pastikan `NotificationService` terhubung ke Fonnte/Penyedia WA Anda di .env.
