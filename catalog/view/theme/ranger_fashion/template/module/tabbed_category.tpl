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
<div class="deal-of-day-section mt-20" id="<?php echo $module_uid; ?>">
    <div class="container">
        <!-- Modern Header with Red Tab, Navigation Tabs, and Arrow Buttons -->
        <div class="tcp-modern-header">
            <div class="tcp-header-left">
                <div class="tcp-modern-title-tab">
                    <strong><?php echo !empty($name) ? htmlspecialchars($name) : 'Deals Of The Week'; ?></strong>
                </div>
            </div>
            <div class="tcp-header-center">
                <?php if (count($tabs) > 1) { ?>
                <div class="tcp-tabs-nav">
                    <?php $i=0; foreach ($tabs as $tab) { ?>
                    <button type="button" class="tcp-tab-btn <?php echo $i==0 ? 'tcp-tab-active' : ''; ?>" 
                            data-tab-id="<?php echo $i; ?>">
                        <?php echo htmlspecialchars($tab['title']); ?>
                    </button>
                    <?php $i++; } ?>
                </div>
                <?php } ?>
            </div>
            <div class="tcp-header-right">
                <?php if (!empty($date_end)) { ?>
                <div class="tcp-countdown-wrapper">
                    <div class="tcp-countdown" data-date-time="<?php echo htmlspecialchars($date_end); ?>">
                        <div class="tcp-countdown-item">
                            <span class="tcp-countdown-value">00</span>
                            <span class="tcp-countdown-label">Days</span>
                        </div>
                        <div class="tcp-countdown-item">
                            <span class="tcp-countdown-value">00</span>
                            <span class="tcp-countdown-label">Hrs</span>
                        </div>
                        <div class="tcp-countdown-item">
                            <span class="tcp-countdown-value">00</span>
                            <span class="tcp-countdown-label">Min</span>
                        </div>
                        <div class="tcp-countdown-item">
                            <span class="tcp-countdown-value">00</span>
                            <span class="tcp-countdown-label">Sec</span>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <div class="tcp-nav-arrows">
                    <button type="button" class="tcp-nav-btn tcp-prev-btn" aria-label="Previous">
                        <i class="fa fa-chevron-left"></i>
                    </button>
                    <button type="button" class="tcp-nav-btn tcp-next-btn" aria-label="Next">
                        <i class="fa fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-lg-12">
        <?php $i=0; foreach ($tabs as $tab) { ?>
                <div class="tabbed-category-slider-wrapper <?php echo $i==0 ? 'active' : ''; ?>" data-tab-id="<?php echo $i; ?>" style="<?php echo $i==0 ? '' : 'display: none;'; ?>">
                    <div class="popular-category-slider owl-carousel">
          <?php if (!empty($tab['products']) && is_array($tab['products'])) { ?>
              <?php foreach ($tab['products'] as $product) { ?>
                            <div class="slider-item">
                                <div class="product-card">
                                    <div class="product-thumb">
                <?php if ($product['discount']) { ?>
                                        <div class="product-badge product-badge2 bg-info">-<?php echo $product['discount']; ?>%</div>
                <?php } ?>
                                        <img class="lazy" alt="<?php echo htmlspecialchars($product['name']); ?>" src="<?php echo $product['thumb']; ?>">
                                        <div class="product-button-group">
                                            <a class="product-button wishlist_store" href="javascript:;" title="Wishlist" onclick="wishlist.add('<?php echo $product['product_id']; ?>'); return false;"><i class="icon-heart"></i></a>
                                            <a class="product-button product_compare" href="javascript:;" title="Compare" onclick="compare.add('<?php echo $product['product_id']; ?>'); return false;"><i class="icon-repeat"></i></a>
                                            <a class="product-button add_to_single_cart" data-target="<?php echo $product['product_id']; ?>" href="javascript:;" title="To Cart" onclick="cart.add('<?php echo $product['product_id']; ?>'); return false;"><i class="icon-shopping-cart"></i></a>
                                        </div>
              </div>
                                    <div class="product-card-body">
                                        <?php if ($product['category_name']) { ?>
                                        <div class="product-category"><a href="javascript:;"><?php echo htmlspecialchars($product['category_name']); ?></a></div>
                <?php } ?>
                                        <h3 class="product-title"><a href="<?php echo $product['href']; ?>"><?php echo htmlspecialchars($product['name']); ?></a></h3>
                                        <div class="rating-stars">
                  <?php for ($s = 1; $s <= 5; $s++) { ?>
                                            <i class="fas fa-star<?php echo ($s <= $product['rating']) ? ' filled' : ''; ?>"></i>
                  <?php } ?>
                </div>
                                        <h4 class="product-price">
                      <?php if ($product['special']) { ?>
                                            <del><?php echo $product['price']; ?></del>
                                            <?php echo $product['special']; ?>
                      <?php } else { ?>
                                            <?php echo $product['price']; ?>
                      <?php } ?>
                                        </h4>
                                        <?php if (!empty($product['points']) && $product['points'] > 0) { ?>
                                        <div class="module-reward-points">
                                          <i class="fa fa-gift"></i>
                                          <span>Earn <?php echo $product['points']; ?> points</span>
                                        </div>
                                        <?php } ?>
                    </div>
                  </div>
                </div>
            <?php } ?>
          <?php } else { ?>
                            <div class="text-center p-4">No products found in this category.</div>
              <?php } ?>
            </div>
          </div>
        <?php $i++; } ?>
            </div>
      </div>
  </div>
</div>

<style>
/* Deal of Day Section */
.deal-of-day-section {
    padding: 40px 0;
    background-color: #fff;
}

.deal-of-day-section .container {
    max-width: 80%;
    margin: 0 auto;
    padding: 0 20px;
    width: 100%;
    box-sizing: border-box;
}

/* Mobile: Full width */
@media (max-width: 767px) {
    .deal-of-day-section .container {
    max-width: 100% !important;
        padding: 0 15px !important;
    }
}

.mt-20 {
    margin-top: 20px !important;
}

/* Modern Header Styles - Tabbed Category Products */
.tcp-modern-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: #f5f5f5;
    padding: 15px 20px;
    border-bottom: 1px solid #e0e0e0;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 15px;
}

.tcp-header-left {
    flex-shrink: 0;
}

.tcp-modern-title-tab {
    margin: 0;
    padding: 0;
}

.tcp-modern-title-tab strong {
    display: inline-block;
    padding: 10px 20px;
    background-color: #ff505a;
    color: #fff;
    font-weight: 700;
    text-transform: uppercase;
    position: relative;
    font-size: 16px;
    line-height: 1.4;
}

.tcp-modern-title-tab strong:before {
    content: "";
    position: absolute;
    left: 0;
    bottom: -10px;
    width: 0;
    height: 0;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-bottom: 10px solid #ff1d2a;
}

.tcp-header-center {
    flex: 1;
    display: flex;
    justify-content: center;
    min-width: 200px;
}

.tcp-tabs-nav {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    -ms-overflow-style: none;
}

.tcp-tabs-nav::-webkit-scrollbar {
    display: none;
}

.tcp-tab-btn {
    background-color: transparent;
    border: none;
    color: #555;
    padding: 10px 15px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    white-space: nowrap;
    transition: color 0.3s ease;
}

.tcp-tab-btn:hover {
    color: #ff6b9d;
}

.tcp-tab-btn.tcp-tab-active {
    color: #ff505a;
    font-weight: 700;
    border-bottom: 2px solid #ff505a;
}

.tcp-header-right {
    flex-shrink: 0;
    display: flex;
    align-items: center;
    gap: 15px;
}

.tcp-countdown-wrapper {
    display: flex;
    align-items: center;
}

.tcp-countdown {
    display: flex;
    gap: 8px;
}

.tcp-countdown-item {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 8px;
    padding: 8px 10px;
    min-width: 50px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(102,126,234,0.3);
}

.tcp-countdown-value {
    display: block;
    font-size: 16px;
    font-weight: 700;
    color: #fff;
    line-height: 1;
}

.tcp-countdown-label {
    display: block;
    font-size: 9px;
    color: rgba(255,255,255,0.8);
    text-transform: uppercase;
    margin-top: 4px;
    letter-spacing: 0.5px;
}

.tcp-nav-arrows {
    display: flex;
    gap: 8px;
}

.tcp-nav-btn {
    width: 36px;
    height: 36px;
    border: 1px solid #e0e0e0;
    background: #fff;
    color: #333;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    border-radius: 4px;
    font-size: 14px;
}

.tcp-nav-btn:hover {
    background: #ff505a;
    color: #fff;
    border-color: #ff505a;
}

/* Premium Product Card Styles (Consistent with Featured/Latest) */
.product-card {
    display: block;
    position: relative;
    width: 100%;
    border-radius: 12px;
    background-color: #fff;
    overflow: hidden;
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    box-shadow: none;
}

.product-card:hover {
    border-color: rgba(0,0,0,0.05);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
    transform: translateY(-5px);
}

.product-card .product-thumb {
    display: block;
    width: 100%;
    overflow: hidden;
    position: relative;
    padding-top: 100%; /* 1:1 Aspect Ratio */
}

.product-card .product-thumb > img {
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.product-card:hover .product-thumb > img {
    transform: scale(1.05);
}

.product-card .product-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    border-radius: 4px;
    padding: 4px 8px;
    height: auto;
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    line-height: 1.2;
    z-index: 9;
    background: #ff6b9d !important;
}

.product-card .product-button-group {
    position: absolute;
    left: 0;
    bottom: -50px;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    opacity: 0;
    visibility: hidden;
    z-index: 15;
    transition: bottom 0.3s ease, opacity 0.3s ease;
    pointer-events: none;
    background: rgba(255,255,255,0.9);
    padding: 10px;
    backdrop-filter: blur(5px);
}

.product-card:hover .product-button-group {
    bottom: 0;
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
}

.product-card .product-button-group .product-button {
    height: 35px;
    width: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #333;
    padding: 0;
    text-align: center;
    text-decoration: none;
    border-radius: 50%;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    margin: 0;
    background: #fff !important;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
    z-index: 16;
}

.product-card .product-button-group .product-button:hover {
    background: #ff6b9d !important;
    color: #fff !important;
    transform: scale(1.1);
}

.product-card .product-button-group .product-button i {
    font-size: 14px;
    color: inherit;
    line-height: 1;
    display: block;
}

.product-card .product-card-body {
    padding: 15px;
    text-align: center;
    display: flex;
    flex-direction: column;
}

.product-card .product-category {
    width: 100%;
    margin-bottom: 6px;
    font-size: 12px;
}

.product-card .product-category > a {
    transition: color 0.2s;
    color: #999;
    text-decoration: none;
}

.product-card .product-category > a:hover {
    color: #ff6b9d;
}

.product-card .product-title {
    margin-bottom: 8px;
    font-size: 15px;
    font-weight: 500;
}

.product-card .product-title > a {
    transition: color 0.2s;
    color: #333;
    text-decoration: none;
    display: block;
    line-height: 1.4;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    height: 42px; /* Fixed height for 2 lines */
}

.product-card .product-title > a:hover {
    color: #ff6b9d;
}

.product-card .rating-stars {
    display: block;
    margin-bottom: 8px;
}

.product-card .rating-stars > i {
    display: inline-block;
    margin-right: 2px;
    color: #e0e0e0;
    font-size: 12px;
}

.product-card .rating-stars > i.filled {
    color: #ffc107;
}

.product-card .product-price {
    display: inline-block;
    margin-bottom: 0;
    font-size: 16px;
    font-weight: 600;
    text-align: center;
    color: #1a1a1a;
    margin-top: auto;
}

.product-card .product-price > del {
    margin-right: 5px;
    color: #999;
    font-weight: 400;
    font-size: 14px;
    text-decoration: line-through;
}

/* Slider Item */
.slider-item {
    padding: 10px 0;
}

/* Owl Carousel Navigation */
.popular-category-slider.owl-carousel .owl-nav div {
    width: 36px;
    height: 36px;
    line-height: 36px;
    border: 0;
    border-radius: 50%;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    background: #fff !important;
    color: #333 !important;
    opacity: 1 !important;
    top: 50%;
    transform: translateY(-50%);
    transition: all 0.3s ease;
}

.popular-category-slider.owl-carousel .owl-prev {
    left: -18px;
    right: auto;
}

.popular-category-slider.owl-carousel .owl-next {
    right: -18px;
    left: auto;
}

.popular-category-slider.owl-carousel .owl-nav div:hover {
    background: #ff6b9d !important;
    color: #fff !important;
}

/* Old tab styles removed - using new tcp-tab-btn styles above */

/* Responsive Design */
@media (max-width: 992px) {
    .tcp-modern-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .tcp-header-center {
        width: 100%;
        justify-content: flex-start;
    }
    
    .tcp-header-right {
        width: 100%;
        justify-content: space-between;
    }
}

@media (max-width: 767px) {
    .deal-of-day-section {
        padding: 20px 0;
    }
    
    .tcp-modern-header {
        padding: 15px;
    }
    
    .tcp-modern-title-tab strong {
        font-size: 14px;
        padding: 8px 16px;
    }
    
    .tcp-modern-title-tab strong:before {
        bottom: -8px;
        border-left: 8px solid transparent;
        border-right: 8px solid transparent;
        border-bottom: 8px solid #ff1d2a;
    }
    
    .tcp-tabs-nav {
        width: 100%;
        justify-content: flex-start;
    }
    
    .tcp-countdown-item {
        min-width: 45px;
        padding: 6px 8px;
    }
    
    .tcp-countdown-value {
        font-size: 14px;
    }
    
    .tcp-countdown-label {
        font-size: 8px;
    }
    
    .tcp-nav-btn {
        width: 32px;
        height: 32px;
        font-size: 12px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    var root = document.getElementById('<?php echo $module_uid; ?>');
    if (!root || root.dataset.tcp2Initialized) {
        return;
    }
    root.dataset.tcp2Initialized = 'true';

    var sliders = root.querySelectorAll('.popular-category-slider');
    var owlCarousels = [];
    var currentActiveCarousel = null;

    // Initialize owl carousel for each tab with AUTOMATIC SLIDING
    sliders.forEach(function(slider) {
        if (typeof jQuery !== 'undefined' && jQuery.fn.owlCarousel) {
            var owl = jQuery(slider).owlCarousel({
                loop: true,
                margin: 15,
                nav: false,
                dots: false,
                autoplay: true, // ENABLE AUTOMATIC SLIDING
                autoplayTimeout: 4000, // 4 seconds
                autoplayHoverPause: true,
                autoplaySpeed: 800,
                smartSpeed: 600,
                responsive: {
                    0: {
                        items: 2,
                        margin: 8,
                        slideBy: 2
                    },
                    576: {
                        items: 2,
                        margin: 10,
                        slideBy: 2
                    },
                    768: {
                        items: 4,
                        margin: 12,
                        slideBy: 2
                    },
                    992: {
                        items: 4,
                        margin: 15
                    },
                    1200: {
                        items: 5,
                        margin: 15
                    }
                }
            });
            owlCarousels.push(owl);
            
            // Set first visible carousel as active
            if (jQuery(slider).closest('.tabbed-category-slider-wrapper').hasClass('active')) {
                currentActiveCarousel = owl;
            }
        }
    });

    // Tab switching functionality with new button classes
    var tabWrappers = root.querySelectorAll('.tabbed-category-slider-wrapper');
    var currentTab = 0;

    // Tab click handlers if multiple tabs exist
    if (tabWrappers.length > 1) {
        var tabItems = root.querySelectorAll('.tcp-tab-btn');
        tabItems.forEach(function(tab, index) {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                var tabId = parseInt(this.getAttribute('data-tab-id'));
                
                // Update active tab
                tabItems.forEach(function(t) { 
                    t.classList.remove('tcp-tab-active'); 
                });
                this.classList.add('tcp-tab-active');
                
                // Show/hide sliders
                tabWrappers.forEach(function(wrapper, idx) {
                    if (idx === tabId) {
                        wrapper.style.display = 'block';
                        wrapper.classList.add('active');
                        // Get and set active carousel
                        var slider = wrapper.querySelector('.popular-category-slider');
                        if (slider && typeof jQuery !== 'undefined' && jQuery(slider).data('owl.carousel')) {
                            currentActiveCarousel = jQuery(slider).data('owl.carousel');
                            jQuery(slider).trigger('refresh.owl.carousel');
                        }
                    } else {
                        wrapper.style.display = 'none';
                        wrapper.classList.remove('active');
                    }
                });
                
                currentTab = tabId;
            });
        });
    }

    // Arrow navigation for carousel
    var $prevBtn = root.querySelector('.tcp-prev-btn');
    var $nextBtn = root.querySelector('.tcp-next-btn');
    
    if ($prevBtn && $nextBtn) {
        $prevBtn.addEventListener('click', function() {
            if (currentActiveCarousel) {
                currentActiveCarousel.trigger('prev.owl.carousel');
            } else if (owlCarousels.length > 0) {
                owlCarousels[0].trigger('prev.owl.carousel');
            }
        });
        
        $nextBtn.addEventListener('click', function() {
            if (currentActiveCarousel) {
                currentActiveCarousel.trigger('next.owl.carousel');
            } else if (owlCarousels.length > 0) {
                owlCarousels[0].trigger('next.owl.carousel');
            }
        });
    }

    // Initialize countdown timer if date_end is set
    var $countdown = root.querySelector('.tcp-countdown');
    if ($countdown) {
        var endDateStr = $countdown.getAttribute('data-date-time');
        if (endDateStr) {
            // Parse date from "YYYY-MM-DD HH:mm" format
            var dateParts = endDateStr.split(' ');
            var datePart = dateParts[0].split('-');
            var timePart = dateParts[1] ? dateParts[1].split(':') : ['00', '00'];
            
            var targetDate = new Date(
                parseInt(datePart[0]), // year
                parseInt(datePart[1]) - 1, // month (0-indexed)
                parseInt(datePart[2]), // day
                parseInt(timePart[0]), // hour
                parseInt(timePart[1]) // minute
            );
            
            if (!isNaN(targetDate.getTime())) {
                var $items = $countdown.querySelectorAll('.tcp-countdown-item');
                
                function updateCountdown() {
                    var now = new Date().getTime();
                    var distance = targetDate.getTime() - now;
                    
                    if (distance < 0) {
                        $items[0].querySelector('.tcp-countdown-value').textContent = '00';
                        $items[1].querySelector('.tcp-countdown-value').textContent = '00';
                        $items[2].querySelector('.tcp-countdown-value').textContent = '00';
                        $items[3].querySelector('.tcp-countdown-value').textContent = '00';
                        return;
                    }
                    
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    
                    $items[0].querySelector('.tcp-countdown-value').textContent = String(days).padStart(2, '0');
                    $items[1].querySelector('.tcp-countdown-value').textContent = String(hours).padStart(2, '0');
                    $items[2].querySelector('.tcp-countdown-value').textContent = String(minutes).padStart(2, '0');
                    $items[3].querySelector('.tcp-countdown-value').textContent = String(seconds).padStart(2, '0');
                }
                
                updateCountdown();
                setInterval(updateCountdown, 1000);
            }
        }
    }
});
</script>
