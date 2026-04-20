# Tailwind Design System

A comprehensive guide for building scalable, production-ready design systems using Tailwind CSS, focused on tokens, component architecture, and accessibility.

## When to Use This Skill

- Building a reusable component library
- Implementing design tokens and semantic theming
- Setting up dark mode and multi-theme support
- Creating type-safe component variants (CVA)
- Refactoring ad-hoc utility classes into a structured system
- Ensuring accessibility (A11y) standards in Tailwind UI

## Core Concepts

### 1. Design Token Pipeline
Abstract brand colors into semantic purposes to enable theming.
- **Brand**: `blue-600` → **Semantic**: `primary` → **Component**: `button-bg`

### 2. Semantic CSS Variables
Use CSS variables in `globals.css` and map them in `tailwind.config.ts` for runtime theming (e.g., Dark Mode).

## Implementation Patterns

### 1. Type-Safe Variants (CVA)
Use **Class Variance Authority** to manage multi-dimensional component states (size, variant, color).
```typescript
const buttonVariants = cva('base-classes', {
  variants: {
    variant: {
      default: 'bg-primary text-white',
      outline: 'border border-input hover:bg-accent',
    },
    size: {
      sm: 'h-9 px-3',
      lg: 'h-11 px-8',
    },
  },
  defaultVariants: {
    variant: 'default',
    size: 'default',
  },
})
```

### 2. Utility-First Composition (cn)
Use a helper like `clsx` + `tailwind-merge` to combine classes without conflicts.
```typescript
export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs));
}
```

### 3. Accessible Forms
Always associate `Label` with `Input` using IDs and utilize `aria-invalid` for error states. Ensure focus rings are visible.

## Configuration (tailwind.config.ts)
- Extend `colors` with semantic keys (`background`, `foreground`, `primary`, `muted`).
- Configure `darkMode: "class"` for explicit theme control.
- Use `plugins` like `tailwindcss-animate` for consistent transitions.

## Best Practices Checklist
- [ ] Are **semantic tokens** used instead of hardcoded colors (e.g., `primary` vs `blue-500`)?
- [ ] Is **Dark Mode** implemented via CSS variables?
- [ ] Do components support **forwarding refs** for proper composition?
- [ ] Are **focus states** (`focus-visible`) clear and accessible?
- [ ] Is `tailwind-merge` used to prevent class conflicts?
- [ ] Are common patterns (Cards, Buttons, Inputs) extracted into **Components**?
- [ ] Is the design system **Mobile-First**?
