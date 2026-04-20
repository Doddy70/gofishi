# Handoff - 2026-04-08

Dokumen ini adalah handoff resmi untuk agent berikutnya.

## PRIORITAS UTAMA (BELUM SELESAI)

**Bug kritikal checkout step auth masih aktif.**

- Gejala: setelah user login dari flow checkout, user tidak lanjut ke checkout step berikutnya.
- Perilaku aktual terakhir: user kembali ke halaman `http://127.0.0.1:8001/perahu`.
- Perilaku yang diharapkan: setelah auth sukses (login form, signup, social login), user lanjut ke `perahu/checkout?step=2`.

Status: **IMPLEMENTASI TERAKHIR BELUM BERHASIL** dan harus menjadi task pertama yang dilanjutkan.

## Ringkasan Bug Untuk Agent Lanjutan

- Flow booking: single product -> checkout -> auth modal -> lanjut checkout.
- Bug terjadi pada transisi auth -> checkout.
- Efek terhadap bisnis: checkout terputus, user tidak bisa melanjutkan pembayaran secara mulus.

## Inisiasi/Fix Yang Sudah Dikerjakan Hari Ini

### 1) Stabilitas backend + routing + error handling

- Tambah alias locale handler di `MiscellaneousController@setLanguage`.
- Tambah route missing untuk thank-you:
  - `frontend.perahu.booking.complete`
- Tambah alias route support ticket:
  - `user.support_ticket.index`
- Rapikan social route naming conflict:
  - primary: `user.login.provider` (`/user/login/{provider}`)
  - alias: `user.login.social` (`/user/login-social/{provider}`)
- Perbaikan `BookingController@complete` agar support parameter route/query.
- Null guard dan fallback di beberapa controller:
  - `ContactController`
  - `MidtransController`
  - `XenditController`
  - `PayPalController`
  - `SmartAiService`
  - `PerahuController`

### 2) Auth checkout flow (target utama), sudah dicoba tetapi belum tuntas

- `UserController@loginSubmit`:
  - support login dengan `username` atau `email`.
  - regenerate session setelah auth sukses.
  - redirect checkout bila ada context checkout.
- `UserController@signupSubmit`:
  - redirect ke checkout step saat context checkout aktif.
- `SocialLoginController`:
  - simpan intent checkout via session.
  - callback diarahkan ke checkout step jika auth dimulai dari checkout.
- `BookingController@checkout`:
  - set session marker checkout:
    - `checkout_redirect = true` (untuk guest)
    - `checkout_return_url = route('frontend.perahu.checkout', ['step' => 2])`
- `checkout.blade.php`:
  - awal step guest dipaksa ke auth step.
  - sosial login URL membawa `checkout_redirect=1`.
  - tombol submit login/signup modal diberi `formaction/formmethod/formnovalidate`.
  - modal auth/profile/info dipindahkan ke luar form checkout utama (hindari nested form invalid).

### 3) Config lokal OAuth

- Update callback URL lokal di `.env` ke port aktif `8001`.
- Masalah `redirect_uri_mismatch` Google diidentifikasi sebagai konfigurasi Google Cloud Console (external config).

## Mengapa Masih Gagal (Hipotesis Saat Ini)

Kemungkinan terbesar ada pada **checkout context session hilang** sebelum request `GET /perahu/checkout`, sehingga controller fallback ke `route('frontend.perahu')`.

Indikasi kuat:

- `BookingController@checkout` punya guard:
  - jika `room_id` / `package_id` di session kosong -> redirect ke `/perahu`.
- Ini cocok dengan gejala user selalu balik ke listing `perahu` setelah auth.

## Task Implementasi Berikutnya (Setelah Prioritas Utama)

Setelah bug prioritas utama selesai, lanjutkan task implementasi berikut:

1. Finalisasi pixel fidelity checkout modal agar mendekati pola Airbnb:
   - layout tab auth
   - spacing
   - hierarchy typography
   - behavior popup persetujuan beruntun
2. Verifikasi penuh alur checkout sampai halaman thank-you.
3. Tambahkan regression check singkat untuk mencegah auth-to-checkout regression.

## Rencana Eksekusi Agent Berikutnya (Disarankan)

1. Tambahkan logging sementara pada:
   - `UserController@loginSubmit`
   - `SocialLoginController@handleProviderCallback`
   - `BookingController@checkout`
2. Log minimal:
   - session id
   - `room_id`, `package_id`
   - `checkout_redirect`, `checkout_return_url`
   - redirect target final
3. Reproduksi 3 jalur:
   - login email/password dari checkout modal
   - signup dari checkout modal
   - social login dari checkout modal
4. Implement fix preserve session checkout yang robust.
5. Hapus logging sementara setelah fix terverifikasi.

## Catatan Verifikasi Yang Sudah Dilakukan Hari Ini

- Syntax checks (`php -l`) untuk controller yang diubah: lulus.
- Lint checks pada file yang diubah: tidak ada error.
- Laravel cache clear yang sudah dijalankan berkali-kali:
  - `php artisan view:clear`
  - `php artisan config:clear`
  - (sebelumnya juga `route:clear` dan `cache:clear` pada fase debugging awal)

