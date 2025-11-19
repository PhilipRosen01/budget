# Tailwind CSS Responsive Design System

## Overview

Your application already uses Tailwind CSS, which is perfect for responsive design. This guide will standardize responsive patterns across your entire application.

## Tailwind Breakpoints

```css
sm: 640px   /* Small tablets and up */
md: 768px   /* Medium tablets and up */
lg: 1024px  /* Large tablets and desktop */
xl: 1280px  /* Large desktop */
2xl: 1536px /* Extra large desktop */
```

## Responsive Component Patterns

### 1. Header Layouts

**Pattern**: Stack on mobile, side-by-side on desktop

```html
<!-- Main Header Container -->
<div
    class="flex flex-col space-y-4 md:flex-row md:justify-between md:items-center md:space-y-0"
>
    <!-- Title Section -->
    <div class="flex-shrink-0">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Title</h2>
        <p class="text-sm text-gray-500 mt-1 md:mt-0">Subtitle</p>
    </div>

    <!-- Controls Section -->
    <div
        class="flex flex-col space-y-3 md:flex-row md:items-center md:space-y-0 md:space-x-4"
    >
        <!-- Control elements -->
    </div>
</div>
```

### 2. Button Groups

**Pattern**: Stack on mobile, inline on desktop

```html
<!-- Button Container -->
<div class="flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2">
    <button
        class="w-full md:w-auto inline-flex items-center justify-center px-3 py-2 [button-styles]"
    >
        Button 1
    </button>
    <button
        class="w-full md:w-auto inline-flex items-center justify-center px-3 py-2 [button-styles]"
    >
        Button 2
    </button>
</div>
```

### 3. Form Controls

**Pattern**: Full width on mobile, auto width on desktop

```html
<div
    class="flex flex-col space-y-2 md:flex-row md:items-center md:space-y-0 md:space-x-2"
>
    <label class="text-sm font-medium text-gray-700 md:whitespace-nowrap"
        >Label:</label
    >
    <select class="block w-full md:w-48 px-3 py-2 [form-styles]">
        <!-- options -->
    </select>
</div>
```

### 4. Grid Layouts

**Pattern**: Responsive card grids

```html
<!-- Overview Cards (4 columns on large, 2 on medium, 1 on mobile) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- cards -->
</div>

<!-- Content Cards (3 columns on large, 2 on medium, 1 on mobile) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- cards -->
</div>

<!-- Main Content (single column on mobile, 3 columns on large) -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- content sections -->
</div>
```

### 5. Container Spacing

**Pattern**: Responsive padding and margins

```html
<!-- Main Container -->
<div class="py-6 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- content -->
    </div>
</div>

<!-- Section Spacing -->
<div class="mb-6 sm:mb-8">
    <!-- section content -->
</div>
```

## Common Responsive Utility Classes

### Spacing

-   `space-y-2 md:space-y-0` - Vertical spacing on mobile, none on desktop
-   `space-x-2 md:space-x-4` - Horizontal spacing increases on larger screens
-   `px-4 sm:px-6 lg:px-8` - Progressive padding increases

### Sizing

-   `w-full md:w-auto` - Full width on mobile, auto on desktop
-   `w-full md:w-48` - Full width on mobile, fixed width on desktop
-   `text-sm md:text-base` - Smaller text on mobile

### Layout

-   `flex-col md:flex-row` - Stack on mobile, row on desktop
-   `items-start md:items-center` - Different alignment per screen size
-   `justify-start md:justify-between` - Different justification per screen size

### Visibility

-   `hidden md:block` - Hidden on mobile, visible on desktop
-   `block md:hidden` - Visible on mobile, hidden on desktop
-   `md:inline` - Inline only on desktop and up

## Button System

### Primary Actions

```html
<button
    class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
>
    <svg class="w-4 h-4 mr-2" />
    <span class="hidden sm:inline">Full Text</span>
    <span class="sm:hidden">Short</span>
</button>
```

### Secondary Actions

```html
<button
    class="w-full md:w-auto inline-flex items-center justify-center px-3 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition ease-in-out duration-150"
>
    Action
</button>
```

## Implementation Status

### ✅ Already Implemented

-   Dashboard header responsive layout
-   Budget templates header responsive layout
-   Button groups with proper responsive classes
-   Grid layouts for cards and content
-   Container spacing and padding

### 🔧 Areas to Standardize

-   Form layouts consistency
-   Modal responsive design
-   Navigation responsive behavior
-   Table responsive handling

## Best Practices

1. **Mobile First**: Start with mobile styles, add larger screen modifications
2. **Consistent Breakpoints**: Use `md:` (768px) as primary desktop breakpoint
3. **Progressive Enhancement**: Add features/spacing as screen size increases
4. **Touch Targets**: Ensure buttons are at least 44px tall on mobile
5. **Content Priority**: Most important content/actions should be visible on mobile

## Testing Strategy

Test your responsive design at these key breakpoints:

-   **Mobile**: 375px (iPhone)
-   **Tablet**: 768px (iPad portrait)
-   **Desktop**: 1024px (laptop)
-   **Large Desktop**: 1440px (desktop monitor)

Use browser dev tools or actual devices to verify layouts work correctly at all sizes.
