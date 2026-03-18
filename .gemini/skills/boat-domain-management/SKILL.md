---
name: boat-domain-management
description: Domain expertise and workflows for the Boat Rental Marketplace. Use when refactoring "Hotel/Room" terminology to "Lokasi/Perahu", implementing boat-specific features, or updating the admin/vendor management UI for the boat domain.
---

# Boat Domain Management

This skill ensures consistent application of the "Boat Marketplace" domain logic, particularly when refactoring from the legacy "Hotel/Room" booking system.

## Domain Mapping

The official mapping between technical (legacy) terms and domain terms is defined in [references/mapping.md](references/mapping.md).

### Core Rule: Preserve Database, Transform Presentation
Database table names (`hotels`, `rooms`) and column names (`hotel_id`, `bed`, `bathroom`) should generally remain unchanged to maintain system integrity. All transformations should happen at the **Route**, **Model Attribute (Alias)**, and **View** layers.

## Standard Workflows

### 1. Refactoring Routes
When updating routes, ensure both the URL prefix and the route name are synchronized with the domain.
- **Legacy URL**: `/room/{slug}/{id}`
- **Standard URL**: `/perahu/{slug}/{id}`
- **Route Name**: `frontend.perahu.details`

### 2. Standardizing UI Labels
Always use the Indonesian terms provided in `mapping.md` for user-facing content.
- Use `{{ __('Perahu') }}` or hardcoded `Perahu` depending on the context.
- Pricing should be `/ hari` (per day), not `/ malam` (per night).

### 3. Model Aliasing
When working with `Room` or `Hotel` models, implement Laravel Accessors to provide domain-friendly names.
```php
public function getBoatNameAttribute() {
    return $this->title; // from room_contents
}
```

## UI & Design Standards
- **Primary Buttons**: Use the Airbnb-style gradient: `linear-gradient(to right, #E61E4D, #E31C5F, #D70466)`.
- **Spacing**: Maintain consistent padding (e.g., `mb-4`, `py-6`) as found in the Airbnb-style components at the end of `style.css`.
