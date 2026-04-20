# Web Performance Optimization

Comprehensive guide for optimizing web applications for speed, responsiveness, and excellent Core Web Vitals.

## When to Use This Skill

- Optimizing page load times (LCP)
- Improving interactivity and responsiveness (FID/INP)
- Eliminating visual instability (CLS)
- Reducing JavaScript bundle sizes
- Implementing efficient caching and asset delivery
- Auditing site performance with Lighthouse and DevTools
- Optimizing images and media for web

## Core Web Vitals

### 1. LCP (Largest Contentful Paint)
Goal: < 2.5s
- **Optimize Server**: Improve TTFB, use CDN, implement caching.
- **Critical Path**: Preload critical images and fonts.
- **Assets**: Use WebP/AVIF, implement responsive images (`srcset`), and compress.
- **Render Blocking**: Inline critical CSS, defer non-critical JS/CSS.

### 2. FID (First Input Delay) / INP (Interaction to Next Paint)
Goal: FID < 100ms, INP < 200ms
- **JS Execution**: Break up long tasks (>50ms) using `setTimeout` or `requestIdleCallback`.
- **Main Thread**: Use Web Workers for heavy computation.
- **Third-Party**: Audit and defer non-essential third-party scripts.
- **Hydration**: Use partial hydration or streaming SSR if using frameworks.

### 3. CLS (Cumulative Layout Shift)
Goal: < 0.1
- **Dimensions**: Always set `width` and `height` attributes on `<img>` and `<video>` or use `aspect-ratio` in CSS.
- **Dynamic Content**: Reserve space for ads, embeds, and dynamic items using placeholders/skeletons.
- **Fonts**: Use `font-display: swap` and preload critical web fonts to avoid FOIT/FOUT shifts.
- **Animations**: Use `transform` instead of properties that trigger layout (like `top`, `left`, `margin`).

## Optimization Strategies

### JavaScript Optimization
- **Code Splitting**: Use dynamic `import()` to load code only when needed.
- **Tree Shaking**: Ensure build tools remove unused exports.
- **Modern Syntax**: Ship ES6+ to modern browsers to reduce transpilation bloat.
- **Execution**: Implement **virtual scrolling** for long lists to reduce DOM nodes.
- **API Strategy**: Use **batching** and **debouncing** for frequent API calls.

### Image & Media Optimization
- **Modern Formats**: Convert images to WebP/AVIF.
- **Lazy Loading**: Use `loading="lazy"` for below-the-fold content.
- **Responsive Images**: Use `<picture>` or `srcset` to serve appropriately sized images for devices.

### CSS & Rendering Optimization
- **Critical CSS**: Extract and inline CSS needed for above-the-fold content.
- **Unused CSS**: Periodically audit and remove dead styles (PurgeCSS).
- **Avoid Layout Thrashing**: Prevent "forced synchronous layouts" by batching DOM reads and writes.
- **Animations**: Use `requestAnimationFrame` and prefer CSS `transforms` over positioning properties.
- **Containment**: Use `content-visibility: auto` to skip rendering for off-screen sections.

### Caching & Delivery
- **Cache-Control**: Implement long-term hashing for static assets.
- **Service Workers**: Use service workers for offline support and asset caching.
- **Resource Hints**: Implement `preload`, `prefetch`, and `preconnect`.

## Monitoring & Audit
- **Lighthouse**: Run automated audits during development.
- **Chrome DevTools**: Use the **Performance panel** to identify long tasks and layout shifts.
- **RUM (Real User Metrics)**: Monitor actual performance from user devices.

## Best Practices Checklist
- [ ] Are images/videos sized with explicit dimensions to prevent CLS?
- [ ] Is the LCP image preloaded?
- [ ] Are long JavaScript tasks broken up to keep the main thread responsive?
- [ ] Is layout thrashing avoided in animations or DOM updates?
- [ ] Are static assets served via CDN with compression (Brotli/Gzip) enabled?
