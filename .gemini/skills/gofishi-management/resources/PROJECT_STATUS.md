# Gofishi - Technical Project Status

## Foundation (Completed)
- [x] **Database Schema**: Penambahan field teknis kapal, paket harian (1-3 hari), jam kumpul/kembali, dan area rute pada tabel `rooms` dan `vendors`.
- [x] **Model Perahu**: Update `$fillable` dan alias untuk atribut kapal.
- [x] **Registration (User & Vendor)**:
    - [x] Implementasi `signupSubmit` di `UserController`.
    - [x] Validasi usia 17+ (DOB) dan Recaptcha.
    - [x] Penanganan upload dokumen vendor (KTP, SIM, Kepemilikan Kapal).
- [x] **Admin Tiering**: Seeding Admin 1, 2, 3 dengan level izin yang berbeda.
- [x] **Booking Logic**: 
    - [x] `BookingController@storeData` mendukung `approval` mode dan `direct`.
    - [x] Sinkronisasi harga berdasarkan `day_package` (1, 2, 3 hari).
- [x] **Frontend (Vendor)**: Form `create.blade.php` diperbarui dengan atribut kapal lengkap.
- [x] **Frontend (Guest)**: `room-details.blade.php` diperbarui dengan widget booking dinamis (pilih paket -> update jam & area).

## Ongoing Implementation (Current Phase)
- [x] **Admin Reports**: Implementasi laporan harian, mingguan, bulanan, dan custom.
- [x] **WhatsApp Notification**: Menghubungkan trigger booking/payment ke API WhatsApp (ready for API key) dan tombol chat langsung di produk.
- [x] **Review Moderation**: Fitur bagi Admin untuk menghapus ulasan tidak sesuai etika.

## Project Policies (Must Follow)
1. **Migration Safety**: Always verify table existence/columns via Tinker before creating alter migrations.
2. **Admin Auth**: Use `auth('admin')` and check roles by name, not ID.
3. **Age Law**: 17+ checkbox must be recorded in the `bookings.age_confirmed` column.
4. **WhatsApp**: All WA notifications must pass through `NotificationService@sendWhatsApp`.

## Next Steps (Prioritized)
1. **Admin Reports Enhancement**: Menambahkan filter periode cepat (Daily/Weekly/Monthly) di dashboard Admin.
2. **Review & Chat Refinement**: Memastikan Host bisa membalas chat dan ulasan (logic sudah ada, perlu UI check).
3. **Age Verification Checkbox**: Memastikan checkbox 17+ muncul di setiap alur checkout (View refinement).
