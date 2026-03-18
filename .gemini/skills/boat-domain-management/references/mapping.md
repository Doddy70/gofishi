# Boat Domain Mapping Reference

This document defines the official terminology mapping for the transition from a Hotel/Room Booking system to a Boat Rental Marketplace.

## Core Entity Mapping

| Old Term (Technical) | New Term (Indonesian) | New Term (English) | Context |
| :--- | :--- | :--- | :--- |
| Hotel | Lokasi / Dermaga | Location / Dock | Container for boats |
| Room | Perahu / KM | Boat | Individual rentable vessel |
| Bed | Mesin | Engine | Technical spec for boat |
| Bathroom | Kru | Crew | Technical spec for boat |
| Night | Hari | Day | Pricing unit |

## Route & URL Mapping

| Old URL Pattern | New URL Pattern | Route Name Alias |
| :--- | :--- | :--- |
| `/rooms` | `/perahu` | `frontend.perahu` |
| `/room/{slug}/{id}` | `/perahu/{slug}/{id}` | `frontend.perahu.details` |
| `/admin/hotel-management` | `/admin/lokasi-management` | `admin.lokasi.management` |
| `/admin/rooms-management` | `/admin/perahu-management` | `admin.perahu.management` |

## Attributes & Spec Mapping

| Attribute | Old Label | New Label |
| :--- | :--- | :--- |
| `rooms.adult` | Adults | Kapasitas Orang |
| `rooms.children` | Children | Kapasitas Tambahan |
| `rooms.bed` | Beds | Jumlah Mesin |
| `rooms.bathroom` | Bathrooms | Jumlah Kru |
| `rooms.area` | Area | Area/Rute Perairan |

## UI Gradient (Airbnb Style)
- **Primary Button**: `linear-gradient(to right, #E61E4D, #E31C5F, #D70466)`
- **Class**: `.btn-reserve-main`, `.btn-reserve-nav`
