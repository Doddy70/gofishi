---
title: RoomService - Technical Documentation
component_path: app/Services/RoomService.php
version: 1.2
date_created: 2026-03-16
last_updated: 2026-03-16
owner: Lead Developer
tags: `service`,`business-logic`,`rental`,`search`,`availability`
---

# RoomService Documentation

The `RoomService` is a core business logic component in the **Go Fishi** system. It centralizes the complexity of retrieving boat listings, managing multilingual content, and enforcing real-time availability rules (holidays and existing bookings).

## 1. Component Overview

### Purpose/Responsibility
- **OVR-001**: Acts as the single source of truth for boat-related data operations.
- **OVR-002**: Handles complex SQL joins and filters for boat searching, including geo-text search and date-range availability.
- **OVR-003**: Manages caching for critical settings and provides structured data for frontend consumption.

## 2. Architecture Section

- **ARC-001**: Implements the **Service Pattern** to decouple business logic from the Controller layer.
- **ARC-002**: Uses **Repository-like methods** for Eloquent query building.
- **ARC-003**: Integrates with Laravel's **Cache** facade for performance optimization.

### Component Structure and Dependencies Diagram

```mermaid
graph TD
    subgraph "Service Layer"
        RS[RoomService] --> BL[Business Logic]
    end

    subgraph "Domain Models"
        RS --> P[Perahu / Room]
        RS --> RC[RoomContent]
        RS --> B[Booking]
        RS --> H[Holiday]
    end

    subgraph "Infrastructure"
        RS --> Cache[Laravel Cache]
        RS --> DB[(MySQL Database)]
    end

    subgraph "External Helpers"
        RS --> Carbon[Carbon Date Lib]
    end

    classDiagram
        class RoomService {
            +getSettings(): Basic
            +getAvailableRooms(filters, langId): Paginator
            +getRoomDetails(id, langId): Perahu
            +getHotelHolidays(hotelId): array
            +getRoomReviews(roomId): array
            +getRelatedRooms(roomId, catId, langId): Collection
        }
```

## 3. Interface Documentation

| Method | Purpose | Parameters | Return Type | Usage Notes |
|-----------------|---------|------------|-------------|-------------|
| `getSettings` | Get website settings | None | `Basic` | Uses 1-hour caching |
| `getAvailableRooms` | Main search engine | `array $filters, int $langId` | `LengthAwarePaginator` | Handles date-range & occupancy |
| `getRoomDetails` | Full room data | `int $id, int $langId` | `Perahu` | Eager loads galleries & content |
| `getRoomReviews` | Review listing | `int $roomId` | `array` | Includes user metadata |

## 4. Implementation Details

- **IMP-001**: **Availability Logic**: The service excludes boats if the requested range overlaps with `holidays` (set by dermaga) or `bookings` (existing confirmed transactions).
- **IMP-002**: **Multilingual Support**: Automatically joins `room_contents` and `hotel_contents` based on the provided `$languageId`.
- **IMP-003**: **Geo-Text Search**: Performs partial matches on both boat addresses and pier (hotel) titles.

## 5. Usage Examples

### Basic Usage in Controller

```php
// Injecting and using the service
public function index(Request $request, RoomService $roomService) {
    $rooms = $roomService->getAvailableRooms($request->all(), $langId);
    return view('listing', compact('rooms'));
}
```

### Advanced Availability Check

```php
// The service automatically handles Airbnb-style date strings
$filters = ['checkInDates' => '03/20/2026 - 03/25/2026'];
$available = $roomService->getAvailableRooms($filters, 1);
```

## 6. Quality Attributes

- **QUA-001**: **Security**: Uses Eloquent parameter binding to prevent SQL injection.
- **QUA-002**: **Performance**: Optimized via Eager Loading (`with`) to solve N+1 query problems.
- **QUA-003**: **Reliability**: Fail-safe mechanisms in `getSettings` to prevent 500 errors if DB is missing records.

## 7. Reference Information

- **REF-001**: Depends on `PHP 8.2+` and `Laravel 11+`.
- **REF-002**: Database Tables: `rooms`, `room_contents`, `bookings`, `holidays`.
- **REF-003**: Related Documentation: [Checkout Flow](../TechnicalDesign_DocumentVerification.md).
