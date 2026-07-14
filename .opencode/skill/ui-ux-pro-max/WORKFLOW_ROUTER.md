# UI/UX Pro Max - Workflow Router

## Dominios Disponibles

| Domain | Uso | Trigger Keywords | Scripts |
|--------|-----|------------------|---------|
| `product` | Product type recommendations | SaaS, e-commerce, portfolio, healthcare, beauty, service | search.py --domain product |
| `style` | UI styles, colors, effects | glassmorphism, minimalism, dark mode, brutalism | search.py --domain style |
| `typography` | Font pairings, Google Fonts | elegant, playful, professional, modern, clean | search.py --domain typography |
| `color` | Color palettes by product type | saas, ecommerce, healthcare, beauty, fintech, service | search.py --domain color |
| `landing` | Page structure, CTA strategies | hero, hero-centric, testimonial, pricing, social-proof | search.py --domain landing |
| `chart` | Chart recommendations | trend, comparison, timeline, funnel, pie | search.py --domain chart |
| `ux` | UX guidelines | animation, accessibility, z-index, loading | search.py --domain ux |
| `stack` | Stack-specific guidelines | layout, responsive, a11y, performance | search.py --stack <stack> |

**Total**: 8 dominios

## Routing Logic

### Paso 1: Analizar User Requirements

Extraer info clave del request:
```python
user_requirements = {
    "product_type": detect_product_type(),      # SaaS, e-commerce, portfolio, dashboard, landing page, etc.
    "style_keywords": detect_style_keywords(),   # minimal, playful, professional, elegant, dark mode, etc.
    "industry": detect_industry(),             # healthcare, fintech, gaming, education, etc.
    "stack": detect_stack(),                   # react, nextjs, vue, or default a `html-tailwind`
}
```

**Auto-detection rules**:
```python
# product_type
if task.contains("SaaS") or task.contains("dashboard"):
    product_type = "SaaS"

if task.contains("e-commerce") or task.contains("store") or task.contains("shop"):
    product_type = "e-commerce"

if task.contains("portfolio") or task.contains("showcase") or task.contains("personal"):
    product_type = "portfolio"

# style_keywords
if task.contains("minimal") or task.contains("clean") or task.contains("simple"):
    style_keywords = ["minimal"]

if task.contains("glassmorphism") or task.contains("glass") or task.contains("frosted"):
    style_keywords = ["glassmorphism", "glass"]

if task.contains("dark mode") or task.contains("dark"):
    style_keywords = ["dark mode"]

if task.contains("professional") or task.contains("enterprise") or task.contains("corporate"):
    style_keywords = ["professional", "elegant"]

# industry
if task.contains("healthcare") or task.contains("medical") or task.contains("wellness"):
    industry = "healthcare"

if task.contains("fintech") or task.contains("finance") or task.contains("banking"):
    industry = "fintech"

if task.contains("gaming") or task.contains("game"):
    industry = "gaming"

if task.contains("education") or task.contains("learning") or task.contains("university"):
    industry = "education"

# stack
if task.contains("react") and task.contains("nextjs"):
    stack = "nextjs"
elif task.contains("react"):
    stack = "react"
elif task.contains("vue") and task.contains("nuxtjs"):
    stack = "nuxtjs"
elif task.contains("svelte") or task.contains("sveltekit"):
    stack = "svelte"
elif task.contains("swiftui"):
    stack = "swiftui"
elif task.contains("react-native"):
    stack = "react-native"
elif task.contains("flutter"):
    stack = "flutter"
else:
    stack = "html-tailwind"  # default
```

### Paso 2: Determinar Dominios Activos

**Auto-detection**:
```python
if user_requirements["product_type"]:
    → Activar dominio "product"

if user_requirements["style_keywords"]:
    → Activar dominio "style"

if task menciona "landing page":
    → Activar dominio "landing"

if task menciona "dashboard" or "analytics":
    → Activar dominio "chart"

if task menciona "UX guidelines" or "accessibility":
    → Activar dominio "ux"

if task menciona "typography" or "fonts":
    → Activar dominio "typography"

if task menciona "color palette" or "colors":
    → Activar dominio "color"
```

**Prioridad de dominios**:
```
1. Si es landing page: landing → product → style → typography → color → chart → ux → stack
2. Si es dashboard: chart → product → style → typography → color → ux → stack
3. Si es feature: product → style → typography → color → ux → stack
4. Si es diseño UI: style → typography → color → ux → stack
```

### Paso 3: Ejecutar Búsquedas en Orden Recomendado

**Orden por defecto**:
1. **product** - Style recommendations para product type
2. **style** - Detailed style guide (colors, effects, frameworks)
3. **typography** - Font pairings con Google Fonts imports
4. **color** - Color palette (Primary, Secondary, CTA, Background, Text, Border)
5. **landing** - Page structure (si landing page)
6. **chart** - Chart recommendations (si dashboard/analytics)
7. **ux** - Best practices y anti-patterns
8. **stack** - Stack-specific guidelines (default: html-tailwind)

**Script**:
```bash
# 1. Product type
python3 .opencode/skill/ui-ux-pro-max/scripts/search.py "$PRODUCT_TYPE" --domain product

# 2. Style
python3 .opencode/skill/ui-ux-pro-max/scripts/search.py "$STYLE_KEYWORDS" --domain style

# 3. Typography
python3 .opencode/skill/ui-ux-pro-max/scripts/search.py "$TYPOGRAPHY_STYLE" --domain typography

# 4. Color
python3 .opencode/skill/ui-ux-pro-max/scripts/search.py "$INDUSTRY" --domain color

# 5+6. Landing/Chart (si aplica)
if is_landing_page or is_dashboard:
    python3 .opencode/skill/ui-ux-pro-max/scripts/search.py "$PAGE_TYPE" --domain landing
    python3 .opencode/skill/ui-ux-pro-max/scripts/search.py "$CHART_TYPE" --domain chart

# 7. UX
python3 .opencode/skill/ui-ux-pro-max/scripts/search.py "animation" --domain ux
python3 .opencode/skill/ui-ux-pro-max/scripts/search.py "accessibility" --domain ux

# 8. Stack (default: html-tailwind)
python3 .opencode/skill/ui-ux-pro-max/scripts/search.py "layout responsive" --stack html-tailwind
```

### Paso 4: Sintetizar Resultados

**Output esperado**:
```markdown
## UI/UX Design Recommendations

### Product Type: SaaS Dashboard
- Recommended style: Minimalist + Glassmorphism
- Color palette: Primary: #6366F1, Background: #F9FAFB, Text: #111827
- Font pairing: Inter (headings) + IBM Plex Sans (body)
- Layout: Grid-based dashboard with sidebar navigation

### Style: Minimal Professional
- Effects: Subtle shadows, clean borders, rounded corners (8px)
- Spacing: Consistent 8px grid
- Contrast: Dark text on light backgrounds (4.5:1+)

### Typography: Modern Clean
- Google Fonts: Inter (400, 500, 600, 700)
- Hierarchy: H1: 32px, H2: 24px, H3: 20px, Body: 16px
- Line height: 1.5 for body, 1.2 for headings

### Color Palette: Fintech SaaS
- Primary: #2563EB (blue)
- Secondary: #64748B (purple)
- Background: #FFFFFF / #0F172A (light/dark)
- Text: #1E293B / #F8FAFC (light/dark)
- Border: #E2E8F0 / #1E293B (light/dark)

### Stack: html-tailwind
- Container: `max-w-7xl mx-auto px-4`
- Responsive: `grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3`
- Spacing: `gap-4`, `p-4`, `m-2`
- Accessibility: `focus-visible:outline-none` + `aria-label`

### UX Guidelines Applied
- [ ] Hover states on all interactive elements (150-300ms transitions)
- [ ] Cursor pointer on clickable elements
- [ ] Focus visible on keyboard navigation
- [ ] Alt text on all images
- [ ] Color contrast 4.5:1 minimum
```

## Available Stacks

| Stack | Focus |
|-------|-------|
| `html-tailwind` | Tailwind utilities, responsive, a11y (DEFAULT) |
| `react` | State, hooks, performance, patterns |
| `nextjs` | SSR, routing, images, API routes |
| `vue` | Composition API, Pinia, Vue Router |
| `svelte` | Runes, stores, SvelteKit |
| `swiftui` | Views, State, Navigation, Animation |
| `react-native` | Components, Navigation, Lists |
| `flutter` | Widgets, State, Layout, Theming |
| `shadcn` | shadcn/ui components, theming, forms, patterns |

## Quick Reference (Domains)

### product (Product Type)
- **Uso**: Recommendations para tipos de producto
- **Keywords**: SaaS, e-commerce, portfolio, healthcare, beauty, service
- **Output**: Style recommendations, color palette suggestions

### style (UI Style)
- **Uso**: Estilos de interfaz
- **Keywords**: glassmorphism, minimalism, brutalism, neumorphism, claymorphism, bento grid, dark mode
- **Output**: Color schemes, effects, framework, components

### typography (Fonts)
- **Uso**: Pairings tipográficas
- **Keywords**: elegant, playful, professional, modern, clean, bold
- **Output**: Google Fonts imports, pairing recommendations

### color (Color Palettes)
- **Uso**: Paletas específicas por industria
- **Keywords**: saas, ecommerce, healthcare, beauty, fintech, service
- **Output**: Primary, Secondary, CTA, Background, Text, Border colors

### landing (Page Structure)
- **Uso**: Estructura de página
- **Keywords**: hero, hero-centric, testimonial, pricing, social-proof
- **Output**: CTA strategies, layout patterns

### chart (Chart Types)
- **Uso**: Recomendaciones para dashboards y analytics
- **Keywords**: trend, comparison, timeline, funnel, pie
- **Output**: Chart types, library recommendations

### ux (UX Guidelines)
- **Uso**: Best practices y anti-patterns
- **Keywords**: animation, accessibility, z-index, loading
- **Output**: Guidelines, anti-patterns, common issues

### stack (Stack Guidelines)
- **Uso**: Implementación específica por stack
- **Keywords**: layout, responsive, a11y, performance
- **Output**: Best practices, patterns, limitations

## Pre-Delivery Checklist

Before delivering UI code, verify these items:

### Visual Quality
- [ ] No emojis used as icons (use SVG instead)
- [ ] All icons from consistent icon set (Heroicons/Lucide)
- [ ] Brand logos are correct (verified from Simple Icons)
- [ ] Hover states don't cause layout shift

### Interaction
- [ ] All clickable elements have `cursor-pointer`
- [ ] Hover states provide clear visual feedback
- [ ] Transitions are smooth (150-300ms)
- [ ] Focus states visible for keyboard navigation

### Light/Dark Mode
- [ ] Light mode text has sufficient contrast (4.5:1 minimum)
- [ ] Glass/transparent elements visible in light mode
- [ ] Borders visible in both modes
- [ ] Test both modes before delivery

### Layout
- [ ] Floating elements have proper spacing from edges
- [ ] No content hidden behind fixed navbars
- [ ] Consistent max-width (max-w-6xl or max-w-7xl)
- [ ] Responsive at 320px, 768px, 1024px, 1440px
- [ ] No horizontal scroll on mobile

### Accessibility
- [ ] All images have alt text
- [ ] Form inputs have labels
- [ ] Color is not the only indicator
- [ ] `prefers-reduced-motion` respected

## Usage Example

**User request**: "Create a SaaS dashboard for healthcare"

**Routing**:
1. Detect: product_type = "SaaS", industry = "healthcare"
2. Dominios activos: product → style → typography → color → ux → stack
3. Búsquedas:
   - search.py "SaaS dashboard" --domain product
   - search.py "clean professional" --domain style
   - search.py "modern clean" --domain typography
   - search.py "healthcare" --domain color
   - search.py "layout responsive" --stack html-tailwind
4. Sintetizar: Recomendaciones completas
5. Pre-delivery checklist

## Tips for Better Results

1. **Be specific with keywords** - "healthcare SaaS dashboard" > "app"
2. **Search multiple times** - Different keywords reveal different insights
3. **Combine domains** - Style + Typography + Color = Complete design system
4. **Always check UX** - Search "animation", "z-index", "accessibility" for common issues
5. **Use stack flag** - Get implementation-specific best practices
6. **Iterate** - If first search doesn't match, try different keywords
7. **Split Into Multiple Files** - For better maintainability:
    - Separate components into individual files (e.g., `Header.tsx`, `Footer.tsx`)
    - Extract reusable styles into dedicated files
    - Keep each file focused and under 200-300 lines

## Common Rules for Professional UI

### Icons & Visual Elements
| Rule | Do | Don't |
|------|----|-------|
| **No emoji icons** | Use SVG icons (Heroicons, Lucide, Simple Icons) | Use emojis like 🎨 🚀 ⚙️ as UI icons |
| **Stable hover states** | Use color/opacity transitions on hover | Use scale transforms that shift layout |
| **Correct brand logos** | Research official SVG from Simple Icons | Guess or use incorrect logo paths |
| **Consistent icon sizing** | Use fixed viewBox (24x24) with w-6 h-6 | Mix different icon sizes randomly |

### Interaction & Cursor
| Rule | Do | Don't |
|------|----|-------|
| **Cursor pointer** | Add `cursor-pointer` to all clickable/hoverable cards | Leave default cursor on interactive elements |
| **Hover feedback** | Provide visual feedback (color, shadow, border) | No indication element is interactive |
| **Smooth transitions** | Use `transition-colors duration-200` | Instant state changes or too slow (>500ms) |

### Light/Dark Mode Contrast
| Rule | Do | Don't |
|------|----|-------|
| **Glass card light mode** | Use `bg-white/80` or higher opacity | Use `bg-white/10` (too transparent) |
| **Text contrast light** | Use `#0F172A` (slate-900) for text | Use `#94A3B8` (slate-400) for body text |
| **Muted text light** | Use `#475569` (slate-600) minimum | Use gray-400 or lighter |
| **Border visibility** | Use `border-gray-200` in light mode | Use `border-white/10` (invisible) |

### Layout & Spacing
| Rule | Do | Don't |
|------|----|-------|
| **Floating navbar** | Add `top-4 left-4 right-4` spacing | Stick navbar to `top-0 left-0 right-0` |
| **Content padding** | Account for fixed navbar height | Let content hide behind fixed elements |
| **Consistent max-width** | Use same `max-w-6xl` or `max-w-7xl` | Mix different container widths |
