# Theme Color Button System

A simplified SASS formula for generating buttons using the general theme colors.

## Color Palette

The system uses these 21 theme colors:

- **Primary** (`#3943B7`) - Main brand blue
- **Primary Hover** (`#9298DD`) - Primary hover state
- **Secondary** (`#FFCC01`) - Brand yellow
- **Secondary Hover** (`#FFEB99`) - Secondary hover state
- **Tertiary** (`#201E1F`) - Dark neutral
- **Neutral** (`#201E1F`) - Text color
- **Light Neutral** (`#FFFBEB`) - Light background
- **Cream** (`#FFFBEB`) - Cream background
- **Brown** (`#413620`) - Earth tone
- **Red** (`#9E2B25`) - Error color
- **Orange** (`#FF6F00`) - Warning color
- **Yellow** (`#FFCC01`) - Highlight color
- **Green** (`#2FBF71`) - Success color
- **Light Green** (`#DAF7DC`) - Light success
- **Blue** (`#3943B7`) - Primary blue
- **Light Blue** (`#85C7F2`) - Light blue
- **Purple** (`#CB9CF2`) - Purple accent
- **Pink** (`#F283B6`) - Pink accent
- **Gray** (`#CDD1DE`) - Neutral gray
- **Black** (`#201E1F`) - Dark text
- **White** (`#ffffff`) - Light text

## Button Styles

### 1. Solid Buttons
```html
<button class="btn--primary">Primary</button>
<button class="btn--secondary">Secondary</button>
<button class="btn--tertiary">Tertiary</button>
<button class="btn--green">Green</button>
<button class="btn--blue">Blue</button>
<button class="btn--purple">Purple</button>
<button class="btn--pink">Pink</button>
<button class="btn--orange">Orange</button>
<button class="btn--red">Red</button>
<button class="btn--yellow">Yellow</button>
<button class="btn--brown">Brown</button>
<button class="btn--gray">Gray</button>
<button class="btn--black">Black</button>
<button class="btn--white">White</button>
```

### 2. Outline Buttons
```html
<button class="btn--primary-outline">Primary Outline</button>
<button class="btn--secondary-outline">Secondary Outline</button>
<button class="btn--tertiary-outline">Tertiary Outline</button>
<button class="btn--green-outline">Green Outline</button>
<button class="btn--blue-outline">Blue Outline</button>
<button class="btn--purple-outline">Purple Outline</button>
<button class="btn--pink-outline">Pink Outline</button>
<button class="btn--orange-outline">Orange Outline</button>
<button class="btn--red-outline">Red Outline</button>
<button class="btn--yellow-outline">Yellow Outline</button>
<button class="btn--brown-outline">Brown Outline</button>
<button class="btn--gray-outline">Gray Outline</button>
<button class="btn--black-outline">Black Outline</button>
<button class="btn--white-outline">White Outline</button>
```

### 3. Ghost Buttons
```html
<button class="btn--primary-ghost">Primary Ghost</button>
<button class="btn--secondary-ghost">Secondary Ghost</button>
<button class="btn--tertiary-ghost">Tertiary Ghost</button>
<button class="btn--green-ghost">Green Ghost</button>
<button class="btn--blue-ghost">Blue Ghost</button>
<button class="btn--purple-ghost">Purple Ghost</button>
<button class="btn--pink-ghost">Pink Ghost</button>
<button class="btn--orange-ghost">Orange Ghost</button>
<button class="btn--red-ghost">Red Ghost</button>
<button class="btn--yellow-ghost">Yellow Ghost</button>
<button class="btn--brown-ghost">Brown Ghost</button>
<button class="btn--gray-ghost">Gray Ghost</button>
<button class="btn--black-ghost">Black Ghost</button>
<button class="btn--white-ghost">White Ghost</button>
```

## SASS Mixins

### Quick Button Generation
```scss
.my-button {
    @include theme-btn('primary', 'solid');
}

.my-outline-button {
    @include theme-btn('secondary', 'outline');
}

.my-ghost-button {
    @include theme-btn('success', 'ghost');
}
```

### Primary Buttons
```scss
.primary-solid {
    @include primary-btn('solid');
}

.primary-outline {
    @include primary-btn('outline');
}

.primary-ghost {
    @include primary-btn('ghost');
}
```

### Secondary Buttons
```scss
.secondary-solid {
    @include secondary-btn('solid');
}

.secondary-outline {
    @include secondary-btn('outline');
}

.secondary-ghost {
    @include secondary-btn('ghost');
}
```

### Neutral Buttons
```scss
.neutral-solid {
    @include neutral-btn('solid');
}

.neutral-outline {
    @include neutral-btn('outline');
}

.neutral-ghost {
    @include neutral-btn('ghost');
}
```


## Color Combinations

The system automatically handles color contrast:

- **Dark colors** (Primary, Tertiary, Neutral, Brown, Red, Orange, Green, Blue, Purple, Pink, Black) → White text
- **Light colors** (Secondary, Light Neutral, Cream, Yellow, Light Green, Light Blue, Gray, White) → Black text

## Accessibility Features

- Minimum 44px touch targets
- Focus indicators with secondary color outline
- Proper color contrast ratios
- Disabled states with reduced opacity
- Screen reader friendly

## Generated Classes

The system automatically generates:
- `btn--[color]` (solid)
- `btn--[color]-outline` (outline)
- `btn--[color]-ghost` (ghost)

Total: 63 button variations (21 colors × 3 styles)
