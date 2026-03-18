# Technical Design Document: Host Onboarding Document Verification

## 1. Data Model Changes
### Table: `vendors`
- **Modify Column**: `document_verified`
  - Type: `TINYINT` (formerly `BOOLEAN`)
  - Default: `0`
  - Mapping:
    - `0`: Pending
    - `1`: Verified
    - `2`: Rejected
- **Add Column**: `rejection_reason`
  - Type: `TEXT`
  - Nullable: `TRUE`

## 2. API / Method Contracts
### Controller: `Admin\VendorManagementController`
- **Method**: `updateDocumentStatus(Request $request)`
  - **Inputs**:
    - `id`: Vendor ID
    - `document_verified`: Integer (0, 1, 2)
    - `rejection_reason`: String (required if status is 2)
  - **Logic**:
    - Find vendor.
    - Update `document_verified` and `rejection_reason`.
    - If status == 1, trigger `MegaMailer` with `document_verification_approved`.
    - If status == 2, trigger `MegaMailer` with `document_verification_rejected`.
  - **Output**: Redirect back with success message.

## 3. Email Templates
New entries in `mail_templates` table:
### Template 1: `document_verification_approved`
- **Subject**: Dokumen Diverifikasi
- **Body**:
  ```html
  <p>Halo {username},</p>
  <p>Selamat! Dokumen Anda telah berhasil diverifikasi. Anda sekarang memiliki akses penuh untuk mendaftarkan perahu Anda di marketplace kami.</p>
  <p>Silakan <a href="{login_link}">login</a> ke dashboard Anda untuk mulai berjualan.</p>
  ```
### Template 2: `document_verification_rejected`
- **Subject**: Dokumen Ditolak
- **Body**:
  ```html
  <p>Halo {username},</p>
  <p>Mohon maaf, dokumen verifikasi Anda telah ditolak.</p>
  <p><strong>Alasan Penolakan:</strong> {rejection_reason}</p>
  <p>Silakan unggah kembali dokumen yang valid melalui dashboard Anda untuk melanjutkan proses onboarding.</p>
  ```

## 4. Middleware Logic
### Class: `App\Http\Middleware\DocumentVerified`
- **Purpose**: Restrict access to boat management for unverified vendors.
- **Logic**:
  ```php
  if (Auth::guard('vendor')->check() && Auth::guard('vendor')->user()->document_verified != 1) {
      return redirect()->route('vendor.dashboard')->with('warning', 'Dokumen Anda harus diverifikasi sebelum dapat mengakses fitur ini.');
  }
  return $next($request);
  ```
- **Registration**: Registered as `document.verified` in `app/Http/Kernel.php`.

## 5. Integration Points
- **MegaMailer Helper**: Used to handle template parsing and SMTP delivery logic.
- **Blade Templates**:
  - `admin.vendor_management.vendor_details`: Add rejection reason input field and dropdown for status.
  - `vendor.dashboard`: Display status alert (Pending/Rejected with reason).
  - `routes/vendor.php`: Apply `document.verified` middleware to boat/hotel listing routes.
