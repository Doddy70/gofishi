---
title: "Design Decisions & Rationale Log"
project: "Airbnb Clone — Marketplace Sewa Perahu"
version: "2.0.0"
captured_at: "2026-03-11T21:39:22+07:00"
---

# Design Decisions Log

## DDR-001: Single Monolithic CSS File
**Decision**: All styles live in `public/assets/front/css/style.css` (~6300+ lines)  
**Rationale**: Existing project structure; refactoring to SCSS/modules would require build toolchain changes  
**Trade-off**: Difficult to maintain long-term; recommend future migration to SCSS with BEM  
**Impact**: Always append new CSS at end of file to avoid override conflicts

---

## DDR-002: Airbnb Color Palette Adoption
**Decision**: Use Airbnb's exact colors (#E31C5F, #222222, #717171, #dddddd)  
**Rationale**: User requirement to replicate Airbnb UI exactly  
**Implementation**: CSS custom properties (`--color-airbnb-coral: #E31C5F`) + inline Tailwind-style values  
**Note**: The coral gradient `linear-gradient(to right, #E61E4D, #E31C5F, #D70466)` is used for all primary CTAs

---

## DDR-003: Leaflet.js as Primary Map Provider
**Decision**: Leaflet.js is used for all maps (hotel-map, room-details)  
**Rationale**: Google Maps API is optional (toggled by `$basicInfo->google_map_api_key_status == 1`)  
**Fallback**: When Google Maps is disabled, Leaflet.js handles all map rendering  
**Note**: The `hotel-single-map.js` script handles both Leaflet and Google Maps initialization

---

## DDR-004: Bootstrap 5 + jQuery Compatibility
**Decision**: Keep Bootstrap 5 for grid/components but override heavily with custom CSS  
**Rationale**: Existing codebase uses Bootstrap; removing it would break too many components  
**Key Classes Used**: `d-flex`, `col-lg-*`, `gap-*`, `border-bottom`, `overflow-auto`, `position-sticky`  
**Custom Overrides**: `.booking-card-airbnb` overrides `.booking-card`, `.btn-reserve-main` overrides `.btn.btn-primary`

---

## DDR-005: Split View 55/45 Ratio
**Decision**: 55% for card list, 45% for map  
**Rationale**: Airbnb uses approximately this ratio on desktop at 1440px  
**CSS**: `flex: 0 0 55%; max-width: 55%` and `flex: 0 0 45%; max-width: 45%`  
**Responsive**: Below 992px, map is hidden and accessible via modal ("View Map" button)

---

## DDR-006: Room Detail 8/4 Column Split (67%/33%)
**Decision**: `col-lg-8` details + `col-lg-4` booking widget  
**Rationale**: Airbnb uses this exact ratio for their single product pages  
**Sticky Widget**: `position: sticky; top: 100px` applied to booking widget container  
**Mobile**: `d-none d-lg-block` hides widget on mobile; mobile reserve bar appears instead

---

## DDR-007: No Breadcrumbs on Room Detail Page
**Decision**: Removed `@includeIf('frontend.partials.breadcrumb', ...)` from room-details  
**Rationale**: Airbnb's single product pages have no breadcrumb navigation  
**Impact**: Slightly reduced SEO crawlability but better UX consistency with Airbnb  
**Alternative**: Could add structured data breadcrumbs in JSON-LD without visual display

---

## DDR-008: Read-More Pattern for Description
**Decision**: Max-height collapse (168px) with JavaScript toggle for "Tampilkan selengkapnya"  
**Rationale**: Airbnb collapses long descriptions with a "Show more" button  
**Implementation**: CSS `max-height: 168px; overflow: hidden` toggled via jQuery  
**Animation**: Intentionally not animated (baseline-ui skill: NEVER animate layout properties like height)

---

## DDR-009: Amenities Grid + Modal
**Decision**: Show first 10 amenities in 2-col grid, show all in Bootstrap modal  
**Rationale**: Airbnb shows ~10 amenities then "Show all N amenities" button opening a modal  
**Implementation**: `@if($i < 10)` filter in blade + `btn-show-all-amenities` + `#amenitiesModal`  
**Icon Source**: FontAwesome classes stored in `amenities.icon` field in database

---

## DDR-010: Package-based Pricing (not nightly rate)
**Decision**: Three pricing packages: 1 day, 2 days 1 night, 3 days 2 nights  
**Rationale**: Boat rentals operate differently from Airbnb lodging — sold as packages, not nightly rates  
**Fields**: `price_day_1`, `price_day_2`, `price_day_3` on rooms table  
**UX**: Booking widget shows package selector that updates price display via JS  
**Meet Time**: Each package has optional `meet_time_day_*` that auto-fills the time input

---

## DDR-011: categoryName Accessor Pattern
**Decision**: `$roomContent->categoryName` (direct, from SQL join) not `$roomContent->hotel->categoryName`  
**Rationale**: RoomService joins `room_categories` table and selects `room_categories.name as categoryName`  
**Anti-pattern**: Do NOT use `$roomContent->hotel->categoryName` — the `hotel` relationship is not loaded  
**Reference**: `app/Services/RoomService.php` line 51

---

## DDR-012: Availability Mode Display
**Decision**: Two modes shown with different icons/copy  
- Mode 1 (instant): Green bolt icon + "Pemesanan Instan (Real-time)"  
- Mode 2 (approval): Clock icon + "Approval oleh Host"  
**Field**: `$roomContent->availability_mode` (1=instant, 2=on-request)

---

## DDR-013: Wishlist Implementation
**Decision**: Dedicated add/remove routes with full page reload  
**Rationale**: No AJAX wishlist toggle implemented; standard form GET redirects used  
**Routes**: `addto.wishlist.room` / `remove.wishlist.room` (named Laravel routes)  
**Check**: `checkroomWishList($roomId, $userId)` helper function  
**SVG**: Airbnb heart SVG with conditional fill based on wishlist state

---

*Design decisions log — Session ab910e07 — 2026-03-11T21:39:22+07:00*
