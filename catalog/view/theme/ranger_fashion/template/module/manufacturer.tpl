<div id="manufacturer-brand-section" class="brandloop24_section manufacturer-display-wrapper">
  <div class="brandloop24_inner manufacturer-inner-container">
    <div class="brandloop24_track manufacturer-track-container">
      <?php foreach ($manufacturers as $manufacturer) { ?>
      <a class="brandloop24_card manufacturer-brand-card" href="<?php echo $manufacturer['href']; ?>" title="<?php echo $manufacturer['name']; ?>">
        <img class="manufacturer-brand-image" src="<?php echo $manufacturer['thumb']; ?>" alt="<?php echo $manufacturer['name']; ?>" title="<?php echo $manufacturer['name']; ?>" loading="lazy" />
      </a>
      <?php } ?>
      <?php foreach ($manufacturers as $manufacturer) { ?>
      <a class="brandloop24_card manufacturer-brand-card" href="<?php echo $manufacturer['href']; ?>" title="<?php echo $manufacturer['name']; ?>">
        <img class="manufacturer-brand-image" src="<?php echo $manufacturer['thumb']; ?>" alt="<?php echo $manufacturer['name']; ?>" title="<?php echo $manufacturer['name']; ?>" loading="lazy" />
      </a>
      <?php } ?>
    </div>
  </div>
</div>

<style>
/* Manufacturer Section - Unique IDs and Classes to Avoid Conflicts */
#manufacturer-brand-section.brandloop24_section.manufacturer-display-wrapper {
  padding: 60px 0 !important;
  background: #ffffff !important;
  overflow: hidden !important;
  position: relative !important;
  display: block !important;
  visibility: visible !important;
}
#manufacturer-brand-section.brandloop24_section.manufacturer-display-wrapper::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(180deg, rgba(255,255,255,0) 0%, rgba(250,250,250,0.5) 50%, rgba(255,255,255,0) 100%);
  pointer-events: none;
  z-index: 0;
}
.manufacturer-inner-container.brandloop24_inner {
  max-width: 1400px !important;
  margin: 0 auto !important;
  position: relative !important;
  padding: 0 40px !important;
  z-index: 1;
}
.manufacturer-track-container.brandloop24_track {
  display: flex !important;
  gap: 24px !important;
  animation: brandloop24_scroll 20s linear infinite !important;
  width: max-content !important;
}
.manufacturer-brand-card.brandloop24_card {
  width: 160px !important;
  height: 90px !important;
  border-radius: 16px !important;
  background: #ffffff !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  padding: 16px 20px !important;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.04) !important;
  border: 1px solid rgba(0, 0, 0, 0.06) !important;
  flex-shrink: 0 !important;
  position: relative !important;
  overflow: visible !important;
  text-decoration: none !important;
}
.manufacturer-brand-card.brandloop24_card::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.6), transparent);
  transition: left 0.5s ease;
  z-index: 1;
}
.manufacturer-brand-card.brandloop24_card:hover::before {
  left: 100%;
}
/* Manufacturer Image Styles - Ensure Visibility */
.manufacturer-brand-image {
  max-width: 100% !important;
  max-height: 100% !important;
  width: auto !important;
  height: auto !important;
  object-fit: contain !important;
  filter: grayscale(40%) brightness(0.95) !important;
  opacity: 0.8 !important;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
  position: relative !important;
  z-index: 2 !important;
  display: block !important;
  visibility: visible !important;
  margin: 0 auto !important;
}
.manufacturer-brand-card.brandloop24_card:hover {
  transform: translateY(-8px) scale(1.02) !important;
  background: #ffffff !important;
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12), 0 4px 12px rgba(0, 0, 0, 0.08) !important;
  border-color: rgba(0, 0, 0, 0.1) !important;
}
.manufacturer-brand-card.brandloop24_card:hover .manufacturer-brand-image {
  filter: grayscale(0%) brightness(1) !important;
  opacity: 1 !important;
  transform: scale(1.05) !important;
}
/* Fallback text if no image */
.manufacturer-brand-name {
  font-size: 14px;
  font-weight: 600;
  color: #333;
  text-align: center;
  z-index: 2;
  position: relative;
}
@keyframes brandloop24_scroll {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}
@media (max-width: 768px) {
  #manufacturer-brand-section.brandloop24_section.manufacturer-display-wrapper {
    padding: 40px 0 !important;
  }
  .manufacturer-inner-container.brandloop24_inner {
    padding: 0 20px !important;
  }
  .manufacturer-track-container.brandloop24_track {
    animation-duration: 25s !important;
    gap: 16px !important;
  }
  .manufacturer-brand-card.brandloop24_card {
    width: 120px !important;
    height: 70px !important;
    padding: 12px 16px !important;
  }
}
@media (max-width: 480px) {
  .manufacturer-brand-card.brandloop24_card {
    width: 100px !important;
    height: 60px !important;
    padding: 10px 12px !important;
  }
  .manufacturer-brand-image {
    max-width: 90% !important;
    max-height: 90% !important;
  }
}
</style>