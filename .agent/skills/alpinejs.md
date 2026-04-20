# Alpine.js

A minimal, declarative framework for adding behavior directly to your HTML markup. Perfect for modern, reactive UIs without the overhead of larger frameworks.

## When to Use This Skill

- Adding interactivity to server-rendered templates (Laravel Blade, Django, etc.)
- Managing UI states like dropdowns, modals, and tabs
- Implementing forms with real-time validation or two-way binding
- Creating fast, interactive components without a complex build step
- Sprinkling reactive behavior on existing DOM elements

## Core Directives

### State & Scope
- `x-data`: Defines a chunk of HTML as an Alpine component and provides its initial data.
- `x-init`: Runs code when an element is initialized by Alpine.
- `x-cloak`: Used to hide elements until Alpine finishes loading (prevents flicker).

### Binding & Events
- `x-bind` or `:`: Dynamically binds an attribute to an expression (e.g., `:class="{ 'active': open }"`).
- `x-on` or `@`: Listens for browser events (e.g., `@click="open = true"`). supports modifiers like `.prevent`, `.stop`, `.outside`.
- `x-model`: Two-way data binding for form inputs.

### Display Logic
- `x-show`: Toggles `display: none` based on an expression. Supports transitions.
- `x-if`: Completely adds or removes an element from the DOM (must be used on a `<template>` tag).
- `x-for`: Loops through data to create repetitive DOM elements (must be used on a `<template>` tag).

### Content & Logic
- `x-text` / `x-html`: Updates the inner text or HTML of an element.
- `x-effect`: Re-runs a piece of code whenever one of its dependencies changes.

## Magic Properties

- `$el`: Current element.
- `$refs`: Accesses DOM elements marked with `x-ref`.
- `$dispatch`: Dispatches a custom browser event.
- `$watch`: Watches a piece of data and runs a callback on change.
- `$nextTick`: Waits for Alpine to finish update cycles before running code.
- `$store`: Accesses global state defined via `Alpine.store()`.

## Best Practices

### 1. Maintain x-cloak
Always include `[x-cloak] { display: none !important; }` in your CSS to prevent uninitialized components from flashing on page load.

### 2. Extract Complex Logic
If your `x-data` object or logic exceeds 2-3 lines, extract it into a dedicated `Alpine.data()` component in a separate script tag/file.

```javascript
document.addEventListener('alpine:init', () => {
    Alpine.data('dropdown', () => ({
        open: false,
        toggle() { this.open = !this.open },
        close() { this.open = false }
    }))
})
```

### 3. Use x-transition for Polish
Leverage `x-transition` for smooth UI interactions.
```html
<div x-show="open" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 scale-90"
     x-transition:enter-end="opacity-100 scale-100">
    ...
</div>
```

### 4. Direct Communication
Use `$dispatch` and custom events to let components communicate without complex global state if they are loosely coupled.

## Common Patterns

### Dropdown / Modal
```html
<div x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false">
    <button @click="open = !open">Toggle</button>
    <div x-show="open" x-cloak>
        Dropdown Content
    </div>
</div>
```

### Tab System
```html
<div x-data="{ tab: 'home' }">
    <button :class="{ 'active': tab === 'home' }" @click="tab = 'home'">Home</button>
    <button :class="{ 'active': tab === 'about' }" @click="tab = 'about'">About</button>

    <div x-show="tab === 'home'">Home Content</div>
    <div x-show="tab === 'about'">About Content</div>
</div>
```
