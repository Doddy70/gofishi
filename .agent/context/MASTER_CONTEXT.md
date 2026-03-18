---
title: "Airbnb Marketplace Sewa Perahu — Master Context"
version: "2.0.0"
captured_at: "2026-03-11T21:39:22+07:00"
context_type: comprehensive
tags: [airbnb, laravel, ui-redesign, split-view, single-product, search-widget]
project_root: "/Users/doddykapisha/Documents/GitHub/Airbnb/project"
dev_url: "http://127.0.0.1:8000"
---

# 🗺️ Project: Marketplace Sewa Perahu (Airbnb Clone)

A fully-featured Laravel-based boat/vessel rental marketplace with an Airbnb-identical UI design.

---

## 🏗️ Architecture Overview

```
Laravel App (PHP)
├── Frontend (Blade + Vanilla CSS + Bootstrap 5 + jQuery)
│   ├── Homepage (/) — Airbnb-style search widget
│   ├── /lokasi-dermaga — Split-view map + card list (Airbnb search results)
│   ├── /perahu — Grid view of all boats/rooms  
│   ├── /room/{slug}/{id} — Single product detail page (Airbnb-identical)
│   ├── /hotel/{slug}/{id} — Location detail page
│   └── /vendor/{username} — Host/vendor profile
├── Backend (Laravel MVC + Service Layer)
│   ├── Controllers/FrontEnd/ (HotelController, RoomController, etc.)
│   ├── Services/RoomService.php (encapsulated room query logic)
│   └── Models/ (Room, Hotel, RoomContent, Amenitie, etc.)
├── Maps: Leaflet.js (primary) + Google Maps API (optional)
└── CSS: public/assets/front/css/style.css (single monolithic file, ~6300+ lines)
```

---

## 🎨 Design System (Airbnb Tokens)

| Token | Value |
|---|---|
| **Primary** | `#E31C5F` (Airbnb coral red) |
| **Primary Gradient** | `linear-gradient(to right, #E61E4D, #E31C5F, #D70466)` |
| **Dark** | `#222222` |
| **Muted** | `#717171` |
| **Border** | `#dddddd` |
| **BG Light** | `#F7F7F7` |
| **Card Radius** | `12px` |
| **Button Radius** | `8px` |
| **Font** | `'Circular', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto` |
| **Card Shadow** | `0 6px 20px rgba(0,0,0,0.12)` |
| **Z-Index Scale** | header=1000, search=1001, dropdowns=1050+ |

---

## 📁 Key Files Reference

### Views (Blade Templates)
| Page | File Path |
|---|---|
| Home / | `resources/views/frontend/home/index.blade.php` |
| Lokasi Dermaga (Map list) | `resources/views/frontend/hotel/hotel-map.blade.php` |
| Hotel Detail | `resources/views/frontend/hotel/hotel-details.blade.php` |
| **Room Detail (main)** | `resources/views/frontend/room/room-details.blade.php` |
| Room Grid | `resources/views/frontend/room/room-gird.blade.php` |
| Checkout | `resources/views/frontend/room/checkout.blade.php` |
| Layout | `resources/views/frontend/layout.blade.php` |
| Partials | `resources/views/frontend/partials/` |

### Controllers
| Controller | Path |
|---|---|
| HotelController | `app/Http/Controllers/FrontEnd/HotelController.php` |
| RoomController | `app/Http/Controllers/FrontEnd/RoomController.php` |
| HomeController | `app/Http/Controllers/FrontEnd/HomeController.php` |
| VendorController | `app/Http/Controllers/FrontEnd/VendorController.php` |

### Services
| Service | Path | Key Methods |
|---|---|---|
| RoomService | `app/Services/RoomService.php` | `getRoomDetails()`, `getHostInfo()`, `getAvailabilityDates()`, `getRoomReviews()`, `getRelatedRooms()` |

### Assets
| Asset | Path |
|---|---|
| **Main CSS** | `public/assets/front/css/style.css` |
| Map (hotel list) | `public/assets/front/js/map-hotel.js` |
| Map (single) | `public/assets/front/js/hotel-single-map.js` |
| Search JS | `public/assets/front/js/hotel-search.js` |
| API Search | `public/assets/front/js/api-search-2.js` |

---

## ✅ Completed Implementations

### 1. Homepage Search Widget (Airbnb-identical)
- **Location pill** with autocomplete
- **Date pickers** (Tanggal Keberangkatan) with calendar dropdown  
- **Guest counter** (Dewasa + Anak-anak) with inline picker
- **Cari button** in Airbnb coral red with gradient  
- Z-index correctly layered: header → search → dropdowns
- Smooth animated background on scroll

### 2. `/lokasi-dermaga` Split View Page
- **55% left**: Scrollable card list with Airbnb-style property cards
- **45% right**: Sticky Leaflet map panel
- **Root bug fixed**: Extra `</div>` in hotel-map.blade.php was prematurely closing the flex container, causing stacked layout instead of side-by-side

```html
<!-- CORRECT structure in hotel-map.blade.php -->
<div class="split-view-container" style="display:flex; flex-wrap:nowrap;">
  <div class="list-panel" style="flex:0 0 55%; height:calc(100vh - 80px); overflow-auto;">
    <!-- Cards ... -->
    <!-- Map panel is INSIDE split-view-container, not after it -->
    <div class="map-panel" style="flex:0 0 45%; position:sticky; top:100px;">
      <div id="main-map" style="width:100%;height:100%;"></div>
    </div>
  </div>
</div>
```

> ⚠️ **GOTCHA**: The `map-panel` must be a **direct child** of `split-view-container`. Any extra `</div>` before the map-panel will break the flex layout.

### 3. Room Detail Single Product Page (`/room/{slug}/{id}`)
Full Airbnb-style redesign with these sections in order:

1. **`detail-sticky-nav`** — appears on scroll past gallery, has nav links + price + reserve button
2. **Container** (max-width: 1200px)
   - Title row + Share/Save buttons (with SVG icons)
   - **Photo Gallery** — 5-photo Airbnb grid (`grid-main` + 4 smaller)
   - **Two-column layout** (col-lg-8 + col-lg-4)
     - **Left**: Host header → 3 Highlights → Description (collapsible) → Amenities grid → Reviews 2-col → Host section → Location map
     - **Right**: `booking-card-airbnb` sticky widget
3. **Mobile Reserve Bar** (fixed bottom, d-lg-none)
4. **Mobile Booking Modal**

#### Booking Card Fields
- Package selector (Paket 1 Hari / 2H1M / 3H2M) with JS price update
- Date input (Tanggal Keberangkatan)
- Time input (Jam Kumpul, auto-updated from package)
- Adults + Children inputs
- Coral red "Pesan Sekarang" button
- Price breakdown
- "Anda belum akan dikenakan biaya" notice

#### Controller Variables Available in room-details.blade.php
```php
$roomContent->title          // Room name
$roomContent->description    // HTML description
$roomContent->amenities      // JSON encoded amenity IDs
$roomContent->categoryName   // From join (NOT $roomContent->hotel->categoryName!)
$roomContent->latitude       // From hotels table join
$roomContent->longitude      // From hotels table join
$roomContent->address        // From hotel_contents join
$roomContent->max_guest      // Max guests
$roomContent->bed            // Bed count
$roomContent->bathroom       // Bathroom count
$roomContent->area           // Area in m²
$roomContent->price_day_1    // Package 1 day price
$roomContent->price_day_2    // Package 2 day price
$roomContent->price_day_3    // Package 3 day price
$roomContent->meet_time_day_1 // Default meet time for package 1
$roomContent->vendor_id      // 0 = admin, otherwise vendor
$roomContent->availability_mode // 1=instant, 2=on-request
$roomContent->average_rating // Rating
$roomContent->id             // Room ID
$vendor->image / $vendor->photo  // Host photo
$userName                    // Host username string
$roomImages                  // Collection of RoomImage objects
$reviews                     // Collection of RoomReview objects
$numOfReview                 // Integer count
```

---

## 🐛 Resolved Issues

### Issue 1: Split View Stacking (hotel-map.blade.php)
**Symptom**: Cards and map stacked vertically instead of side-by-side  
**Root Cause**: Extra `</div>` at line ~242 closed `split-view-container` before `map-panel`  
**Fix**: Restructured closing tags so `map-panel` stays inside the flex container  
**File**: `resources/views/frontend/hotel/hotel-map.blade.php` lines 240-251

### Issue 2: Z-Index Chaos on Homepage
**Symptom**: Search dropdowns appearing behind other elements  
**Fix**: Established z-index scale — header: 1000, search-container: 1001, dropdowns: 1050+  
**File**: `public/assets/front/css/style.css`

### Issue 3: 500 Error on /lokasi-dermaga
**Symptom**: Server returning 500 Internal Server Error  
**Root Cause**: Undefined `$radius`, `$bs`, `$locationIds` variables + unbalanced braces  
**Fix**: Fixed variable definitions in HotelController, corrected brace balance  
**File**: `app/Http/Controllers/FrontEnd/HotelController.php`

---

## ⚠️ Pending Issues / Gotchas

### 1. HostController Does Not Exist
```
ReflectionException: Class "HostController" does not exist
```
One route in `routes/web.php` references a `HostController` that doesn't exist. Running `php artisan route:list` will fail due to this.  
**Action**: Search `routes/web.php` for `HostController` and remove or implement it.

### 2. Pagination on hotel-map
The `$currentPageData` uses Eloquent paginator (`paginate()`). The view loops `$currentPageData->lastPage()` and `currentPage()` — ensure the controller always returns a paginator, not a plain collection.

### 3. Map Initialization  
For `hotel-map.blade.php`: map initializes via `map-hotel.js` using `featured_contents` and `hotel_contentss` JS variables defined in `@section('script')`.  
For `room-details.blade.php`: map initializes via `hotel-single-map.js` using `latitude` and `longitude` JS variables.

---

## 🔧 CSS Architecture Notes

The entire CSS lives in **one file**: `public/assets/front/css/style.css` (~6300+ lines).

Sections added during this session (appended at end of file):
- `.airbnb-detail-page` — page wrapper
- `.detail-sticky-nav` — sticky navigation bar on single product page
- `.airbnb-listing-title` — h1 styling
- `.airbnb-host-title`, `.airbnb-specs-row` — host header
- `.airbnb-highlights` — 3 icon highlights
- `.amenities-grid` — 2-col amenities layout
- `.reviews-grid` — 2-col reviews layout
- `.booking-card-airbnb` — sticky booking card
- `.booking-inputs-wrapper`, `.booking-input-group`, `.booking-label`, `.booking-input-field`
- `.btn-reserve-main`, `.btn-reserve-nav` — coral red buttons
- `.mobile-reserve-bar` — fixed mobile bottom bar

Previously added sections:
- `.split-view-container`, `.list-panel`, `.map-panel` — lokasi-dermaga split view
- `.airbnb-image-grid` — 5-photo gallery grid
- `.airbnb-card` — property listing card

---

## 🗄️ Database Models Reference

| Model | Table | Key Fields |
|---|---|---|
| Room | rooms | id, hotel_id, status, max_guest, bed, bathroom, area, price_day_1/2/3, meet_time_day_1/2/3, vendor_id, availability_mode, average_rating |
| RoomContent | room_contents | room_id, language_id, title, slug, description, amenities (JSON), room_category |
| RoomImage | room_images | room_id, image |
| RoomReview | room_reviews | room_id, hotel_id, rating, review |
| Hotel | hotels | id, status, latitude, longitude, logo, stars |
| HotelContent | hotel_contents | hotel_id, language_id, title, slug, address, category_id |
| Amenitie | amenities | id, title, icon (FontAwesome class) |
| Vendor | vendors | id, username, photo |
| HourlyRoomPrice | hourly_room_prices | room_id, hour_id, price |

---

## 🔌 Helper Functions (global)

```php
symbolPrice($amount)           // Format currency with symbol (e.g., Rp 1.000.000)
totalHotelRoom($hotelId)       // Count rooms for a hotel
totalHotelReview($hotelId)     // Count reviews for a hotel
totalRoomReview($roomId)       // Count reviews for a room
checkHotelWishList($hotelId, $userId)  // Bool: is hotel in user wishlist?
checkroomWishList($roomId, $userId)    // Bool: is room in user wishlist?
showAd($position)              // Display ad by position number
```

---

## 🚀 Next Recommended Actions

1. **Fix HostController route issue** — find and fix the broken route in `routes/web.php`
2. **Hotel Details Page** (`hotel-details.blade.php`) — apply same Airbnb single-product treatment (currently uses old layout with breadcrumb + circular logo image)
3. **Room Grid Page** (`room-gird.blade.php`) — improve card design consistency with `list-panel` cards on hotel-map
4. **Search Widget Integration** — connect search filters on `/lokasi-dermaga` to filter the card list dynamically via AJAX
5. **Photo Gallery Lightbox** — Verify fancybox/lightbox library is loaded for the gallery to work on room-details
6. **Mobile Responsiveness** — Test all pages at 360px, 768px, 1024px breakpoints
7. **SEO** — Add structured data (schema.org `Product`, `Review`) to room-details page

---

## 🛠️ Development Environment

```bash
# Start dev server
cd /Users/doddykapisha/Documents/GitHub/Airbnb/project
php artisan serve
# Runs on http://127.0.0.1:8000

# Key URLs
http://127.0.0.1:8000                     # Homepage
http://127.0.0.1:8000/lokasi-dermaga      # Split view map
http://127.0.0.1:8000/perahu              # Room grid
http://127.0.0.1:8000/room/km-prana/2    # Single product (test room)
```

---

## 📚 Reference Artifacts

All session screenshots and recordings are stored at:
```
/Users/doddykapisha/.gemini/antigravity/brain/ab910e07-a11f-433e-9d43-d0ae33e0d4ed/
```

Key recordings:
- `verify_split_view_fixed_structure_*.webp` — Split view fix verification
- `lokasi_dermaga_split_view_successful_*.webp` — Split view working state
- `verify_airbnb_search_bar_v3_*.webp` — Search bar redesign

---

*Context captured by Antigravity AI Agent — 2026-03-11T21:39:22+07:00*
