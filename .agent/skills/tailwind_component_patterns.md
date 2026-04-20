# Tailwind CSS Component Patterns

Master production-ready UI patterns using Tailwind CSS. This guide focuses on consistent spacing, responsive layouts, and semantic component construction.

## When to Use This Skill

- Building new UI sections or components
- Standardizing spacing and typography across a project
- Implementing responsive layouts (base, sm, md, lg, xl)
- Creating common UI patterns (Cards, Buttons, Forms, Grids)
- Ensuring dark mode compatibility via semantic tokens

## Core Patterns

### 1. The Container Pattern
Standard constraint for page content with responsive padding.
```html
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
  <!-- Content -->
</div>
```

### 2. The Responsive Grid
Adapts columns based on screen size (1 col mobile, 2 col tablet, 3 col desktop).
```html
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
  <!-- Items -->
</div>
```

### 3. Semantic Component Base (Card)
Uses theme tokens for background, text, and borders.
```html
<div class="bg-card text-card-foreground rounded-lg border border-border p-6 shadow-sm">
  <h3 class="text-lg font-semibold mb-2">Card Title</h3>
  <p class="text-muted-foreground">Description goes here.</p>
</div>
```

## Spacing & Typography Scale

### Spacing Guidelines
Use the 4-point scale for rhythm:
- **Tight**: `gap-2` (8px) / `p-2`
- **Standard**: `gap-4` (16px) / `p-4`
- **Loose**: `gap-8` (32px) / `p-8`
- **Section**: `py-16 sm:py-24` (64px/96px)

### Typography Hierarchy
- **Page Title**: `text-4xl sm:text-5xl lg:text-6xl font-bold`
- **Section Title**: `text-3xl sm:text-4xl font-bold`
- **Card Title**: `text-xl font-semibold`
- **Body**: `text-base text-muted-foreground`

## Essential Principles (The Do's & Don'ts)

### ✅ Always Do
1. **Semantic Colors**: Use `bg-primary`, `text-card-foreground` instead of `bg-blue-500`.
2. **Mobile-First**: Define base styles first, then use `md:`, `lg:` for larger screens.
3. **Interactive States**: Always add `hover:*` and `focus:*` with `transition-colors`.
4. **Touch Targets**: Ensure buttons are at least `h-10` or `px-4 py-2` (minimum 44x44px).

### ❌ Never Do
1. **Hardcoded Pixels**: Avoid `text-[16px]`, use `text-base` for accessibility.
2. **Fixed Widths on Mobile**: Avoid `w-[400px]`, use `w-full max-w-md`.
3. **Broken Grids**: Don't forget `min-w-0` on grid items that contain truncated text.
4. **Animating Layout**: Don't animate `width` or `top`; use `transform: scale` or `translate`.

## Common Combinations

- **Sticky Header**: `sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur`
- **Centered Hero**: `flex flex-col items-center justify-center text-center py-20`
- **Responsive Table**: Wrap `<table>` in `div class="w-full overflow-x-auto"`
- **Hover Lift**: `transition-transform hover:-translate-y-1`
