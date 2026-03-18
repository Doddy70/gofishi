# Domain Mapping (Draft)

Tanggal: 2026-02-27
Status: Draft (menunggu konfirmasi user)

## Tujuan
Menyesuaikan domain “Hotel/Room Booking” menjadi “Marketplace Sewa Perahu”.

Dokumen ini menyajikan opsi mapping istilah dan dampaknya agar tim bisa memilih arah refactor dengan aman.

---

## Opsi Mapping

### Opsi A (Disarankan untuk kemiripan struktur)
- **Hotel** → **Lokasi**
- **Room** → **Perahu**

Alasan:
- Struktur saat ini: Hotel (parent) punya banyak Room (child). Ini selaras dengan “Operator/Lokasi” memiliki banyak Perahu.
- Pencarian berbasis lokasi (state/city/country) sudah ada di level Hotel, cocok untuk “Lokasi/Dermaga”.
- Booking sudah terikat ke Room, cocok untuk booking perahu.

Implikasi:
- Banyak label “Hotel” berubah menjadi “Lokasi”.
- Banyak label “Room” berubah menjadi “Perahu”.
- Fitur “Featured Hotel” menjadi “Featured Lokasi”.


### Opsi B (Alternatif)
- **Hotel** → **Perahu**
- **Room** → **Paket/Tipe/Slot Sewa**

Alasan:
- Jika perahu dianggap sebagai entitas utama tingkat atas.

Implikasi:
- Relasi & UI “Room” menjadi “Paket/Slot” agak dipaksakan.
- Lebih banyak perubahan struktur data mungkin dibutuhkan.

---

## Pemetaan Istilah (Opsi A)

### Frontend / UX
- Hotels → Lokasi
- Rooms → Perahu
- Hotel Details → Detail Lokasi
- Room Details → Detail Perahu
- Hotel Search → Pencarian Lokasi
- Room Search → Pencarian Perahu
- Hotel Wishlist → Wishlist Lokasi (atau disederhanakan menjadi Wishlist Perahu saja)
- Room Wishlist → Wishlist Perahu
- Room Booking → Booking Perahu
- Room Reviews → Review Perahu

### Admin / Vendor
- Hotel Management → Manajemen Lokasi
- Room Management → Manajemen Perahu
- Hotel Categories → Kategori Lokasi
- Room Categories → Kategori Perahu
- Featured Hotel → Promosi Lokasi
- Featured Room → Promosi Perahu
- Holiday Setup (Hotel) → Jadwal Libur Lokasi (tidak ada pelayanan)

### Data & Model (Nama Saat Ini → Makna Baru)
- `hotels` → lokasi
- `hotel_contents` → konten lokasi
- `hotel_images` → galeri lokasi
- `hotel_features` → fitur/promosi lokasi
- `hotel_categories` → kategori lokasi
- `hotel_wishlists` → wishlist lokasi (opsional)
- `rooms` → perahu
- `room_contents` → konten perahu
- `room_images` → galeri perahu
- `room_features` → fitur perahu
- `room_categories` → kategori perahu
- `room_reviews` → review perahu
- `room_wishlists` → wishlist perahu
- `bookings` → booking perahu
- `hourly_room_prices` → harga per jam perahu

---

## Pemetaan Istilah (Opsi B)

### Frontend / UX
- Hotels → Perahu
- Rooms → Paket / Tipe / Slot Sewa

### Data & Model (Nama Saat Ini → Makna Baru)
- `hotels` → perahu
- `rooms` → paket/tipe/slot sewa

Implikasi:
- Struktur room sebagai “slot” membutuhkan custom logic tambahan.

---

## Rekomendasi Sementara
Pilih **Opsi A** karena paling selaras dengan struktur data saat ini dan meminimalkan risiko refactor.

---

## Langkah Lanjutan (menunggu konfirmasi)
1. Konfirmasi pilihan mapping (Opsi A vs B) + istilah final (Lokasi).
2. Buat daftar perubahan label/UI (tahap-1).
3. Rancang perubahan data/logic (tahap-2) jika diperlukan.

---

## Mapping Atribut Perahu (Tanpa Ubah DB) - Tahap 1
Tujuan: memetakan input wajib ke field yang sudah ada.

### Form Perahu (Room)
- **Nama KM / Nama Perahu** → `room_contents.title`
- **Nama Kapten** → `room_contents.description` (diisi dalam bagian “Detail Perahu”)
- **Mesin 1 & Mesin 2** → `room_contents.description` (ditulis sebagai teks)
- **Kapasitas Orang** → `rooms.adult`
- **Kapasitas Tambahan (opsional)** → `rooms.children`
- **Jumlah Mesin** → `rooms.bed` (sementara, numeric)
- **Jumlah Kru** → `rooms.bathroom` (sementara, numeric)
- **Jumlah Perahu (tipe sama)** → `rooms.number_of_rooms_of_this_same_type`
- **Area/Rute Perairan** → `rooms.area`
- **Fasilitas** → `room_contents.amenities[]`
- **Detail Alur Sewa (jam kumpul/kembali, area, deposit, pembatalan)** → `room_contents.description`
- **Harga Sewa per Hari** → gunakan harga “Rent for X Hrs” sebagai paket durasi (sementara, tetap berbasis jam)
- **Lokasi/Dermaga** → `rooms.hotel_id` (pilih dari Hotel/Lokasi)

Catatan:
- Ini hanya mapping UI/label agar data bisa diinput tanpa perubahan skema.
- Detail tekstual (Kapten, Mesin, Alur Sewa) dikumpulkan dalam deskripsi.
