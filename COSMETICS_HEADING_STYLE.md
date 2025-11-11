# Cosmetics Website Module Heading Style

## Overview
All frontend module headings now use a beautiful, left-aligned cosmetics-style design that matches premium cosmetics websites.

## Design Features

### Typography
- **Font Size**: 28px (desktop), responsive on mobile
- **Font Weight**: 600 (semi-bold)
- **Color**: #1a1a1a (elegant dark gray)
- **Font Family**: 'Inter', modern sans-serif
- **Letter Spacing**: -0.02em (tight, elegant)
- **Line Height**: 1.3

### Layout
- **Alignment**: Left-aligned (premium cosmetics style)
- **Padding**: 20px top, 16px bottom
- **Position**: Relative (for accent line)

### Accent Element
- **Pink Gradient Underline**: 60px wide, 3px height
- **Color**: Linear gradient from #ff6b9d to #ff8c9f
- **Position**: Bottom left, 8px below text
- **Border Radius**: 2px (rounded corners)

## Modules Updated

✅ **Flash Deal** - `flash-deal-title`  
✅ **Featured Flash Sale** - `flashdeal-title`  
✅ **Special Products** - Unified heading  
✅ **Popular Products** - Unified heading  
✅ **Bestseller** - `popular-products__title`  
✅ **Latest Products** - `latest-products__title`  
✅ **eBay Listing** - Unified heading  
✅ **Top Seller** - Unified heading  
✅ **Featured List** - Unified heading  
✅ **Featured Category** - Unified heading  
✅ **Tabbed Category** - `tcp2-module-title`  
✅ **Big Offer** - `offer-title`  
✅ **Store** - `panel-heading`  
✅ **Google Hangouts** - `panel-heading`  

## CSS Class

### Primary Class
```css
.cosmetics-module-heading
```

### Usage
```html
<div class="module-heading-wrapper">
  <h2 class="cosmetics-module-heading"><?php echo $heading_title; ?></h2>
</div>
```

## Responsive Breakpoints

- **Desktop (>992px)**: 28px font, 60px accent line
- **Tablet (749px-992px)**: 24px font, 50px accent line
- **Mobile (576px-749px)**: 22px font, 45px accent line
- **Small Mobile (<576px)**: 20px font, 40px accent line

## Design Inspiration

This style is inspired by premium cosmetics brands like:
- Sephora
- Ulta Beauty
- MAC Cosmetics
- Fenty Beauty

Features:
- Clean, modern typography
- Elegant pink accent (cosmetics industry standard)
- Left alignment (premium feel)
- Responsive design

## Benefits

✅ **Consistent Design** - All modules look professional and unified  
✅ **Premium Appearance** - Matches high-end cosmetics websites  
✅ **Responsive** - Perfect on all devices  
✅ **Easy to Maintain** - One style for all modules  
✅ **Beautiful** - Elegant pink accent line adds sophistication  

## Files Created

1. `catalog/view/theme/ranger_fashion/template/common/cosmetics_module_heading.css`
   - Base cosmetics heading styles

## Color Palette

- **Text Color**: #1a1a1a (elegant dark)
- **Accent Gradient**: #ff6b9d → #ff8c9f (cosmetics pink)
- **Background**: Transparent/white

---

**All module headings now have a beautiful, consistent cosmetics-style design!** ✨

