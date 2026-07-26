# Restrack — Design System (Premium Glass) / نظام التصميم

The approved direction: **Vibrant Premium executed as luxurious glassmorphism** — youthful, professional,
and فخم (never childish). Reference mockup: `mockups/03-premium-glass.html`. This is the buildable spec for
the real Laravel/Tailwind views. Full product/UX context: `../plan/MASTER_PLAN.md` §14.

## 1. Principles
- **Premium, not playful-childish:** crisp type, generous spacing, **gold used sparingly**; energy comes from color accents + motion + glass depth, not from cartoon/emoji.
- **SVG icons only — never emoji** in product UI. Line icons, 1.6 stroke, rounded caps/joins, `currentColor`, 24×24 grid. A shared sprite (`<symbol>`+`<use>`); tint via `color`.
- **Glass is the signature surface**, but readable: every glass panel keeps text ≥ 4.5:1.
- **Wow but fast:** animate only `transform`, `opacity`, `filter`; GPU-friendly; never animate `backdrop-filter` or layout. Everything behind `prefers-reduced-motion`.
- **Arabic-first RTL**, logical CSS, Western-Arabic numerals, tabular-nums for figures.
- **Light + dark** both first-class (مود نهاري/ليلي), token-driven.

## 2. Color tokens
Brand: navy `#16264b` · gold `#af9136`. Vibrant accents: violet `#7C6CFC` · teal `#10B4A0` · coral `#FF7A59` · bright-gold `#FFB400`. Semantic: success `#12B39B` · warning `#F5A524` · danger `#F0506E`. (All live in `resources/css/app.css` `@theme`.)

**Dark (signature):** canvas `#0b1428`→`#070d1c` aurora · ink `#F3F6FF` · ink-2 `#AEB9D6` · gold-on-dark `#D9B458`.
**Light (day):** canvas `#F4F7FE` · ink `#152142` · gold-on-light darkens to `#9A7C22` (contrast). Glass fill flips to frosted white.

## 3. The glass recipe (liquid-glass)
```css
.glass{
  background:linear-gradient(160deg, var(--g-fill-1), var(--g-fill-2)); /* .10 → .035 white on dark */
  border:1px solid var(--g-border);                                     /* ~rgba(255,255,255,.16) */
  backdrop-filter:blur(22px) saturate(160%);
  box-shadow:0 30px 70px -34px rgba(0,0,0,.8), inset 0 1px 0 var(--g-hi);/* depth + top highlight */
  border-radius:24px; overflow:hidden;
}
.glass::before{ /* specular top-left highlight */ background:linear-gradient(135deg,var(--g-hi) 0%,transparent 26%) }
.glass::after{  /* moving sheen band, sweeps on hover — the "liquid" feel */ }
```
Layers that make it read as real glass: (1) translucent gradient fill, (2) 1px light border, (3) inset top highlight, (4) soft colored outer shadow, (5) hover sheen sweep, (6) aurora orbs blurred **behind** so the glass refracts color. Light theme: raise fill opacity to ~.6–.72 and border to ~.85.

## 4. Aurora background
Deep navy radial gradient + 3 blurred color orbs (violet/teal/gold) drifting slowly (`transform`, 26–38s, `ease-in-out`) + a faint 4% grain. In light mode reduce orb opacity to ~.38.

## 5. Motion system (performant "wow")
- **Entrance:** `.rise` = fade + translateY(26px) + slight scale + blur-in, 0.8s `cubic-bezier(.2,.7,.2,1)`, triggered by IntersectionObserver. `.stagger` children delay 80ms each.
- **Signature moments:** progress-ring draw, number count-up (rAF, ease-out cubic), gold CTA sheen sweep, pointer-parallax tilt on the hero card (rAF-throttled, ±5–6°).
- **Ambient:** orb drift, pulsing live-dot.
- **Budget:** no library; a few KB of vanilla JS; `will-change` only on orbs/ring; kill everything under `prefers-reduced-motion`.

## 6. Typography
Self-host **IBM Plex Sans Arabic** (or Tajawal) in production. Scale: display `clamp(2.6rem,6vw,4.4rem)` / h2 `clamp(1.7rem,3.6vw,2.5rem)` / body 17px/1.7 / label .72rem uppercase +.2em. `text-wrap:balance` on headings. Western-Arabic numerals; `tabular-nums` for stats.

## 7. Components (built in the mockup)
Glass top-bar · aurora hero + glass "mission control" dashboard (ring + SVG stat chips) · SVG icon feature cards · glass course card (SVG play + progress) · protection panel (shield + layered SVG list) · glass certificate card · glass footer. All RTL, both themes, SVG-icon-driven.

## 8. Implementation note (Laravel/Tailwind v4)
Tokens already added to `resources/css/app.css` (@theme accents + runtime light/dark vars + `.gradient-joy`/`.pill`/`.btn-fun`/theme helpers + reduced-motion guard). Next: add a `.glass`/`.glass::before/after` utility layer + the SVG sprite as a Blade partial (`resources/views/partials/icons.blade.php`) + a small `resources/js/motion.js` for the observer/tilt/count-up — then migrate views (start with the home hero). Keep the existing navy/gold utilities; the glass layer is additive.
