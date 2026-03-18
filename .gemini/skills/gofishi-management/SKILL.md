---
name: gofishi-management
description: "Master management skill for Go Fishi (gofishi.com). Handles database synchronization, modern stack porting, and core feature delivery consistency."
version: 1.0.0
---

# Go Fishi Management Skill

## Capability
Skill ini memberikan pengetahuan mendalam tentang arsitektur project Go Fishi, terutama penanganan database hibrida (SQL Dump + Migration) dan implementasi fitur khusus (WA, 17+, Admin Tier).

## Triggers
- "Lanjutkan project Go Fishi"
- "Setup database Go Fishi"
- "Implementasikan fitur perahu"

## Instructions (Core Workflow)
1. **Database Sync**: Jangan jalankan `migrate:fresh` mentah. Gunakan `emergency_db_restorer.php` untuk memuat tabel dasar, baru jalankan `migrate`.
2. **Admin Auth**: Selalu gunakan pengecekan Role Name (bukan ID) sesuai standar Admin Tiering (Admin 1, 2, 3).
3. **Frontend Modernization**: Project telah beralih ke Vue + Inertia + Tailwind. Jangan membuat view Blade baru kecuali sangat diperlukan. Gunakan komponen Vue di `resources/js/Pages`.
4. **Consistency Guard**: Sebelum implementasi, validasi apakah kolom database sudah ada via Tinker.

## Tools & Scripts
- `php emergency_db_restorer.php`: Memulihkan 100+ tabel dari database.sql.
- `python3 batch_refactor.py`: Memperbaiki namespace/facade secara massal.
- `php artisan db:seed --class=GofishiDemoSeeder`: Mengisi data demo perahu.

## Artifacts Memory
- **WA Notification**: `App\Services\NotificationService`
- **Age Verification**: `App\Http\Requests\Room\BookingProcessRequest`
- **Boat Attributes**: `App\Models\Perahu` (using `rooms` table)
