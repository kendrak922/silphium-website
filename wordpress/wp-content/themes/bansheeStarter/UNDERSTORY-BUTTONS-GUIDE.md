# Understory Theme Button System

A simplified SASS formula for generating buttons using any of the understory theme colors.

## Color Palette

The system uses these 8 understory colors:

- **Deep Forest** (`#1B4332`) - Primary dark green
- **Forest Green** (`#52B788`) - Bright accent green  
- **Warm Brown** (`#D4A373`) - Earth tone
- **Golden Amber** (`#E9C46A`) - Highlight color
- **Soft Cream** (`#FDF6EC`) - Light background
- **Charcoal** (`#2E2E2E`) - Dark text
- **Sage Green** (`#A3B18A`) - Muted green
- **Earth Brown** (`#8C6A4A`) - Dark earth tone

## Button Styles

### 1. Solid Buttons
```html
<button class="btn--understory-deep-forest">Deep Forest</button>
<button class="btn--understory-forest-green">Forest Green</button>
<button class="btn--understory-warm-brown">Warm Brown</button>
<button class="btn--understory-golden-amber">Golden Amber</button>
<button class="btn--understory-soft-cream">Soft Cream</button>
<button class="btn--understory-charcoal">Charcoal</button>
<button class="btn--understory-sage-green">Sage Green</button>
<button class="btn--understory-earth-brown">Earth Brown</button>
```

### 2. Outline Buttons
```html
<button class="btn--understory-deep-forest-outline">Deep Forest Outline</button>
<button class="btn--understory-forest-green-outline">Forest Green Outline</button>
<button class="btn--understory-warm-brown-outline">Warm Brown Outline</button>
<button class="btn--understory-golden-amber-outline">Golden Amber Outline</button>
<button class="btn--understory-soft-cream-outline">Soft Cream Outline</button>
<button class="btn--understory-charcoal-outline">Charcoal Outline</button>
<button class="btn--understory-sage-green-outline">Sage Green Outline</button>
<button class="btn--understory-earth-brown-outline">Earth Brown Outline</button>
```

### 3. Ghost Buttons
```html
<button class="btn--understory-deep-forest-ghost">Deep Forest Ghost</button>
<button class="btn--understory-forest-green-ghost">Forest Green Ghost</button>
<button class="btn--understory-warm-brown-ghost">Warm Brown Ghost</button>
<button class="btn--understory-golden-amber-ghost">Golden Amber Ghost</button>
<button class="btn--understory-soft-cream-ghost">Soft Cream Ghost</button>
<button class="btn--understory-charcoal-ghost">Charcoal Ghost</button>
<button class="btn--understory-sage-green-ghost">Sage Green Ghost</button>
<button class="btn--understory-earth-brown-ghost">Earth Brown Ghost</button>
```

### 4. Gradient Buttons
```html
<button class="btn--understory-deep-forest-gradient">Deep Forest Gradient</button>
<button class="btn--understory-forest-green-gradient">Forest Green Gradient</button>
<button class="btn--understory-warm-brown-gradient">Warm Brown Gradient</button>
<button class="btn--understory-golden-amber-gradient">Golden Amber Gradient</button>
<button class="btn--understory-soft-cream-gradient">Soft Cream Gradient</button>
<button class="btn--understory-charcoal-gradient">Charcoal Gradient</button>
<button class="btn--understory-sage-green-gradient">Sage Green Gradient</button>
<button class="btn--understory-earth-brown-gradient">Earth Brown Gradient</button>
```

## SASS Mixins

### Quick Button Generation
```scss
.my-button {
    @include understory-btn('deep-forest', 'solid');
}
```

### Forest-Themed Buttons
```scss
.forest-primary {
    @include forest-btn('primary');
}

.forest-secondary {
    @include forest-btn('secondary');
}

.forest-accent {
    @include forest-btn('accent');
}
```

### Earth-Themed Buttons
```scss
.earth-primary {
    @include earth-btn('primary');
}

.earth-outline {
    @include earth-btn('outline');
}
```

### Neutral Buttons
```scss
.neutral-primary {
    @include neutral-btn('primary');
}

.neutral-ghost {
    @include neutral-btn('ghost');
}
```

## Color Combinations

The system automatically handles color contrast:

- **Dark colors** (Deep Forest, Forest Green, Charcoal, Earth Brown) → White text
- **Light colors** (Warm Brown, Golden Amber, Soft Cream, Sage Green) → Dark text

## Accessibility Features

- Minimum 44px touch targets
- Focus indicators with golden amber outline
- Proper color contrast ratios
- Disabled states with reduced opacity
- Screen reader friendly

## Generated Classes

The system automatically generates:
- `btn--understory-[color]` (solid)
- `btn--understory-[color]-outline` (outline)
- `btn--understory-[color]-ghost` (ghost)

Total: 32 button variations (8 colors × 4 styles)
