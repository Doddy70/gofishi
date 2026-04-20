# Responsive Design

Master modern responsive design techniques to create interfaces that adapt seamlessly across all screen sizes and device contexts.

## When to Use This Skill

- Implementing mobile-first responsive layouts
- Using container queries for component-based responsiveness
- Creating fluid typography and spacing scales
- Building complex layouts with CSS Grid and Flexbox
- Designing breakpoint strategies for design systems
- Implementing responsive images and media
- Creating adaptive navigation patterns

## Core Concepts

### 1. Container Queries
Component-level responsiveness independent of viewport. Allow components to adapt based on the size of their parent container.
- `container-type: inline-size;`
- `@container (min-width: 400px) { ... }`

### 2. Fluid Typography & Spacing
Using CSS `clamp()` to scale values smoothly between a minimum and maximum based on viewport width.
- `font-size: clamp(1rem, 2vw + 1rem, 2.5rem);`

### 3. Layout Patterns
- **Flexbox**: Ideal for 1D distribution (rows or columns).
- **CSS Grid**: Best for 2D layouts and complex structures. Use `grid-template-areas` for readable responsive transformations.

## Breakpoint Strategy (Mobile-First)

| Prefix | Breakpoint | Device Context |
|--------|------------|----------------|
| (base) | < 640px    | Portrait Mobile |
| `sm`   | 640px      | Landscape Mobile / Small Tablets |
| `md`   | 768px      | Tablets |
| `lg`   | 1024px     | Laptops / Small Desktops |
| `xl`   | 1280px     | Desktops |
| `2xl`  | 1536px     | Large Desktops |

## Key Implementation Patterns

### Fluid Type Scale
```css
:root {
  --text-base: clamp(1rem, 0.9rem + 0.5vw, 1.125rem);
  --text-xl: clamp(1.25rem, 1rem + 1.25vw, 1.5rem);
  --text-3xl: clamp(1.875rem, 1.5rem + 1.875vw, 2.5rem);
}
```

### Auto-Fit Grid (Intrinsic Layout)
```css
.grid-auto {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr));
  gap: 1.5rem;
}
```

### Responsive Navigation (Mobile Toggle)
- Base: Hidden navigation links behind a menu button.
- Desktop (`lg`+): Horizontal visible links.
- Use `100dvh` for mobile menu heights to account for browser UI.

## Best Practices Checklist
- [ ] Is the design **Mobile-First**?
- [ ] Are breakpoints based on **content clusters** rather than specific device models?
- [ ] Are **touch targets** at least 44x44px for easy tapping on mobile?
- [ ] Are **responsive images** implemented with `srcset` or `<picture>`?
- [ ] Are **viewport units** (`dvh`, `svh`) used for mobile-friendly full-height sections?
- [ ] Is **horizontal overflow** eliminated on all tested widths?
- [ ] Are **logical properties** (`padding-inline`, `margin-block`) used for internationalization?
- [ ] Does the UI degrade gracefully when **JavaScript** is disabled (where possible)?
