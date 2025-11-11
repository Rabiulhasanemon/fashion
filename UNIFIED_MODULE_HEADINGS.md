# Unified Module Heading Style

## Overview
All frontend module headings now use a consistent, beautiful style across the entire site.

## Style Applied
- **Font Size**: 24px (desktop), responsive on mobile
- **Font Weight**: 600 (semi-bold)
- **Color**: #333 (dark gray)
- **Alignment**: Center
- **Padding**: 24px top/bottom
- **Text Transform**: None (preserves original case)
- **Letter Spacing**: 0

## Modules Updated
✅ Flash Deal - `flash-deal-title`  
✅ Featured Flash Sale - `flashdeal-title`  
✅ Special Products - `<h2 class="unified-module-heading">`  
✅ Popular Products - `<h2 class="unified-module-heading">`  
✅ Bestseller - `popular-products__title`  
✅ eBay Listing - `<h2 class="unified-module-heading">`  

## CSS Classes

### Primary Class
```css
.unified-module-heading
```

### Wrapper Class
```css
.module-heading-wrapper
```

## Usage in Templates

### Standard Usage
```html
<div class="module-heading-wrapper">
  <h2 class="unified-module-heading"><?php echo $heading_title; ?></h2>
</div>
```

### With Existing Classes
```html
<h2 class="existing-class unified-module-heading"><?php echo $heading_title; ?></h2>
```

## Responsive Breakpoints

- **Desktop (>992px)**: 24px font, 24px padding
- **Tablet (749px-992px)**: 22px font, 20px padding
- **Mobile (576px-749px)**: 20px font, 18px padding
- **Small Mobile (<576px)**: 18px font, 15px padding

## Files Created

1. `catalog/view/theme/ranger_fashion/template/common/unified_module_heading.css`
   - Base styles for unified headings

2. `catalog/view/theme/ranger_fashion/template/common/unified_module_heading_override.css`
   - Override styles to ensure consistency

## Benefits

✅ **Consistent Design** - All modules look professional and unified  
✅ **Responsive** - Works perfectly on all devices  
✅ **Easy to Maintain** - One style for all modules  
✅ **Beautiful** - Clean, modern, premium appearance  

## Future Modules

When adding new modules, use:
```html
<div class="module-heading-wrapper">
  <h2 class="unified-module-heading"><?php echo $heading_title; ?></h2>
</div>
```

