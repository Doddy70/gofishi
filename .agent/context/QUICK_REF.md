---
title: "Quick Reference — For Next Agent"
priority: "READ FIRST"
captured_at: "2026-03-11T21:39:22+07:00"
---

# ⚡ Quick Reference Card

> **Read this first** before doing anything in this project.

## Project in 1 Sentence
Laravel-based boat rental marketplace (`Marketplace Sewa Perahu`) with a pixel-perfect Airbnb UI clone.

## Dev Server
```bash
cd /Users/doddykapisha/Documents/GitHub/Airbnb/project
php artisan serve  # → http://127.0.0.1:8000
```

## Test URLs
| Page | URL |
|---|---|
| Homepage | http://127.0.0.1:8000 |
| List + Map | http://127.0.0.1:8000/lokasi-dermaga |
| Single Product | http://127.0.0.1:8000/room/km-prana/2 |
| All Boats | http://127.0.0.1:8000/perahu |

## The 3 Most Important Files Right Now

1. **`resources/views/frontend/room/room-details.blade.php`** — Complete Airbnb single product page (just rewritten Session 2)
2. **`resources/views/frontend/hotel/hotel-map.blade.php`** — Split-view map+list page (split bug just fixed)
3. **`public/assets/front/css/style.css`** — ALL CSS for the frontend (append to end)

## 🚨 Critical Gotchas

1. **Split view**: `map-panel` MUST be inside `split-view-container` flex parent. Any stray `</div>` will break it.
2. **categoryName**: Use `$roomContent->categoryName` NOT `$roomContent->hotel->categoryName`
3. **CSS**: Always append to end of `style.css`. Never edit existing rule positions without checking for overrides.
4. **HostController**: Does not exist. `php artisan route:list` will fail because of this. Known bug, pending fix.
5. **Maps**: Leaflet.js is primary. Google Maps is secondary (opt-in via admin panel).

## Next Priority Tasks (in order)

1. 🔴 **CRITICAL** Fix `HostController` missing class in routes → `routes/web.php`
2. 🟡 Hotel Details page (`hotel-details.blade.php`) — needs Airbnb redesign treatment  
3. 🟡 Search filters on lokasi-dermaga — pill filters + AJAX
4. 🟡 Verify fancybox gallery works on room-details page

## Context Files Location
```
project/.agent/context/
├── MASTER_CONTEXT.md      ← Full project context (read this for details)
├── TASK_LOG.md            ← All tasks, completed + pending
├── DESIGN_DECISIONS.md    ← Why things were built the way they are
├── project-context.json   ← Machine-readable context snapshot
└── QUICK_REF.md           ← This file
```
