<?php 
// VISUAL DEBUG: Show debug info on page
$template_debug = array();
$template_debug['manufacturers_count'] = isset($manufacturers) ? count($manufacturers) : 0;
$template_debug['manufacturers_data'] = isset($manufacturers) ? $manufacturers : array();
$controller_debug = isset($debug_info) ? $debug_info : array();
?>
<!-- MANUFACTURER DEBUG INFO START -->
<script>
console.group('🔍 Manufacturer Module Debug');
<?php if (!empty($controller_debug)) { ?>
console.group('Controller Debug');
console.log('Settings:', <?php echo json_encode(isset($controller_debug['settings']) ? $controller_debug['settings'] : array(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>);
console.log('Action:', '<?php echo isset($controller_debug['action']) ? htmlspecialchars($controller_debug['action']) : 'N/A'; ?>');
console.log('Final Count:', <?php echo isset($controller_debug['final_count']) ? $controller_debug['final_count'] : 0; ?>);
<?php if (!empty($controller_debug['manufacturers_found'])) { ?>
console.log('Manufacturers Found:', <?php echo json_encode($controller_debug['manufacturers_found'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>);
<?php } ?>
console.groupEnd();
<?php } ?>
console.group('Template Debug');
console.log('Manufacturers Count:', <?php echo $template_debug['manufacturers_count']; ?>);
console.log('Manufacturers Data:', <?php echo json_encode($template_debug['manufacturers_data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>);
<?php if (!empty($template_debug['manufacturers_data'])) { ?>
console.log('First Manufacturer:', <?php echo json_encode($template_debug['manufacturers_data'][0], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>);
<?php } ?>
console.groupEnd();
console.groupEnd();
</script>
<!-- MANUFACTURER DEBUG INFO END -->
<!-- VISUAL DEBUG PANEL -->
<div id="manufacturer-debug-panel" style="position: fixed; bottom: 10px; right: 10px; background: rgba(0,0,0,0.9); color: #0f0; padding: 15px; border-radius: 8px; font-family: monospace; font-size: 11px; z-index: 9999; max-width: 400px; max-height: 300px; overflow: auto; display: none; border: 2px solid #0f0;">
  <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
    <strong style="color: #0f0;">🔍 Manufacturer Debug</strong>
    <button onclick="document.getElementById('manufacturer-debug-panel').style.display='none'" style="background: #f00; color: #fff; border: none; padding: 2px 8px; cursor: pointer; border-radius: 3px;">✕</button>
  </div>
  <div id="debug-content">
    <div><strong>Count:</strong> <span id="debug-count"><?php echo $template_debug['manufacturers_count']; ?></span></div>
    <?php if (!empty($controller_debug)) { ?>
    <div><strong>Action:</strong> <span style="color: #0ff;"><?php echo isset($controller_debug['action']) ? htmlspecialchars($controller_debug['action']) : 'N/A'; ?></span></div>
    <?php } ?>
    <div><strong>Images:</strong> <span id="debug-images-loaded" style="color: #0f0;">0</span> loaded, <span id="debug-images-failed" style="color: #f00;">0</span> failed</div>
    <div id="debug-details" style="margin-top: 10px; font-size: 10px;"></div>
  </div>
</div>
<button onclick="document.getElementById('manufacturer-debug-panel').style.display=document.getElementById('manufacturer-debug-panel').style.display==='none'?'block':'none'" style="position: fixed; bottom: 10px; right: 420px; background: #007bff; color: #fff; border: none; padding: 8px 15px; cursor: pointer; border-radius: 5px; z-index: 9998; font-weight: bold;">🔍 Debug</button>
<script>
(function() {
  let imagesLoaded = 0;
  let imagesFailed = 0;
  const debugDetails = document.getElementById('debug-details');
  const debugImagesLoaded = document.getElementById('debug-images-loaded');
  const debugImagesFailed = document.getElementById('debug-images-failed');
  
  // Track all images
  document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('.manufacturer-brand-image');
    console.log('📸 Found', images.length, 'manufacturer images to track');
    
    images.forEach((img, index) => {
      const card = img.closest('.manufacturer-brand-card');
      const manufacturerId = card ? card.getAttribute('data-manufacturer-id') : 'unknown';
      const manufacturerName = card ? card.getAttribute('title') : 'unknown';
      
      // Add to debug panel
      const detailDiv = document.createElement('div');
      detailDiv.style.marginBottom = '5px';
      detailDiv.style.padding = '5px';
      detailDiv.style.background = 'rgba(255,255,255,0.1)';
      detailDiv.style.borderRadius = '3px';
      detailDiv.id = 'debug-img-' + index;
      detailDiv.innerHTML = `<strong>#${index + 1}:</strong> ${manufacturerName}<br><small style="color: #aaa;">ID: ${manufacturerId}</small><br><small style="color: #ff0;">Loading...</small>`;
      debugDetails.appendChild(detailDiv);
      
      // Track load success
      img.addEventListener('load', function() {
        imagesLoaded++;
        debugImagesLoaded.textContent = imagesLoaded;
        const detail = document.getElementById('debug-img-' + index);
        if (detail) {
          detail.querySelector('small:last-child').textContent = '✅ Loaded: ' + this.src.substring(0, 50) + '...';
          detail.querySelector('small:last-child').style.color = '#0f0';
        }
        console.log('✅ Image #' + (index + 1) + ' loaded:', this.src);
      });
      
      // Track load failure
      img.addEventListener('error', function() {
        imagesFailed++;
        debugImagesFailed.textContent = imagesFailed;
        const detail = document.getElementById('debug-img-' + index);
        if (detail) {
          detail.querySelector('small:last-child').textContent = '❌ FAILED: ' + this.src.substring(0, 50) + '...';
          detail.querySelector('small:last-child').style.color = '#f00';
        }
        console.error('❌ Image #' + (index + 1) + ' FAILED:', this.src);
      });
    });
  });
})();
</script>
<div id="manufacturer-brand-section" class="container brandloop24_section manufacturer-display-wrapper">
  <div class="brandloop24_inner manufacturer-inner-container">
    <div class="brandloop24_track manufacturer-track-container">
      <?php if (isset($manufacturers) && !empty($manufacturers)) { ?>
        <?php foreach ($manufacturers as $index => $manufacturer) { 
          $image_url = !empty($manufacturer['thumb']) ? $manufacturer['thumb'] : '';
          $manufacturer_name = isset($manufacturer['name']) ? htmlspecialchars($manufacturer['name']) : '';
        ?>
        <a class="brandloop24_card manufacturer-brand-card" href="<?php echo isset($manufacturer['href']) ? $manufacturer['href'] : '#'; ?>" title="<?php echo $manufacturer_name; ?>" data-manufacturer-id="<?php echo isset($manufacturer['manufacturer_id']) ? $manufacturer['manufacturer_id'] : ''; ?>" data-index="<?php echo $index; ?>">
          <?php if ($image_url) { ?>
          <img class="manufacturer-brand-image" 
               src="<?php echo htmlspecialchars($image_url, ENT_QUOTES, 'UTF-8'); ?>" 
               alt="<?php echo $manufacturer_name; ?>" 
               title="<?php echo $manufacturer_name . ' - ' . htmlspecialchars($image_url); ?>" 
               loading="lazy" 
               data-src="<?php echo htmlspecialchars($image_url, ENT_QUOTES, 'UTF-8'); ?>"
               style="display: block !important; visibility: visible !important; opacity: 1 !important; min-width: 50px; min-height: 30px; background: #f0f0f0;"
               onload="console.log('✅ Image loaded:', this.src); this.style.opacity='1'; this.style.background='transparent';"
               onerror="console.error('❌ Image FAILED:', this.src, 'for manufacturer:', '<?php echo $manufacturer_name; ?>'); console.error('Full URL:', this.src); this.style.border='2px solid red'; this.style.background='#ffcccc'; this.style.display='block'; this.nextElementSibling.style.display='block';" />
          <div class="manufacturer-brand-name" style="display: none; background: #f0f0f0; padding: 10px; border-radius: 4px;">
            <?php echo $manufacturer_name; ?>
            <small style="display: block; color: #999; font-size: 10px;">Image failed to load</small>
          </div>
          <?php } else { ?>
          <div class="manufacturer-brand-name" style="background: #f0f0f0; padding: 10px; border-radius: 4px;">
            <?php echo $manufacturer_name; ?>
            <small style="display: block; color: #999; font-size: 10px;">No image</small>
          </div>
          <?php } ?>
        </a>
        <?php } ?>
        <?php foreach ($manufacturers as $index => $manufacturer) { 
          $image_url = !empty($manufacturer['thumb']) ? $manufacturer['thumb'] : '';
          $manufacturer_name = isset($manufacturer['name']) ? htmlspecialchars($manufacturer['name']) : '';
        ?>
        <a class="brandloop24_card manufacturer-brand-card" href="<?php echo isset($manufacturer['href']) ? $manufacturer['href'] : '#'; ?>" title="<?php echo $manufacturer_name; ?>" data-manufacturer-id="<?php echo isset($manufacturer['manufacturer_id']) ? $manufacturer['manufacturer_id'] : ''; ?>" data-index="<?php echo $index + count($manufacturers); ?>">
          <?php if ($image_url) { ?>
          <img class="manufacturer-brand-image" 
               src="<?php echo htmlspecialchars($image_url, ENT_QUOTES, 'UTF-8'); ?>" 
               alt="<?php echo $manufacturer_name; ?>" 
               title="<?php echo $manufacturer_name . ' - ' . htmlspecialchars($image_url); ?>" 
               loading="lazy" 
               data-src="<?php echo htmlspecialchars($image_url, ENT_QUOTES, 'UTF-8'); ?>"
               style="display: block !important; visibility: visible !important; opacity: 1 !important; min-width: 50px; min-height: 30px; background: #f0f0f0;"
               onload="console.log('✅ Image loaded:', this.src); this.style.opacity='1'; this.style.background='transparent';"
               onerror="console.error('❌ Image FAILED:', this.src, 'for manufacturer:', '<?php echo $manufacturer_name; ?>'); console.error('Full URL:', this.src); this.style.border='2px solid red'; this.style.background='#ffcccc'; this.style.display='block'; this.nextElementSibling.style.display='block';" />
          <div class="manufacturer-brand-name" style="display: none; background: #f0f0f0; padding: 10px; border-radius: 4px;">
            <?php echo $manufacturer_name; ?>
            <small style="display: block; color: #999; font-size: 10px;">Image failed to load</small>
          </div>
          <?php } else { ?>
          <div class="manufacturer-brand-name" style="background: #f0f0f0; padding: 10px; border-radius: 4px;">
            <?php echo $manufacturer_name; ?>
            <small style="display: block; color: #999; font-size: 10px;">No image</small>
          </div>
          <?php } ?>
        </a>
        <?php } ?>
      <?php } else { ?>
        <!-- DEBUG: No manufacturers found -->
        <div style="padding: 20px; text-align: center; color: #999; background: #fff3cd; border: 2px solid #ffc107; border-radius: 8px; margin: 20px;">
          <strong>⚠️ DEBUG: No manufacturers found</strong><br>
          <small>Check console for details</small>
        </div>
      <?php } ?>
    </div>
  </div>
</div>

<style>
/* Manufacturer Section - Unique IDs and Classes to Avoid Conflicts */
#manufacturer-brand-section.brandloop24_section.manufacturer-display-wrapper {
  padding: 20px 0px !important;
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
  max-width: 80% !important;
  margin: 0 auto !important;
  position: relative !important;
  padding: 0 40px !important;
  z-index: 1;
}
.manufacturer-track-container.brandloop24_track {
  display: flex !important;
  gap: 24px !important;
  animation: brandloop24_scroll 20s linear infinite !important;
  animation-play-state: running !important;
  animation-iteration-count: infinite !important;
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