<?php 
// Debug: Ensure module displays even if something is wrong
if (empty($tabs)) {
    // Show debug message if no tabs (only visible if debugging)
    if (isset($_GET['debug_module'])) {
        echo '<!-- TabbedCategory Module: No tabs configured -->';
    }
    return;
}
?>
<div class="tcp2-section banner-fullscreen <?php echo !empty($class) ? htmlspecialchars($class) : ''; ?>" id="<?php echo $module_uid; ?>" style="width: 95vw !important; margin-left: calc(-47.5vw + 50%) !important; margin-right: calc(-47.5vw + 50%) !important; padding: 30px 0 !important; margin-top: 0 !important; margin-bottom: 0 !important; position: relative !important; box-sizing: border-box !important;">
  <?php if (!empty($name)) { ?>
    <div class="tcp2-module-title">
      <h2><?php echo htmlspecialchars($name); ?></h2>
      <?php if (!empty($blurb)) { ?>
        <p class="tcp2-module-blurb"><?php echo htmlspecialchars($blurb); ?></p>
      <?php } ?>
    </div>
  <?php } ?>
  <div class="tcp2-container" style="max-width: 100% !important; width: 100% !important; padding: 0 20px !important; box-sizing: border-box !important; margin: 0 auto !important;">
    <div class="tcp2-header">
      <ul class="tcp2-tablist" role="tablist">
        <?php $i=0; foreach ($tabs as $tab) { ?>
          <?php if (!empty($tab['title'])) { ?>
          <li class="tcp2-tab <?php echo $i==0 ? 'active' : ''; ?>" role="tab" data-tab-id="<?php echo $i; ?>"><?php echo htmlspecialchars($tab['title']); ?></li>
          <?php } ?>
        <?php $i++; } ?>
      </ul>
      <div class="tcp2-nav">
        <button type="button" class="tcp2-nav-btn prev">&lt;</button>
        <button type="button" class="tcp2-nav-btn next">&gt;</button>
      </div>
    </div>

    <div class="tcp2-tabpanes">
        <?php $i=0; foreach ($tabs as $tab) { ?>
      <div class="tcp2-tabpane <?php echo $i==0 ? 'active' : ''; ?>" data-pane-id="<?php echo $i; ?>">
        <div class="tcp2-products">
          <?php if (!empty($tab['products']) && is_array($tab['products'])) { ?>
              <?php foreach ($tab['products'] as $product) { ?>
            <div class="tcp2-card">
              <div class="tcp2-thumb">
                <a href="<?php echo $product['href']; ?>">
                    <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" />
                  </a>
                <?php if ($product['discount']) { ?>
                <span class="tcp2-badge">-<?php echo $product['discount']; ?>%</span>
                <?php } ?>
              </div>
              <div class="tcp2-info">
                <?php if ($product['manufacturer']) { ?>
                <span class="tcp2-category"><?php echo $product['manufacturer']; ?></span>
                <?php } ?>
                <a href="<?php echo $product['href']; ?>" class="tcp2-name"><?php echo $product['name']; ?></a>
                <div class="tcp2-rating">
                  <?php for ($s = 1; $s <= 5; $s++) { ?>
                  <span class="star<?php echo ($s <= $product['rating']) ? ' filled' : ''; ?>"></span>
                  <?php } ?>
                </div>
                <div class="tcp2-price">
                      <?php if ($product['special']) { ?>
                  <span class="tcp2-price-new"><?php echo $product['special']; ?></span>
                  <span class="tcp2-price-old"><?php echo $product['price']; ?></span>
                      <?php } else { ?>
                  <span class="tcp2-price-current"><?php echo $product['price']; ?></span>
                      <?php } ?>
                    </div>
                  </div>
                </div>
            <?php } ?>
          <?php } else { ?>
            <p style="padding: 20px; text-align: center; width: 100%;">No products found in this category.</p>
              <?php } ?>
            </div>
          </div>
        <?php $i++; } ?>
      </div>
  </div>
</div>

<style>
.tcp2-module-title {
    text-align: left;
    margin-bottom: 20px;
    padding: 0 20px;
}
.tcp2-module-title h2 {
    font-size: 28px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0 0 8px 0;
    letter-spacing: -0.02em;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    position: relative;
    padding: 20px 0 16px 0;
}
.tcp2-module-title h2::after {
    content: '';
    position: absolute;
    bottom: 8px;
    left: 0;
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #ff6b9d, #ff8c9f);
    border-radius: 2px;
}
.tcp2-module-blurb {
    font-size: 14px;
    color: #6b7280;
    margin: 0;
}
.tcp2-section.banner-fullscreen {
    width: 95vw !important;
    margin-left: calc(-47.5vw + 50%) !important;
    margin-right: calc(-47.5vw + 50%) !important;
    padding: 30px 0 !important;
    margin-top: 0 !important;
    margin-bottom: 0 !important;
    background: #fff;
    position: relative;
    box-sizing: border-box;
}
.tcp2-container {
    max-width: 100% !important;
    width: 100% !important;
    padding: 0 20px !important;
    box-sizing: border-box !important;
    margin: 0 auto !important;
}
.tcp2-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    border-bottom: 1px solid #e5e7eb;
}
.tcp2-tablist {
    display: flex;
    gap: 24px;
    list-style: none;
    padding: 0;
    margin: 0;
    overflow-x: auto;
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
}
.tcp2-tablist::-webkit-scrollbar {
    display: none; /* Chrome, Safari and Opera */
}
.tcp2-tab {
    padding: 12px 0;
    cursor: pointer;
    color: #6b7280;
    font-weight: 600;
    border-bottom: 3px solid transparent;
    transition: color .2s ease, border-color .2s ease;
    white-space: nowrap;
}
.tcp2-tab:hover {
    color: #111827;
}
.tcp2-tab.active {
    color: #ef6c00;
    border-color: #ef6c00;
}
.tcp2-nav {
    display: flex;
    gap: 8px;
}
.tcp2-nav-btn {
    border: 1px solid #e5e7eb;
    background: #fff;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    transition: background .2s ease, border-color .2s ease;
    font-size: 18px;
    line-height: 30px;
    text-align: center;
    color: #333;
}
.tcp2-nav-btn:hover {
    background: #f3f4f6;
    border-color: #d1d5db;
}
.tcp2-tabpane {
    display: none;
}
.tcp2-tabpane.active {
    display: block;
}
.tcp2-tabpane.active .tcp2-card {
    animation: tcp2-fade-in 0.4s ease-in-out forwards;
}
@keyframes tcp2-fade-in {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}
.tcp2-products {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none; /* Firefox */
}
.tcp2-products::-webkit-scrollbar {
    display: none; /* Safari and Chrome */
}
.tcp2-card {
    background: #fff;
    border: none;
    border-radius: 0;
    overflow: hidden;
    box-shadow: none;
    flex-shrink: 0;
    scroll-snap-align: start;
    opacity: 1;
    width: calc(16.666% - 6.67px); /* 6 items: 100% / 6 = 16.666%. Gap: 8px * 5 gaps / 6 = 6.67px */
    transition: transform 0.25s ease-out;
}
.tcp2-card:hover {
    transform: scale(1.03);
}
.tcp2-thumb {
    --gallery-aspect-ratio: 1.0;
    display: block;
    background: #fff;
    position: relative;
    padding-top: 100%;
    aspect-ratio: 1.0;
    overflow: hidden;
    border-radius: 0;
    width: 100%;
    transition: transform 0.25s ease-out;
}
.tcp2-card:hover .tcp2-thumb {
    transform: scale(1.03);
}
.tcp2-thumb img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
    image-rendering: -webkit-optimize-contrast;
    image-rendering: crisp-edges;
    image-rendering: high-quality;
    aspect-ratio: inherit;
}
.tcp2-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #ffc107;
    color: #111;
    font-size: 12px;
    font-weight: 700;
    padding: 4px 8px;
    border-radius: 6px;
}
.tcp2-info {
    padding: 10px;
}
.tcp2-category {
    font-size: 11px;
    color: #6b7280;
    display: block;
    margin-bottom: 3px;
    text-transform: capitalize;
}
.tcp2-name {
    display: block;
    color: #111827;
    text-decoration: none;
    font-weight: 600;
    height: 36px;
    overflow: hidden;
    margin-bottom: 6px;
    font-size: 14px;
    line-height: 18px;
}
.tcp2-name:hover {
    text-decoration: underline;
}
.tcp2-rating {
    margin-bottom: 6px;
    font-size: 12px;
}
.tcp2-rating .star {
    display: inline-block;
    font-style: normal;
}
.tcp2-rating .star::before {
    content: '☆';
    color: #d1d5db;
}
.tcp2-rating .star.filled::before {
    content: '★';
    color: #f59e0b;
}
.tcp2-price {
    display: flex;
    gap: 8px;
    align-items: center;
    font-weight: 700;
}
.tcp2-price-old {
    text-decoration: line-through;
    color: #9ca3af;
    font-weight: 400;
}
.tcp2-price-new {
    color: #ef6c00;
}
.tcp2-price-current {
    color: #111827;
}

@media (max-width: 1400px) {
    .tcp2-section.banner-fullscreen .tcp2-container {
        max-width: 100% !important;
        width: 100% !important;
        padding: 0 20px !important;
    }
}

@media (max-width: 1200px) {
    .tcp2-section.banner-fullscreen .tcp2-container {
        max-width: 100% !important;
        width: 100% !important;
        padding: 0 20px !important;
    }
    .tcp2-card { width: calc(25% - 6px); /* 4 items */ }
    .tcp2-products { gap: 8px; }
}
@media (max-width: 992px) {
    .tcp2-section.banner-fullscreen .tcp2-container {
        max-width: 100% !important;
        width: 100% !important;
        padding: 0 15px !important;
    }
    .tcp2-card { width: calc(33.333% - 5.33px); /* 3 items */ }
    .tcp2-products { gap: 8px; }
}
@media (max-width: 749px) {
    .tcp2-section.banner-fullscreen {
        width: 95vw !important;
        margin-left: calc(-47.5vw + 50%) !important;
        margin-right: calc(-47.5vw + 50%) !important;
        padding: 25px 0 !important;
    }
    .tcp2-section.banner-fullscreen .tcp2-container {
        max-width: 100% !important;
        width: 100% !important;
        padding: 0 15px !important;
    }
    .tcp2-module-title {
        margin-bottom: 15px;
    }
    .tcp2-module-title h2 {
        font-size: 24px;
    }
    .tcp2-header {
        margin-bottom: 12px;
    }
    .tcp2-nav { display: none; } /* Hide nav on smaller screens */
    .tcp2-card { width: calc(50% - 4px); /* 2 items */ }
    .tcp2-products { gap: 8px; }
    .tcp2-thumb {
        padding-top: 100%;
        aspect-ratio: 1.0;
    }
}
@media (max-width: 576px) {
    .tcp2-section.banner-fullscreen {
        width: 95vw !important;
        margin-left: calc(-47.5vw + 50%) !important;
        margin-right: calc(-47.5vw + 50%) !important;
        padding: 20px 0 !important;
    }
    .tcp2-section.banner-fullscreen .tcp2-container {
        max-width: 100% !important;
        width: 100% !important;
        padding: 0 10px !important;
    }
    .tcp2-module-title h2 {
        font-size: 20px;
    }
    .tcp2-card { width: calc(50% - 4px); }
    .tcp2-products { gap: 8px; }
    .tcp2-thumb {
        padding-top: 100%;
        aspect-ratio: 1.0;
    }
    .tcp2-info {
        padding: 8px;
    }
    .tcp2-name {
        height: 32px;
        font-size: 13px;
        line-height: 16px;
    }
}
</style>

<script>
(function() {
    // Ensure this script doesn't run again for the same module
    var root = document.getElementById('<?php echo $module_uid; ?>');
    if (!root || root.dataset.tcp2Initialized) {
        return;
    }
    root.dataset.tcp2Initialized = 'true';

    var tabs = Array.from(root.querySelectorAll('.tcp2-tab'));
    var panes = Array.from(root.querySelectorAll('.tcp2-tabpane'));
    var navPrev = root.querySelector('.tcp2-nav-btn.prev');
    var navNext = root.querySelector('.tcp2-nav-btn.next');
    var autoScrollInterval = null;

    function switchTab(tabIndex) {
        if (tabs[tabIndex]) {
            tabs.forEach(function(t) { t.classList.remove('active'); });
            panes.forEach(function(p) { p.classList.remove('active'); });

            tabs[tabIndex].classList.add('active');
            if (panes[tabIndex]) {
                panes[tabIndex].classList.add('active');
                // Restart auto-scroll when tab changes
                startAutoScroll();
            }
        }
    }

    tabs.forEach(function(tab, index) {
        tab.addEventListener('click', function() {
            switchTab(index);
        });
    });

    function getActivePaneProducts() {
        var activePane = root.querySelector('.tcp2-tabpane.active');
        return activePane ? activePane.querySelector('.tcp2-products') : null;
    }

    function scrollProducts(direction) {
        var productContainer = getActivePaneProducts();
        if (productContainer) {
            var scrollAmount = productContainer.clientWidth * 0.5;
            var targetScroll = direction === 'next' 
                ? productContainer.scrollLeft + scrollAmount 
                : productContainer.scrollLeft - scrollAmount;
            
            productContainer.scrollTo({ left: targetScroll, behavior: 'smooth' });
            
            // Check if we've reached the end, then reset to beginning
            setTimeout(function() {
                if (direction === 'next' && 
                    productContainer.scrollLeft + productContainer.clientWidth >= productContainer.scrollWidth - 10) {
                    setTimeout(function() {
                        productContainer.scrollTo({ left: 0, behavior: 'smooth' });
                    }, 500);
                }
            }, 600);
        }
    }

    function startAutoScroll() {
        // Clear any existing interval
        if (autoScrollInterval) {
            clearInterval(autoScrollInterval);
        }
        
        // Auto-scroll every 3 seconds
        autoScrollInterval = setInterval(function() {
            scrollProducts('next');
        }, 3000);
    }

    function stopAutoScroll() {
        if (autoScrollInterval) {
            clearInterval(autoScrollInterval);
            autoScrollInterval = null;
        }
    }

    if (navPrev && navNext) {
        navPrev.addEventListener('click', function() {
            stopAutoScroll();
            scrollProducts('prev');
            // Restart auto-scroll after 5 seconds of inactivity
            setTimeout(startAutoScroll, 5000);
        });

        navNext.addEventListener('click', function() {
            stopAutoScroll();
            scrollProducts('next');
            // Restart auto-scroll after 5 seconds of inactivity
            setTimeout(startAutoScroll, 5000);
        });
    }

    // Pause auto-scroll on hover
    root.addEventListener('mouseenter', function() {
        stopAutoScroll();
    });

    // Resume auto-scroll when mouse leaves
    root.addEventListener('mouseleave', function() {
        startAutoScroll();
    });

    // Start auto-scrolling on page load
    startAutoScroll();
})();
</script>